<?php

namespace Erenkucukersoftware\BrugsMigrationTool\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopifyProductCustomCategory extends Model
{
    use HasFactory;


    protected $connection = 'mysql2';
    protected $table = 'br_product_custom_categories';
    protected $hidden = ['date_created','date_modified', 'date_deleted'];

    public function products()
    {
        return $this->belongsTo(ShopifyProduct::class)->withDefault();
    }

    public function custom_category_name()
    {
        return $this->hasOne(ShopifyProductCustomCategoryID::class, 'id', 'custom_category_id')->withDefault();

    }

}
