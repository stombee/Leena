<?php

namespace Erenkucukersoftware\BrugsMigrationTool\Providers;

use Illuminate\Support\ServiceProvider;
use Erenkucukersoftware\BrugsMigrationTool\Providers\BrugsMigrationToolEventServiceProvider;


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
        $this->mergeConfigFrom(__DIR__ . '/../config/logging.php', 'logging.channels');
        $this->app->register(BrugsMigrationToolEventServiceProvider::class);
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
        $this->publishConfig();

    }
    private function publishConfig()
    {
        $path = $this->getConfigPath();
        $this->publishes([$path => config_path('product-tool.php')], 'config');
    }

    private function getConfigPath()
    {
        return __DIR__ . '/../config/product-tool.php';
    }

}
