<?php

namespace Erenkucukersoftware\BrugsMigrationTool\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Erenkucukersoftware\BrugsMigrationTool\Models\ShopifyProductColor;

class ShopifyProductColorID extends Model
{
    use HasFactory;

    protected $connection = 'mysql2';
    protected $table = 'br_colors';
    protected $hidden = ['id', 'desription', 'date_created','date_modified','date_deleted'];
    public function productColor()
    {
        return $this->belongsTo(ShopifyProductColor::class);
    }
}
