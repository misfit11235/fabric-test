<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Helpers\OMDB;

class OMDBServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('omdb', function() {
            return new OMDB();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
