<?php

namespace Erenkucukersoftware\BrugsMigrationTool\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopifyProductShopifyDataDev extends Model
{
    use HasFactory;

    protected $connection = 'mysql2';
    protected $table = 'shopify_data_dev';
    protected $hidden = ['data', 'variants_data', 'product_inserted', 'inventory_updated', 'inventory_update_date', 'status', 'parent_id'];

    public function products()
    {
        return $this->belongsTo(ShopifyProduct::class)->withDefault();
    }

}
