<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Parking extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();
        static::created(function ($model) {
            if (auth()->user()->company_num) {
                $model->company_id = auth()->user()->company_num;
                $model->save();
            }
        });
    }

    public function slots()
    {
        return $this->hasMany(Slot::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function company()
    {
        return $this->belongsTo(company::class);
    }

    public function users()
    {
        return $this->hasMany(User::class)->where('company_id', $this->company_id)->doesntHave ('roles');
    }
    public function employees()
    {
        return $this->hasMany(User::class)->whereHas('roles', function ($query) {
            $query->where('name', 'driver');
        });
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }


    public function supervisor()
    {
        return $this->belongsTo(User::class, 'supervisor_id')->whereHas('roles', function ($query) {
            $query->where('name', 'supervisor');
        });
    }
}
