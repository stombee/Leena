<?php

namespace Erenkucukersoftware\BrugsMigrationTool\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Erenkucukersoftware\BrugsMigrationTool\Models\ShopifyProduct;


class ShopifyProductVariant extends Model
{
    use HasFactory;

    protected $connection = 'mysql2';
    protected $table = 'br_product_variants';
    protected $hidden = ['date_created', 'date_modified', 'date_deleted'];
    public function products()
    {
        return $this->belongsTo(ShopifyProduct::class)->withDefault();
    }

    public function shippings()
    {
        return $this->hasOne(ShopifyProductVariantShipping::class, 'id','shipping_id')->withDefault();

    }

    public function price()
    {
        return $this->hasOne(ShopifyProductVariantPrice::class, 'real_sku', 'real_sku')->withDefault();
    }

    public function images()
    {
        return $this->hasMany(Image::class, 'real_sku', 'real_sku');
    }

   
}
