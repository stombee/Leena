<?php

namespace Erenkucukersoftware\BrugsMigrationTool\Facades\Product;

use Erenkucukersoftware\BrugsMigrationTool\Facades\Product\Components\ProductFields;
use Erenkucukersoftware\BrugsMigrationTool\Facades\Product\Components\ProductDB;
use Erenkucukersoftware\BrugsMigrationTool\BrugsMigrationTool;

class ProductFacade{

    public $product_fields;
    public $product_db;
    public $field_format;
  
    public function __construct(){
        $this->field_format = BrugsMigrationTool::$settings['API_MODE'] == 'GRAPHQL' ? 'json_line' : 'json';
        $this->product_db = new ProductDB();
        $products = $this->product_db->getProducts();
        $this->product_fields = new ProductFields($products);
        
        
    }

    public function getFields(){
      dd(BrugsMigrationTool::$settings['API_MODE']);
    }


}