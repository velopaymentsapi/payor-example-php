<?php
/**
 *
 * PHP version >= 7.0
 *
 * @category Console_Command
 * @package  App\Console\Commands
 */

namespace App\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use App\Events\RefreshVeloOAuth2Event;
use Datetime;
use Log;

/**
 * Class CheckVeloOAuth2ExpirationCommand
 *
 * @category Console_Command
 * @package  App\Console\Commands
 */
class CheckVeloOAuth2ExpirationCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = "velo:oauth2refresh";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Check if OAuth2 token is about to expire ... if so .. issue event to refresh it";


    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {
            $now = new Datetime();
            if ($now->format('U') >= (int)env('VELO_API_ACCESSTOKENEXPIRATION')){
                event(new RefreshVeloOAuth2Event);
                Log::info('expired fire refresh event');
            } else {
                Log::info('velo oauth2 token NOT expired');
            }
        } catch (Exception $e) {
            $this->error("An error occurred");
        }
    }
}