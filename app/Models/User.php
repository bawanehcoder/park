<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravelcm\Subscriptions\Traits\HasPlanSubscriptions;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory;
    use Notifiable;
    use HasApiTokens;
    use HasRoles;
    use HasPlanSubscriptions;


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['name', 'email', 'password', 'phone'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = ['password', 'remember_token'];

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

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get all of the cars.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function cars()
    {
        return $this->hasMany(Car::class);
    }

    /**
     * Get all of the bookings.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Get the company.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company()
    { 
        return $this->com() ??  $this->own() ;
    }

    public function own()
    {
        return $this->hasOne(company::class, 'owner_id');
    }

    public function com()
    {
        return $this->belongsTo(company::class,'company_id') ?? null;
    }

    public function getCompanyNameAttribute(){
        if($this->com){
            return $this->com->name;
        }
        if($this->own){
            return $this->own->name;
        }
    }

    public function getCompanyNumAttribute(){
        if($this?->com){
            return $this->com->id;
        }
        if($this->own){
            return $this->own->id;
        }
    }

    public function getJobAttribute(){
        return $this->roles()->first()->name ?? 'user';
    }

    /**
     * Get the parking.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parking()
    {
        return $this->belongsTo(Parking::class);
    }

    /**
     * Get all of the subscriptions.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

}
