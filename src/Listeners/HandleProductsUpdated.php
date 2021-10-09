<?php

namespace Erenkucukersoftware\BrugsMigrationTool\Listeners;

use Erenkucukersoftware\BrugsMigrationTool\Events\{ProductsCreated, ProductsUpdated};
use Erenkucukersoftware\BrugsMigrationTool\Models\ShopifyProductShopifyData;
use Erenkucukersoftware\BrugsMigrationTool\Facades\Shopify\ShopifyFlowFacade;
use Illuminate\Support\Facades\{Log, Http};
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Spatie\Url\Url;
use Carbon\Carbon;


class HandleProductsUpdated
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
     * @param  ProductsUpdated  $event
     * @return void
     */
    public function handle(ProductsUpdated $event)
    {
        Log::debug("Product updated Event Worked.");
        $timestamp = ($event->timestamp)->toIso8601String();
        Log::debug(['timestamp',$timestamp]);
        $response = Http::get('https://doofinder.boutiquerugs.com/shopifytable/upload_db.php?operation=update&timestamp='.$timestamp)->getBody()->getContents();
        
        Log::debug(['response_shopify_table', $response]);
    }


}
