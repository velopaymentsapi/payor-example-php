<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return response()->json('payor api index');
});

$router->post('auth/login', ['uses' => 'AuthController@login']);

$router->group(['prefix' => 'settings', 'middleware' => 'auth'], function() use ($router)
{
    $router->get('', ['uses' => 'SettingsController@showInfo']);
    $router->get('accounts', ['uses' => 'SettingsController@listAccounts']);
    $router->post('fundings', ['uses' => 'SettingsController@fundAccount']);
    $router->get('countries', ['uses' => 'SettingsController@listCountries']);
    $router->get('currencies', ['uses' => 'SettingsController@listCurrencies']);
});

$router->group(['prefix' => 'payees', 'middleware' => 'auth'], function() use ($router)
{
    $router->get('', ['uses' => 'PayeesController@listPayees']);
    $router->post('', ['uses' => 'PayeesController@createPayee']);
    $router->get('{payee_id}', ['uses' => 'PayeesController@getPayee']);
    $router->post('{payee_id}/invite', ['uses' => 'PayeesController@veloPayeeUpdate']);
});

$router->group(['prefix' => 'payments', 'middleware' => 'auth'], function() use ($router)
{
    $router->post('', ['uses' => 'PaymentsController@createPayment']);
    $router->delete('{payment_id}', ['uses' => 'PaymentsController@cancelPayment']);
    $router->put('{payment_id}', ['uses' => 'PaymentsController@verifyPayment']);
    $router->get('{payment_id}', ['uses' => 'PaymentsController@getPayment']);
});