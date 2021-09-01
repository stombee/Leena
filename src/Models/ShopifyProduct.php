<?php

namespace Erenkucukersoftware\BrugsMigrationTool\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Erenkucukersoftware\BrugsMigrationTool\Models\Scopes\ShopifyProductScope;
use Erenkucukersoftware\BrugsMigrationTool\Models\ShopifyProductCustomCategory;

class ShopifyProduct extends Model
{
  use HasFactory;

  protected $connection = 'mysql2';
  protected $table = 'br_products';
  protected $hidden = ['date_created', 'date_modified', 'date_deleted'];


  public function variants()
  {
    return $this->hasMany(ShopifyProductVariant::class, 'real_design', 'real_design')->where('br_product_variants.display', 1);
  }


  public function customCategory()
  {
    return $this->hasMany(ShopifyProductCustomCategory::class, 'real_design', 'real_design');
  }

  public function colors()
  {
    return $this->hasMany(ShopifyProductColor::class, 'real_design', 'real_design');
  }

  public function type()
  {
    return $this->hasOne(ShopifyProductType::class, 'id', 'type_id')->withDefault();
  }
  public function subtype()
  {
    return $this->hasOne(ShopifyProductSubType::class, 'id', 'sub_type_id')->withDefault();
  }
  public function shopify_data()
  {
    return $this->hasOne(ShopifyProductShopifyData::class, 'real_design', 'real_design')->withDefault();
  }
  public function shopify_data_dev()
  {
    return $this->hasOne(ShopifyProductShopifyDataDev::class, 'real_design', 'real_design')->withDefault();
  }

  

  protected static function booted()
  {
    static::addGlobalScope(new ShopifyProductScope);
  }

}
