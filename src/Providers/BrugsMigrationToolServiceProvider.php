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
        $this->app->make('Erenkucukersoftware\BrugsMigrationTool\Models\ShopifyProduct');
        $this->app->make('Erenkucukersoftware\BrugsMigrationTool\Models\ShopifyProductColor');
        $this->app->make('Erenkucukersoftware\BrugsMigrationTool\Models\ShopifyProductColorID');
        $this->app->make('Erenkucukersoftware\BrugsMigrationTool\Models\ShopifyProductCustomCategory');
        $this->app->make('Erenkucukersoftware\BrugsMigrationTool\Models\ShopifyProductCustomCategoryID');
        $this->app->make('Erenkucukersoftware\BrugsMigrationTool\Models\ShopifyProductShopifyData');
        $this->app->make('Erenkucukersoftware\BrugsMigrationTool\Models\ShopifyProductShopifyDataDev');
        $this->app->make('Erenkucukersoftware\BrugsMigrationTool\Models\ShopifyProductSubType');
        $this->app->make('Erenkucukersoftware\BrugsMigrationTool\Models\ShopifyProductType');
        $this->app->make('Erenkucukersoftware\BrugsMigrationTool\Models\ShopifyProductVariant');
        $this->app->make('Erenkucukersoftware\BrugsMigrationTool\Models\ShopifyProductVariantPrice');
        $this->app->make('Erenkucukersoftware\BrugsMigrationTool\Models\ShopifyProductVariantShipping');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        include dirname(__DIR__) . '/Routes/routes.php';
        include dirname(__DIR__) . '/helpers.php';
    }
}
