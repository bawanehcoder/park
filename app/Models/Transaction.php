<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{

    protected $fillable = ['amount', 'transactionable_id', 'transactionable_type','status','company_id'];
    public function transactionable()
    {
        return $this->morphTo();
    }

    public function company(){
        return $this->belongsTo(company::class);
    }
}
