<?php

namespace HelgeSverre\Brreg\Providers;

use GuzzleHttp\Client;
use HelgeSverre\Brreg\Services\BrregDataService;
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
        $this->app->singleton(BrregDataService::class, function () {
            return new BrregDataService(new Manager(), new Client());
        });
    }
}
