<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class Booking extends Model
{
    use HasFactory;

    protected $guarded = [];


    protected static function boot()
    {
        parent::boot();

        self::updated(function ($model) {

            switch ($model->status) {
                case 'confirmed':
                    if ($model->start == null) {
                        $model->start = Carbon::now();
                        $model->save();
                    }
                    break;

                case 'parked':
                    $slot = Slot::find($model->slot_id);
                    if ($slot) {
                        $slot->status = 'booked';
                        $slot->save();
                    }
                    break;

                case 'completed':
                    if ($model->end == null) {
                        $slot = Slot::find($model->slot_id);
                        if ($slot) {
                            $model->transaction()->create([
                                'amount' => (double) $model->duration_number * (double) $model->parking->price_per_hour,
                                'status' => 'completed',
                                'company_id' => $model->company_id
                            ]);
                            $model->end = Carbon::now();
                            $model->save();

                            $slot->status = 'available';
                            $slot->save();
                        }
                    }
                    break;

                case 'cancelled':
                    $slot = Slot::find($model->slot_id);
                    if ($slot) {
                        $slot->status = 'available';
                        $slot->save();
                    }
                    break;

                default:
                    // Optional: Handle unknown statuses here
                    break;
            }

        });

        static::created(function ($model) {

            $barcodeDirectory = storage_path('app/barcodes');
            if (!file_exists($barcodeDirectory)) {
                mkdir($barcodeDirectory, 0755, true);
            }
            $code = $model->id;

            $code = QrCode::size(75)->generate($code)->toHtml();
            $model->code = $code;

            $model->save();

        });
    }

    public function getDurationAttribute()
    {
        if ($this->start == null) {
            return "";
        }
        $startDate = Carbon::parse($this->start); // Replace with your start datetime
        $endDate = $this->end ? Carbon::parse($this->end) : Carbon::parse(now()); // Replace with your end datetime

        // Calculate the difference in hours
        $durationInHours = $startDate->diffInHours($endDate);

        // You can also get the difference in minutes or other formats
        $durationInMinutes = $startDate->diffInMinutes($endDate);

        return $durationInHours >= 1 ? round($durationInHours, 2) . ' H' : round($durationInMinutes, 2) . ' Min';
    }

    public function getDurationNumberAttribute()
    {
        if ($this->start == null) {
            return "";
        }
        $startDate = Carbon::parse($this->start); // Replace with your start datetime
        $endDate = $this->end ? Carbon::parse($this->end) : Carbon::parse(now()); // Replace with your end datetime

        // Calculate the difference in hours
        $durationInHours = $startDate->diffInHours($endDate);

        return $durationInHours;
    }

    public function user()
    {
        if(auth()->user()->company_id){
            return $this->belongsTo(related: User::class)->where('company_id', auth()->user()->company_id);
        }
        return auth()->user()->company_num ? $this->belongsTo(User::class)->doesntHave('roles')->where('company_id', auth()->user()->company_num) : $this->belongsTo(User::class)->doesntHave('roles');

    }

    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    public function car()
    {
        if(auth()->user()->company_id){
            return $this->belongsTo(Car::class)->where('company_id', auth()->user()->company_id);
        }
        return auth()->user()->company_num ? $this->belongsTo(Car::class)->where('company_id', auth()->user()->company_num) : $this->belongsTo(Car::class);
    }

    public function parking()
    {
        if(auth()->user()->company_id){
            return $this->belongsTo(Parking::class)->where('company_id', auth()->user()->company_id);
        }
        
        return auth()->user()->company_num ? $this->belongsTo(Parking::class)->where('company_id', auth()->user()->company_num) : $this->belongsTo(Parking::class);

    }

    public function slot()
    {
        return $this->belongsTo(Slot::class);
    }

    public function company()
    {
        return $this->belongsTo(company::class);
    }


    public function transaction()
    {
        return $this->morphOne(Transaction::class, 'transactionable');
    }

    public function getCarBrandAttribute()
    {
        return $this->car->car_brand->name;
    }

    public function getCarModelAttribute()
    {
        return $this->car->car_model->name;
    }
    public function getCarColorAttribute()
    {
        return $this->car->color;
    }
    public function getUserPhoneAttribute()
    {
        return $this->user->phone;
    }

    public function getSupervisorAttribute()
    {
        return $this->parking->supervisor->name;
    }
}
