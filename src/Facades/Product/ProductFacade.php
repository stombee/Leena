<?php

namespace Erenkucukersoftware\BrugsMigrationTool\Facades\Product;


use Erenkucukersoftware\BrugsMigrationTool\Facades\Product\Components\{ProductFields, ProductDB};


class ProductFacade
{
    public $product_fields;
    public $product_db;
    public $field_format;
    public $raw_products;
    public function __construct()
    {
        $this->product_db = new ProductDB();
        $raw_products = $this->product_db->getProducts();
        $this->raw_products = $raw_products;
        $this->product_fields = new ProductFields($raw_products);
    }


    public function rawProducts()
    {
      return $this->raw_products;
    }

    public function run()
    {
      return $this->product_fields->run();
    }

}