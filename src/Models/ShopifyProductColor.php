<?php

namespace Erenkucukersoftware\BrugsMigrationTool\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopifyProductColor extends Model
{
    use HasFactory;

    protected $connection = 'mysql2';
    protected $table = 'br_product_colors';
    protected $hidden = ['date_created', 'date_modified', 'date_deleted', 'sequence','color_id'];



    public function products()
    {
        return $this->belongsTo(ShopifyProduct::class)->withDefault();
        
    }
    public function color_name()
    {
        return $this->hasOne(ShopifyProductColorID::class, 'id', 'color_id')->withDefault();
    }
}
