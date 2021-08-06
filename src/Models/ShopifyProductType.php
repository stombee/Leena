<?php

namespace Erenkucukersoftware\BrugsMigrationTool\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopifyProductType extends Model
{
    use HasFactory;
    protected $connection = 'mysql2';
    protected $table = 'br_product_types';
    protected $hidden = ['date_created', 'date_modified', 'date_deleted','description'];

    public function product()
    {
        return $this->belongsTo(ShopifyProduct::class);
    }



}
