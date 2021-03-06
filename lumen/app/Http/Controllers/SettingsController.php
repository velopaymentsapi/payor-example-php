<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use VeloPayments;
use GuzzleHttp;

class SettingsController extends Controller
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
     * Retrieve our payee information on the velo platform
     *
     * @return Response
     */
    public function showInfo()
    {
        $config = VeloPayments\Client\Configuration::getDefaultConfiguration()->setAccessToken(env('VELO_API_ACCESSTOKEN'));
        $apiInstance = new VeloPayments\Client\Api\PayorsApi(
            new GuzzleHttp\Client(),
            $config
        );
        $payor_id = env('VELO_API_PAYORID');
        
        try {
            $result = $apiInstance->getPayorByIdV2($payor_id);
            $r = json_decode($result);
            return response()->json((object) $r);
        } catch (Exception $e) {
            echo 'Exception when calling CountriesApi->listSupportedCountries: ', $e->getMessage(), PHP_EOL;
        }
    }

    /**
     * Retrieve the list of source accounts on the velo platform
     *
     * @return Response
     */
    public function listAccounts()
    {
        $config = VeloPayments\Client\Configuration::getDefaultConfiguration()->setAccessToken(env('VELO_API_ACCESSTOKEN'));
        $apiInstance = new VeloPayments\Client\Api\FundingManagerApi(
            new GuzzleHttp\Client(),
            $config
        );
        $payor_id = env('VELO_API_PAYORID');
        
        try {
            $result = $apiInstance->getSourceAccountsV2(null, null, $payor_id);
            $r = json_decode($result);
            return response()->json((object) ['accounts' => $r->content]);
        } catch (Exception $e) {
            echo 'Exception when calling CountriesApi->listSupportedCountries: ', $e->getMessage(), PHP_EOL;
        }
    }

    /**
     * Fund a source account on the velo platform
     *
     * @return Response
     */
    public function fundAccount()
    {
        $config = VeloPayments\Client\Configuration::getDefaultConfiguration()->setAccessToken(env('VELO_API_ACCESSTOKEN'));
        $apiInstance = new VeloPayments\Client\Api\FundingManagerApi(
            new GuzzleHttp\Client(),
            $config
        );
        
        $source_account_id = $this->request->input('source_account');
        $funding_request = new \VeloPayments\Client\Model\FundingRequestV1();
        $funding_request->setAmount( (int)$this->request->input('amount') );
        
        try {
            $apiInstance->createAchFundingRequest($source_account_id, $funding_request);
            return response()->json((object) []);
        } catch (Exception $e) {
            echo 'Exception when calling FundingManagerApi->createFundingRequest: ', $e->getMessage(), PHP_EOL;
        }
    }

    /**
     * Retrieve the list of available countries
     *
     * @return Response
     */
    public function listCountries()
    {
        $config = VeloPayments\Client\Configuration::getDefaultConfiguration()->setAccessToken(env('VELO_API_ACCESSTOKEN'));
        $apiInstance = new VeloPayments\Client\Api\CountriesApi(
            new GuzzleHttp\Client(),
            $config
        );

        try {
            $result = $apiInstance->listSupportedCountries();
            $r = json_decode($result);
            return response()->json((object) $r);
        } catch (Exception $e) {
            echo 'Exception when calling CountriesApi->listSupportedCountries: ', $e->getMessage(), PHP_EOL;
        }
    }

    /**
     * Retrieve the list of available currencies
     *
     * @return Response
     */
    public function listCurrencies()
    {
        $config = VeloPayments\Client\Configuration::getDefaultConfiguration()->setAccessToken(env('VELO_API_ACCESSTOKEN'));
        $apiInstance = new VeloPayments\Client\Api\CurrenciesApi(
            new GuzzleHttp\Client(),
            $config
        );

        try {
            $result = $apiInstance->listSupportedCurrencies();
            $r = json_decode($result);
            return response()->json((object) $r);
        } catch (Exception $e) {
            echo 'Exception when calling CountriesApi->listSupportedCurrencies: ', $e->getMessage(), PHP_EOL;
        }
    }
}
