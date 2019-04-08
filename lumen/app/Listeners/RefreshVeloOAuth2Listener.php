<?php

namespace App\Listeners;

use App\Events\RefreshVeloOAuth2Event;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use GuzzleHttp;
use Log;
use Datetime;

class RefreshVeloOAuth2Listener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  RefreshVeloOAuth2Event  $event
     * @return void
     */
    public function handle(RefreshVeloOAuth2Event $event)
    {
        // call velo api to get new token
        $client = new GuzzleHttp\Client();
        $response = $client->post(env('VELO_BASE_URL').'/v1/authenticate?grant_type=client_credentials', [
            'headers' => [
                'Content-Type' => 'application/json', 
                'Authorization' => 'Basic '. base64_encode(env('VELO_API_APIKEY').':'.env('VELO_API_APISECRET'))
            ],
            'body' => json_encode([])
        ]);
        if ($response->getStatusCode() == 200) {
            $j = json_decode($response->getBody());
            $new_access_token = $j->access_token;
            $new_expires_in = $j->expires_in;
            $now = new Datetime();
            $new_expiration = ($new_expires_in + $now->format('U')) - 300;
            Log::info('new velo oauth2 token expires at: '. (string)$new_expiration);
        } else {
            die();
        }
        
        $path = base_path('.env');
        if (file_exists($path)) {
            // ... and update the .env file
            file_put_contents($path, str_replace(
                'VELO_API_ACCESSTOKEN='.env('VELO_API_ACCESSTOKEN'), 'VELO_API_ACCESSTOKEN='.$new_access_token, file_get_contents($path)
            ));
            file_put_contents($path, str_replace(
                'VELO_API_ACCESSTOKENEXPIRATION='.env('VELO_API_ACCESSTOKENEXPIRATION'), 'VELO_API_ACCESSTOKENEXPIRATION='.$new_expiration, file_get_contents($path)
            ));
        }
    }
}
