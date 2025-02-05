<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class Slot extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();

        static::created(function ($car) {

            $barcodeDirectory = storage_path('app/barcodes');
            if (!file_exists($barcodeDirectory)) {
                mkdir($barcodeDirectory, 0755, true);
            }
            $code = $car->id;

            $code =  QrCode::size(75)->generate($code)->toHtml();
            $car->code = $code;

            $car->save();
        });
    }

    public function parking()
    {
        return $this->belongsTo(Parking::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
