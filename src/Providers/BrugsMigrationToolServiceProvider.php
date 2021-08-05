<?php

namespace Erenkucukersoftware\BrugsMigrationTool\Providers;

use Illuminate\Support\ServiceProvider;



class BrugsMigrationToolServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->make('Erenkucukersoftware\BrugsMigrationTool\Controllers\BrugsMigrationToolController');

    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        include dirname(__DIR__) . '/Routes/routes.php';
    }
}
