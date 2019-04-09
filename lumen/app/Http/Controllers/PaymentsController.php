<?php

namespace App\Http\Controllers;
use VeloPayments;
use GuzzleHttp;
use App\Payee;

class PaymentsController extends Controller
{
    /**
     * Create payment locally and then on velo platform
     *
     * @return Response
     */
    public function createPayment()
    {
        $config = VeloPayments\Client\Configuration::getDefaultConfiguration()->setAccessToken(env('VELO_API_ACCESSTOKEN'));
        return "createPayment";
    }

    /**
     * Cancel payment on velo platform
     *
     * @return Response
     */
    public function cancelPayment($payment_id)
    {
        $config = VeloPayments\Client\Configuration::getDefaultConfiguration()->setAccessToken(env('VELO_API_ACCESSTOKEN'));
        return "cancelPayment";
    }

    /**
     * Get payment on velo platform
     *
     * @return Response
     */
    public function getPayment($payment_id)
    {
        $config = VeloPayments\Client\Configuration::getDefaultConfiguration()->setAccessToken(env('VELO_API_ACCESSTOKEN'));
        return "getPayment";
    }
}
