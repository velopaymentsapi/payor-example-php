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

$router->get('/status', function () use ($router) {
    return response()->json('status page');
});

$router->post('auth/login', ['uses' => 'AuthController@login']);

$router->group(['prefix' => 'settings', 'middleware' => 'auth'], function() use ($router)
{
    $router->get('', ['uses' => 'SettingsController@showInfo']);
    // $router->get('accounts', ['uses' => 'SettingsController@showAccounts']);
    $router->get('countries', ['uses' => 'SettingsController@showCountries']);
    $router->get('currencies', ['uses' => 'SettingsController@showCurrencies']);
});