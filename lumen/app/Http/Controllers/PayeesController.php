<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use VeloPayments;
use GuzzleHttp;
use App\Payee;

class PayeesController extends Controller
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
        $ofac_status = null;
        $onboarded_status = null;
        $email = null;
        $display_name = null;
        $remote_id = null;
        $payee_type = null;
        $payee_country = null;
        $page = 1;
        $page_size = 100;
        $sort = 'displayName:desc';

        try {
            $result = $apiInstance->listPayees($payor_id, $ofac_status, $onboarded_status, $email, $display_name, $remote_id, $payee_type, $payee_country, $page, $page_size, $sort);
            $r = json_decode($result);
            unset($r->links);
            $r->payees = $r->content;
            unset($r->content);
            return response()->json((object) $r);
        } catch (Exception $e) {
            echo 'Exception when calling PayeesApi->listPayees: ', $e->getMessage(), PHP_EOL;
        }
    }

    /**
     * Create payee locally and then on velo platform
     *
     * @return Response
     */
    public function createPayee()
    {
        $config = VeloPayments\Client\Configuration::getDefaultConfiguration()->setAccessToken(env('VELO_API_ACCESSTOKEN'));
        $apiInstance = new VeloPayments\Client\Api\PayeeInvitationApi(
            new GuzzleHttp\Client(),
            $config
        );
        // save local db
        $payee = new Payee();
        $data = $this->request->json()->all();
        $payee->fill($data);
        $valid = $payee->validateCreate();
        if (gettype($valid) == "array") {
            return response()->json(['error' => [ 'code' => 422, 'message' => $valid ]]);
        }
        $payee->save();
        
        // populate for velo
        $create_payees_request = new VeloPayments\Client\Model\CreatePayeesRequest();
        $create_payees_request->setPayorId( env('VELO_API_PAYORID') );
        $payees = array();
        array_push($payees, $payee->convertToVelo($data));
        $create_payees_request->setPayees( $payees );
        
        try {
            $result = $apiInstance->v2CreatePayee($create_payees_request);
            return response()->json();
        } catch (Exception $e) {
            var_dump(json_encode($d));die();
            echo 'Exception when calling PayeeBatchCreationApi->v2CreatePayee: ', $e->getMessage(), PHP_EOL;
        }
    }

    /**
     * Get payee locally and on velo platform
     *
     * @param  string  $payee_id
     * @return Response
     */
    public function getPayee($payee_id)
    {
        $config = VeloPayments\Client\Configuration::getDefaultConfiguration()->setAccessToken(env('VELO_API_ACCESSTOKEN'));
        $apiInstance = new VeloPayments\Client\Api\PayeesApi(
            new GuzzleHttp\Client(),
            $config
        );
        $payee_id = $payee_id;
        $sensitive = True;
        
        try {
            $result = $apiInstance->getPayeeById($payee_id, $sensitive);
            $r = json_decode($result);
            return response()->json((object) $r);
        } catch (Exception $e) {
            echo 'Exception when calling PayeesApi->getPayeeById: ', $e->getMessage(), PHP_EOL;
        }
    }

    /**
     * Send invite to payee to update info on velo platform
     *
     * @param  string  $payee_id
     * @return Response
     */
    public function veloPayeeUpdate($payee_id)
    {
        $config = VeloPayments\Client\Configuration::getDefaultConfiguration()->setAccessToken(env('VELO_API_ACCESSTOKEN'));
        $apiInstance = new VeloPayments\Client\Api\PayeeInvitationApi(
            new GuzzleHttp\Client(),
            $config
        );
        $payee_id = $payee_id;
        $invite_payee_request = new VeloPayments\Client\Model\InvitePayeeRequest();
        $invite_payee_request->setPayorId( env('VELO_API_PAYORID') );
        
        try {
            $result = $apiInstance->resendPayeeInvite($payee_id, $invite_payee_request);
            return response()->json();
        } catch (Exception $e) {
            echo 'Exception when calling PayeeInvitationApi->resendPayeeInvite: ', $e->getMessage(), PHP_EOL;
        }
    }
}
