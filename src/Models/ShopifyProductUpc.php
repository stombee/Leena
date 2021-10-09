<?php

namespace Erenkucukersoftware\BrugsMigrationTool\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Erenkucukersoftware\BrugsMigrationTool\Models\Scopes\ShopifyProductUpcScope;

class ShopifyProductUpc extends Model
{
  use HasFactory;
  protected $connection = 'mysql2';
  protected $table = 'br_product_variant_upcs';
  protected $hidden = ['date_created', 'date_modified', 'date_deleted', 'description'];



  public function productVariant()
  {
    return $this->belongsTo(ShopifyProductVariant::class);
  }

}
