<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use VeloPayments;

class Payee extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email', 'first_name', 'last_name', 'address1', 'address2', 'city', 'state', 'postal_code', 'country_code', 'phone_number', 'date_of_birth', 'national_id', 'bank_name', 'routing_number', 'account_number', 'iban', 'velo_id', 'velo_invite_status'
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
        $address = new VeloPayments\Client\Model\Address();
        $address->line1 = "";
        $address->city = "";
        $address->country = "";
        //
        $paymentChannel = new VeloPayments\Client\Model\CreatePaymentChannel();
        $paymentChannel->country_code = "";
        $paymentChannel->currency = "";
        $paymentChannel->account_name = "";
        $paymentChannel->routing_number = "";
        $paymentChannel->account_number = "";
        //
        $challenge = new VeloPayments\Client\Model\Challenge();
        $challenge->description = "";
        $challenge->value = "";
        //
        $payeeType = new VeloPayments\Client\Model\PayeeType();
        //
        $individual = new VeloPayments\Client\Model\Individual();
        $individual->date_of_birth = "";
        $individual_name = new \VeloPayments\Client\Model\IndividualName();
        $individual->first_name = "";
        $individual->last_name = "";
        $individual->name = $individual_name;
        //
        //
        $payee = new VeloPayments\Client\Model\CreatePayee(); 
        $payee->payee_id = "";
        $payee->email = "";
        $payee->remote_id = "";
        $payee->type = "";
        $payee->display_name = "";
        $payee->country = "";
        $payee->address = $address;
        $payee->payment_channel = $paymentChannel;
        $payee->challenge = $challenge;
        $payee->cellphone_number = "";
        $payee->payee_type = $payeeType;
        $payee->individual = $individual;

        return payee;
    }
}