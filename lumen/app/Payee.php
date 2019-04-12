<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use VeloPayments;
use Ramsey\Uuid\Uuid;

class Payee extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';
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

    public static function boot()
    {
        parent::boot();

        self::creating(function($model){
            $uuid4 = Uuid::uuid4();
            $model->id = $uuid4->toString();
            $model->velo_invite_status = "ready";
            // mask the planet
            $model->account_number = substr($model->account_number, -4);
            $model->national_id = substr($model->national_id, -4);
            $bday = substr($model->date_of_birth, 4);
            $model->date_of_birth = '1900' . $bday;
            // $model->iban = 
        });
    }
    
    public function validateCreate()
    {
        $data = $this->toArray();
        $rules = [
            'email' => 'required|email', 
            'first_name' => 'required|string', 
            'last_name' => 'required|string', 
            'address1' => 'required|string',
            'city' => 'required|string',
            'state' => 'required|string',
            'postal_code' => 'required|string',
            'country_code' => 'required|string',
            'phone_number' => 'required|string',
            'date_of_birth' => 'required|string',
            'national_id' => 'required|string',
            'bank_name' => 'required|string'
        ];
        $validator = Validator::make($data, $rules);
        if ($validator->fails()) {
            return $validator->errors()->all();
        }
        return true;
    }

    public function convertToVelo($data)
    {   
        $address = new VeloPayments\Client\Model\Address();
        $address->setLine1( $this->address1 );
        // $address->setLine2( $this->address2 );
        $address->setCity( $this->city );
        $address->setCountry( $this->country_code );
        $address->setZipOrPostcode( $this->postal_code );
        //
        $paymentChannel = new VeloPayments\Client\Model\CreatePaymentChannel();
        $paymentChannel->setCountryCode( $this->country_code );
        $paymentChannel->setCurrency(  "USD" );
        $paymentChannel->setAccountName( $this->bank_name );
        $paymentChannel->setRoutingNumber( $this->routing_number );
        $paymentChannel->setAccountNumber( $data['account_number'] );
        //
        $challenge = new VeloPayments\Client\Model\Challenge();
        $challenge->setDescription( "first nine numbers" );
        $challenge->setValue( "123456789" );
        //
        $individual = new VeloPayments\Client\Model\Individual();
        $individual->setDateOfBirth( $data['date_of_birth'] );
        $individual->setNationalIdentification( $data['national_id'] );
        $individual_name = new \VeloPayments\Client\Model\IndividualName();
        $individual_name->setFirstName( $this->first_name );
        $individual_name->setLastName( $this->last_name );
        $individual->setName( $individual_name );
        //
        $payee = new VeloPayments\Client\Model\CreatePayee(); 
        $payee->setEmail( $this->email );
        $payee->setRemoteId( $this->id );
        $payee->setType( "Individual" );
        $payee->setDisplayName( $this->first_name . ' ' . $this->last_name );
        $payee->setCountry( $this->country_code );
        $payee->setAddress( $address );
        $payee->setPaymentChannel( $paymentChannel );
        $payee->setChallenge( $challenge );
        $payee->setLanguage( "EN" );
        $payee->setCellphoneNumber( $this->phone_number );
        $payee->setIndividual( $individual );

        return $payee;
    }
}