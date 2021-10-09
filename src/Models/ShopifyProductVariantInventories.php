<?php

namespace Erenkucukersoftware\BrugsMigrationTool\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Erenkucukersoftware\BrugsMigrationTool\Models\ShopifyProductVariant;


class ShopifyProductVariantInventories extends Model
{
    use HasFactory;
    protected $connection = 'mysql2';
    protected $table = 'br_product_variant_inventories';
    protected $hidden = ['date_created', 'date_modified', 'date_deleted'];


    public function productVariant()
    {
        return $this->belongsTo(ShopifyProductVariant::class);
    }

}
