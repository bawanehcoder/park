<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class company extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function parkings()
    {
        return $this->hasMany(Parking::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
    public function owner(){
        return $this->belongsTo(User::class,'owner_id')->whereHas('roles', function($query) {
            $query->where('name', 'owner');
        });
    }
}
