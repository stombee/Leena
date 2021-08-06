<?php

namespace Erenkucukersoftware\BrugsMigrationTool\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopifyProductShopifyData extends Model
{
    use HasFactory;

    protected $connection = 'mysql2';
    protected $table = 'shopify_data';
    protected $hidden = ['product_inserted', 'inventory_updated', 'inventory_update_date', 'status','parent_id'];

    public function products()
    {
        return $this->belongsTo(ShopifyProduct::class)->withDefault();
    }
    
}
