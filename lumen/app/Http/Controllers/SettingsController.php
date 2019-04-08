<?php

namespace App\Http\Controllers;
use VeloPayments;
use GuzzleHttp;

class SettingsController extends Controller
{
    /**
     * Retrieve our information on the velo platform
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
            $result = $apiInstance->getPayorById($payor_id);
            return $result;
        } catch (Exception $e) {
            echo 'Exception when calling CountriesApi->listSupportedCountries: ', $e->getMessage(), PHP_EOL;
        }
    }

    /**
     * Retrieve the list of source accounts on the velo platform
     *
     * @return Response
     */
    public function showAccounts()
    {
        $config = VeloPayments\Client\Configuration::getDefaultConfiguration()->setAccessToken(env('VELO_API_ACCESSTOKEN'));
        $apiInstance = new VeloPayments\Client\Api\PayorInformationApi(
            new GuzzleHttp\Client(),
            $config
        );
        $payor_id = env('VELO_API_PAYORID');
        
        try {
            $result = $apiInstance->getSourceAccounts(null, $payor_id);
            return $result;
        } catch (Exception $e) {
            echo 'Exception when calling CountriesApi->listSupportedCountries: ', $e->getMessage(), PHP_EOL;
        }
    }

    /**
     * Retrieve the list of available countries
     *
     * @return Response
     */
    public function showCountries()
    {
        $config = VeloPayments\Client\Configuration::getDefaultConfiguration()->setAccessToken(env('VELO_API_ACCESSTOKEN'));
        $apiInstance = new VeloPayments\Client\Api\CountriesApi(
            new GuzzleHttp\Client(),
            $config
        );

        try {
            $result = $apiInstance->listSupportedCountries();
            return $result;
        } catch (Exception $e) {
            echo 'Exception when calling CountriesApi->listSupportedCountries: ', $e->getMessage(), PHP_EOL;
        }
    }

    /**
     * Retrieve the list of available currencies
     *
     * @return Response
     */
    public function showCurrencies()
    {
        $config = VeloPayments\Client\Configuration::getDefaultConfiguration()->setAccessToken(env('VELO_API_ACCESSTOKEN'));
        $apiInstance = new VeloPayments\Client\Api\CurrenciesApi(
            new GuzzleHttp\Client(),
            $config
        );

        try {
            $result = $apiInstance->listSupportedCurrencies();
            return $result;
        } catch (Exception $e) {
            echo 'Exception when calling CountriesApi->listSupportedCurrencies: ', $e->getMessage(), PHP_EOL;
        }
    }
}
