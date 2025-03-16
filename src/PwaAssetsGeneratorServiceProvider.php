<?php

namespace Manhamprod\PwaAssetsGenerator;

use Illuminate\Support\ServiceProvider;

class PwaAssetsGeneratorServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/pwaassetsgenerator.php' => config_path('pwaassetsgenerator.php'),
        ], 'config');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

        $this->app->singleton('pwaassetsgenerator', function () {
            return new PwaAssetsGenerator();
        });

        $this->mergeConfigFrom(
            __DIR__.'/../config/pwaassetsgenerator.php', 'pwaassetsgenerator'
        );
    }
}
