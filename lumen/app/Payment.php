<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use VeloPayments;

class Payment extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'batch_id', 'payee_id', 'amount', 'currency', 'velo_source_account', 'velo_status'
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