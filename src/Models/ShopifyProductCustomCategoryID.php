<?php

namespace Erenkucukersoftware\BrugsMigrationTool\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopifyProductCustomCategoryID extends Model
{
    use HasFactory;

    protected $connection = 'mysql2';
    protected $table = 'br_custom_categories';
    protected $hidden = ['date_created', 'date_modified', 'date_deleted','description'];

    public function productCustomCategory()
    {
        return $this->belongsTo(ShopifyProductCustomCategory::class);
    }
}
