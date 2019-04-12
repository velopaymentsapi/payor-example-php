<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use VeloPayments;
use Ramsey\Uuid\Uuid;

class Batch extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';
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

    public static function boot()
    {
        parent::boot();

        self::creating(function($model){
            $uuid4 = Uuid::uuid4();
            $model->id = $uuid4->toString();
            $model->velo_status = "ready";
        });
    }
}