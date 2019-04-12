<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use VeloPayments;
use GuzzleHttp;
use App\Batch;
use App\Payment;

class PaymentsController extends Controller
{
    /**
     * The request instance.
     *
     * @var \Illuminate\Http\Request
     */
    private $request;

    /**
     * Create a new controller instance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    public function __construct(Request $request) {
        $this->request = $request;
    }

    /**
     * Create payment locally and then on velo platform
     *
     * @return Response
     */
    public function createPayment()
    {
        $config = VeloPayments\Client\Configuration::getDefaultConfiguration()->setAccessToken(env('VELO_API_ACCESSTOKEN'));
        $apiInstance = new VeloPayments\Client\Api\SubmitPayoutApi(
            new GuzzleHttp\Client(),
            $config
        );

        // save local db
        $batch = new Batch();
        $batch->name = "batch-".time();
        $batch->save();
        $payment = new Payment();
        $payment->batch_id = $batch['id'];
        $payment->payee_id = $this->request->input('payee_id');
        $payment->memo = $this->request->input('memo');
        $payment->amount = $this->request->input('amount');
        $payment->currency = $this->request->input('currency');
        $payment->velo_source_account = $this->request->input('source_account_name');
        $valid = $payment->validateCreate();
        if (gettype($valid) == "array") {
            return response()->json(['error' => [ 'code' => 422, 'message' => $valid ]]);
        }
        $payment->save();

        $create_payout_request = new \VeloPayments\Client\Model\CreatePayoutRequest();
        $create_payout_request->setPayorId(env('VELO_API_PAYORID'));
        $create_payout_request->setPayoutMemo("batch-".time());
        
        $payments = array();
        array_push($payments, $payment->convertToVelo());
        $create_payout_request->setPayments($payments);

        try {
            $apiInstance->submitPayout($create_payout_request);
            return response()->json();
        } catch (Exception $e) {
            echo 'Exception when calling SubmitPayoutApi->submitPayout: ', $e->getMessage(), PHP_EOL;
        }
    }

    /**
     * Verify payment on velo platform
     *
     * @param  string  $payment_id
     * @return Response
     */
    public function verifyPayment($payment_id)
    {
        $config = VeloPayments\Client\Configuration::getDefaultConfiguration()->setAccessToken(env('VELO_API_ACCESSTOKEN'));
        $apiInstance = new VeloPayments\Client\Api\InstructPayoutApi(
            new GuzzleHttp\Client(),
            $config
        );
        $payout_id = $payment_id;
        
        try {
            $apiInstance->v3PayoutsPayoutIdPost($payout_id);
        } catch (Exception $e) {
            echo 'Exception when calling InstructPayoutApi->v3PayoutsPayoutIdPost: ', $e->getMessage(), PHP_EOL;
        }
    }

    /**
     * Cancel payment on velo platform
     *
     * @param  string  $payment_id
     * @return Response
     */
    public function cancelPayment($payment_id)
    {
        $config = VeloPayments\Client\Configuration::getDefaultConfiguration()->setAccessToken(env('VELO_API_ACCESSTOKEN'));
        $apiInstance = new VeloPayments\Client\Api\WithdrawPayoutApi(
            new GuzzleHttp\Client(),
            $config
        );
        $payout_id = $payment_id;
        
        try {
            $apiInstance->v3PayoutsPayoutIdDelete($payout_id);
        } catch (Exception $e) {
            echo 'Exception when calling WithdrawPayoutApi->v3PayoutsPayoutIdDelete: ', $e->getMessage(), PHP_EOL;
        }
        return "cancelPayment";
    }

    /**
     * Get payment on velo platform
     *
     * @param  string  $payment_id
     * @return Response
     */
    public function getPayment($payment_id)
    {
        $config = VeloPayments\Client\Configuration::getDefaultConfiguration()->setAccessToken(env('VELO_API_ACCESSTOKEN'));
        $apiInstance = new VeloPayments\Client\Api\GetPayoutApi(
            new GuzzleHttp\Client(),
            $config
        );
        $payout_id = $payment_id;
        
        try {
            $result = $apiInstance->v3PayoutsPayoutIdGet($payout_id);
            $r = json_decode($result);
            return response()->json((object) $r);
        } catch (Exception $e) {
            echo 'Exception when calling GetPayoutApi->v3PayoutsPayoutIdGet: ', $e->getMessage(), PHP_EOL;
        }
    }
}
