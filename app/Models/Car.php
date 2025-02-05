<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Car extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [];



    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function ($model) {
            if (auth()->user()->company_num) {
                $model->company_id = auth()->user()->company_num;
                $model->save();
            }
        });

        static::creating(function ($car) {
            $barcodeDirectory = storage_path('app/barcodes');
            if (!file_exists($barcodeDirectory)) {
                mkdir($barcodeDirectory, 0755, true);
            }
            $code = rand(9999, 999999999);

            $code = QrCode::size(75)
                ->generate($code)
                ->toHtml();
            $car->code = $code;

           
        });
    }

    public function car_brand(){
        return $this->belongsTo(CarBrand::class, 'brand');
    }

    public function car_model(){
        return $this->belongsTo(CarModel::class, 'model');
    }
}
