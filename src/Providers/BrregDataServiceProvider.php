<?php

namespace HelgeSverre\Brreg;

use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;
use League\Fractal\Manager;

class BrregDataServiceProvider extends ServiceProvider
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
        $this->app->singleton(BrregService::class, function () {
            return new BrregService(new Manager(), new Client());
        });
    }
}
