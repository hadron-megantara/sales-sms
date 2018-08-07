<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;
use GuzzleHttp\Client;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        if(isset($_COOKIE[env('TOKEN_COOKIE', 'phpsess')])){
            View::share('menu', $this->loadMenuData($_COOKIE[env('TOKEN_COOKIE', 'phpsess')]));
        }
    }

    public function loadMenuData($cookie){
        $menu = Cache::remember('menu', 60, function () {
            $token = $_COOKIE[env('TOKEN_COOKIE', 'phpsess')];

            $client = new Client;
            $response = $client->request('GET', env('API_URL', 'http://sales-system.local/api/').env('API_VERSION', 'v1').'/config/menu',[
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                    'Accept'        => 'application/json',
                ],
            ]);

            $response = json_decode($response->getBody()->getContents());

            if($response->success){
                return $response->data->detail;
            }
        });

        return $menu;
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
