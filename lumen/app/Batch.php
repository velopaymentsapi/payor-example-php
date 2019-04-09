<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use VeloPayments;

class Batch extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'velo_payout_id', 'velo_status'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    public function convertToVelo()
    {   
        //
        return [];
    }
}