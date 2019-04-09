<?php

namespace App\Http\Controllers;
use VeloPayments;
use GuzzleHttp;
use App\Payee;

class PayeesController extends Controller
{
    /**
     * Get list of payees on velo platform
     *
     * @return Response
     */
    public function listPayees()
    {
        $config = VeloPayments\Client\Configuration::getDefaultConfiguration()->setAccessToken(env('VELO_API_ACCESSTOKEN'));
        $apiInstance = new VeloPayments\Client\Api\PayeesApi(
            new GuzzleHttp\Client(),
            $config
        );
        $payor_id = env('VELO_API_PAYORID');

        try {
            $result = $apiInstance->listPayees($payor_id);
            $r = json_decode($result);
            unset($r->links);
            return response()->json((object) $r);
        } catch (Exception $e) {
            echo 'Exception when calling PayeesApi->listPayees: ', $e->getMessage(), PHP_EOL;
        }
        
        return "listPayees";
    }

    /**
     * Create payee locally and then on velo platform
     *
     * @return Response
     */
    public function createPayee()
    {
        $config = VeloPayments\Client\Configuration::getDefaultConfiguration()->setAccessToken(env('VELO_API_ACCESSTOKEN'));
        // $apiInstance = new VeloPayments\Client\Api\PayeeInvitationApi(
        //     new GuzzleHttp\Client(),
        //     $config
        // );
        // // save local db
        // $payee = new Payee();
        // $data = $request->only($payee->getFillable());
        // $payee->fill($data)->save();
        // // populate for velo
        // $create_payees_request = new VeloPayments\Client\Model\CreatePayeesRequest();
        // $create_payees_request->payor_id = env('VELO_API_PAYORID');
        // $payees = array();
        // array_push($payees, $payee->convertToVelo());
        // $create_payees_request->payees = payees;

        // try {
        //     $result = $apiInstance->v2CreatePayee($create_payees_request);
        //     print_r($result);
        // } catch (Exception $e) {
        //     echo 'Exception when calling PayeeBatchCreationApi->v2CreatePayee: ', $e->getMessage(), PHP_EOL;
        // }
        return "createPayee";
    }

    /**
     * Get payee locally and on velo platform
     *
     * @return Response
     */
    public function getPayee($payee_id)
    {
        $config = VeloPayments\Client\Configuration::getDefaultConfiguration()->setAccessToken(env('VELO_API_ACCESSTOKEN'));
        return "getPayee";
    }

    /**
     * Send invite to payee to update info on velo platform
     *
     * @return Response
     */
    public function veloPayeeUpdate($payee_id)
    {
        $config = VeloPayments\Client\Configuration::getDefaultConfiguration()->setAccessToken(env('VELO_API_ACCESSTOKEN'));
        return "veloPayeeUpdate";
    }

    /**
     * Get the update invite status on velo platform
     *
     * @return Response
     */
    public function veloPayeeUpdateStatus($payee_id)
    {
        $config = VeloPayments\Client\Configuration::getDefaultConfiguration()->setAccessToken(env('VELO_API_ACCESSTOKEN'));
        return "veloPayeeUpdateStatus";
    }
}
