<?php

namespace Erenkucukersoftware\BrugsMigrationTool\Listeners;

use Erenkucukersoftware\BrugsMigrationTool\Events\{ProductsCreated, ProductsUpdated};
use Erenkucukersoftware\BrugsMigrationTool\Models\ShopifyProductShopifyData;
use Erenkucukersoftware\BrugsMigrationTool\Facades\Shopify\ShopifyFlowFacade;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\{Log, Http};
use Illuminate\Queue\InteractsWithQueue;
use Spatie\Url\Url;
use Carbon\Carbon;

class HandleProductsCreated
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  ProductsCreated  $event
     * @return void
     */
    public function handle(ProductsCreated $event)
    {
        Log::debug("Product created Event Worked.");
        $timestamp = ($event->timestamp)->toIso8601String();
        $response = Http::get('https://doofinder.boutiquerugs.com/shopifytable/upload_db.php?operation=create&timestamp='.$timestamp)->getBody()->getContents();
        Log::debug(['response_shopify_table', $response]);
    }


}
