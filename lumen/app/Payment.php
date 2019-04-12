<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use VeloPayments;
use Ramsey\Uuid\Uuid;

class Payment extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'batch_id', 'payee_id', 'memo', 'amount', 'currency', 'velo_source_account', 'velo_status'
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

    public function validateCreate()
    {
        $data = $this->toArray();
        $rules = [
            'payee_id' => 'required|string',
            'memo' => 'required|string',
            'amount' => 'required|integer',
            'currency' => 'required|string'
        ];
        $validator = Validator::make($data, $rules);
        if ($validator->fails()) {
            return $validator->errors()->all();
        }
        return true;
    }

    public function convertToVelo()
    {   
        //
        $instruction = new \VeloPayments\Client\Model\PaymentInstruction();
        $instruction->setRemoteId( $this->payee_id );
        $instruction->setCurrency( $this->currency );
        $instruction->setAmount( $this->amount );
        $instruction->setPaymentMemo( $this->memo );
        $instruction->setSourceAccountName( $this->velo_source_account );
        $instruction->setPayorPaymentId( $this->id );

        return $instruction;
    }
}