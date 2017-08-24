<?php

namespace Wsc\Wsc;

use Illuminate\Support\ServiceProvider;

use Wsc\Wsc\WscApi;

class WscServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(WscApi::class, function($app){
            return new WscApi(env('WSC_API_KEY'));
        });
    }
}
