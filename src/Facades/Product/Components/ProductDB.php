<?php

namespace Erenkucukersoftware\BrugsMigrationTool\Facades\Product\Components;


use Illuminate\Support\Facades\DB;
use Erenkucukersoftware\BrugsMigrationTool\BrugsMigrationTool;
use Illuminate\Support\Facades\Log;

use Erenkucukersoftware\BrugsMigrationTool\Models\ShopifyProduct;


class ProductDB{

  public $products;
  public $shopify_data;
  public $isDummy;
  public $dummyProducts =
  [
    [
      "real_design" => "A-103",
      "real_collection" => "",
      "design" => "ANK",
      "group_id" => "1130",
      "collection" => "Ankeny",
      "type_id" => 1,
      "sub_type_id" => 1,
      "description" => "Collection: Ankeny<br />Colors: Ink, Ink/Burgundy/Khaki/Dark Brown/Tan/Camel/Sage<br>Construction: Hand Tufted<br />Material: 100% NZ Wool<br />Pile: Medium Pile<br />Pile Height: 0.63<br />Style: Traditional<br />Outdoor Safe: No<br />Made in: India",
      "brand" => "Hauteloom",
      "country_of_origin" => "India",
      "designer" => "Hauteloom",
      "construction" => "Hand Tufted",
      "pile" => "Medium Pile",
      "keywords" => "Traditional  Persian",
      "style" => "Traditional",
      "material" => "100% NZ Wool",
      "fragile" => 0,
      "clearance" => 1,
      "assembly_required" => 0,
      "outdoor_safe" => 0,
      "top_seller" => 0,
      "washable" => 0,
      "recycled" => 0,
      "new_arrival" => 0,
      "featured" => 0,
      "display" => 1,
      "status" => "",
      "variants_count" => 5,
      "variants" =>  [
        0 => [
          "real_sku" => "A103-23",
          "sku" => "ANK-23",
          "real_design" => "A-103",
          "name" => "Ankeny Traditional  Persian 2' x 3' Area Rug",
          "shipping_id" => 1,
          "size" => "2' x 3'",
          "shape" => "Rectangle",
          "fringe" => 0,
          "display" => 1,
          "status" => "",
          "custom_status" => 0,
          "shippings" => [
            "id" => 1,
            "weight_lbs" => "6.000000",
            "length_in" => "36.00",
            "width_in" => "24.00",
            "height_in" => "0.63",
            "shipping_weight_lbs" => "6.00000",
            "shipping_length_in" => "24.00000",
            "shipping_width_in" => "3.000000",
            "shipping_height_in" => "3.000000",
          ],
          "price" =>  [
            "id" => 1,
            "real_sku" => "A103-23",
            "price" => 22425,
            "old_price" => 22425,
            "msrp" => 29900,
            "map" => 17940,
            "store_id" => 1,
          ]
        ],
        1 =>  [
          "real_sku" => "A103-3353",
          "sku" => "ANK-3353",
          "real_design" => "A-103",
          "name" => "Ankeny Traditional  Persian 3'3\" x 5'3\" Area Rug",
          "shipping_id" => 2,
          "size" => "3'3\" x 5'3\"",
          "shape" => "Rectangle",
          "fringe" => 0,
          "display" => 1,
          "status" => "Discontinued",
          "custom_status" => 0,
          "shippings" =>  [
            "id" => 2,
            "weight_lbs" => "14.500000",
            "length_in" => "63.00",
            "width_in" => "39.00",
            "height_in" => "0.63",
            "shipping_weight_lbs" => "15.00000",
            "shipping_length_in" => "39.00000",
            "shipping_width_in" => "7.000000",
            "shipping_height_in" => "7.000000",
          ],
          "price" =>  [
            "id" => 2,
            "real_sku" => "A103-3353",
            "price" => 67575,
            "old_price" => 67575,
            "msrp" => 90100,
            "map" => 54060,
            "store_id" => 1,
          ]
        ],
        2 => [
          "real_sku" => "A103-58",
          "sku" => "ANK-58",
          "real_design" => "A-103",
          "name" => "Ankeny Traditional  Persian 5' x 8' Area Rug",
          "shipping_id" => 3,
          "size" => "5' x 8'",
          "shape" => "Rectangle",
          "fringe" => 0,
          "display" => 1,
          "status" => "",
          "custom_status" => 0,
          "shippings" => [
            "id" => 3,
            "weight_lbs" => "35.000000",
            "length_in" => "96.00",
            "width_in" => "60.00",
            "height_in" => "0.63",
            "shipping_weight_lbs" => "35.00000",
            "shipping_length_in" => "60.00000",
            "shipping_width_in" => "8.000000",
            "shipping_height_in" => "8.000000",
          ],
          "price" =>  [
            "id" => 3,
            "real_sku" => "A103-58",
            "price" => 137550,
            "old_price" => 137550,
            "msrp" => 183400,
            "map" => 110040,
            "store_id" => 1,
          ]
        ],
        3 =>  [
          "real_sku" => "A103-8RD",
          "sku" => "ANK-8RD",
          "real_design" => "A-103",
          "name" => "Ankeny Traditional  Persian 8' Round Area Rug",
          "shipping_id" => 4,
          "size" => "8' Round",
          "shape" => "Round",
          "fringe" => 0,
          "display" => 1,
          "status" => "",
          "custom_status" => 0,
          "shippings" =>  [
            "id" => 4,
            "weight_lbs" => "56.000000",
            "length_in" => "96.00",
            "width_in" => "96.00",
            "height_in" => "0.63",
            "shipping_weight_lbs" => "48.00000",
            "shipping_length_in" => "96.00000",
            "shipping_width_in" => "8.000000",
            "shipping_height_in" => "8.000000",
          ],
          "price" =>  [
            "id" => 4,
            "real_sku" => "A103-8RD",
            "price" => 253125,
            "old_price" => 253125,
            "msrp" => 337500,
            "map" => 202500,
            "store_id" => 1,
          ]
        ],
        4 =>  [
          "real_sku" => "A103-913",
          "sku" => "ANK-913",
          "real_design" => "A-103",
          "name" => "Ankeny Traditional  Persian 9' x 13' Area Rug",
          "shipping_id" => 5,
          "size" => "9' x 13'",
          "shape" => "Rectangle",
          "fringe" => 0,
          "display" => 1,
          "status" => "",
          "custom_status" => 0,
          "shippings" =>  [
            "id" => 5,
            "weight_lbs" => "112.000000",
            "length_in" => "156.00",
            "width_in" => "108.00",
            "height_in" => "0.63",
            "shipping_weight_lbs" => "112.00000",
            "shipping_length_in" => "108.00000",
            "shipping_width_in" => "10.000000",
            "shipping_height_in" => "10.000000",
          ],
          "price" => [
            "id" => 5,
            "real_sku" => "A103-913",
            "price" => 462525,
            "old_price" => 462525,
            "msrp" => 616700,
            "map" => 370020,
            "store_id" => 1,
          ]
        ]
      ],
      "custom_category" =>  [
        0 =>  [
          "id" => 1,
          "real_design" => "A-103",
          "custom_category_id" => 1,
          "value" => "2x3",
          "custom_category_name" => [
            "id" => 1,
            "name" => "size"
          ]
        ],
        1 =>  [
          "id" => 2,
          "real_design" => "A-103",
          "custom_category_id" => 1,
          "value" => "3x5",
          "custom_category_name" =>  [
            "id" => 1,
            "name" => "size",
          ]
        ],
        2 =>  [
          "id" => 3,
          "real_design" => "A-103",
          "custom_category_id" => 1,
          "value" => "5x8",
          "custom_category_name" =>  [
            "id" => 1,
            "name" => "size",
          ]
        ],
        3 =>  [
          "id" => 4,
          "real_design" => "A-103",
          "custom_category_id" => 1,
          "value" => "8x10",
          "custom_category_name" =>  [
            "id" => 1,
            "name" => "size",
          ]
        ],
        4 =>  [
          "id" => 5,
          "real_design" => "A-103",
          "custom_category_id" => 1,
          "value" => "9x12",
          "custom_category_name" =>  [
            "id" => 1,
            "name" => "size",
          ]
        ],
        5 =>  [
          "id" => 6,
          "real_design" => "A-103",
          "custom_category_id" => 2,
          "value" => "rectangle",
          "custom_category_name" =>  [
            "id" => 2,
            "name" => "shape",
          ]
        ],
        6 => [
          "id" => 7,
          "real_design" => "A-103",
          "custom_category_id" => 2,
          "value" => "round",
          "custom_category_name" =>  [
            "id" => 2,
            "name" => "shape",
          ]
        ],
        7 =>  [
          "id" => 8,
          "real_design" => "A-103",
          "custom_category_id" => 3,
          "value" => "200-299",
          "custom_category_name" =>  [
            "id" => 3,
            "name" => "price",
          ]
        ],
        8 =>  [
          "id" => 9,
          "real_design" => "A-103",
          "custom_category_id" => 3,
          "value" => "500-699",
          "custom_category_name" => [
            "id" => 3,
            "name" => "price",
          ]
        ],
        9 => [
          "id" => 10,
          "real_design" => "A-103",
          "custom_category_id" => 3,
          "value" => "over-900",
          "custom_category_name" =>  [
            "id" => 3,
            "name" => "price",
          ]
        ],
        10 =>  [
          "id" => 11,
          "real_design" => "A-103",
          "custom_category_id" => 4,
          "value" => "Traditional",
          "custom_category_name" =>  [
            "id" => 4,
            "name" => "style",
          ]
        ],
        11 =>  [
          "id" => 12,
          "real_design" => "A-103",
          "custom_category_id" => 5,
          "value" => "100% NZ Wool",
          "custom_category_name" =>  [
            "id" => 5,
            "name" => "material"
          ]
        ]
      ],
      "colors" =>  [
        0 =>  [
          "id" => 1,
          "real_design" => "A-103",
          "color_name" =>  [
            "name" => "Ink",
          ]
        ],
        1 =>  [
          "id" => 2,
          "real_design" => "A-103",
          "color_name" =>  [
            "name" => "Burgundy",
          ]
        ],
        2 =>  [
          "id" => 3,
          "real_design" => "A-103",
          "color_name" =>  [
            "name" => "Khaki"
          ]
        ],
        3 =>  [
          "id" => 4,
          "real_design" => "A-103",
          "color_name" =>  [
            "name" => "Dark Brown"
          ]
        ],
        4 =>  [
          "id" => 5,
          "real_design" => "A-103",
          "color_name" =>  [
            "name" => "Tan"
          ]
        ],
        5 =>  [
          "id" => 6,
          "real_design" => "A-103",
          "color_name" =>  [
            "name" => "Camel"
          ]
        ],
        6 =>  [
          "id" => 7,
          "real_design" => "A-103",
          "color_name" =>  [
            "name" => "Sage"
          ]
        ]
      ],
      "type" =>  [
        "id" => 1,
        "name" => "Rugs",
        "tax_code" => "P0000000",
      ],
      "subtype" =>  [
        "id" => 1,
        "type_id" => 1,
        "name" => "Area Rug"
      ],
      "shopify_data" =>  [
        "id" => 568,
        "real_design" => "A-103",
        "shopify_product_id" => 6729675243712,
        "data" => "",
        "bc_product_id" => 18700,
      ],
      "graphql_id" => "gid://shopify/Product/6729675243712",
      "metafield_graphql_api_id" => "gid://shopify/Metafield/19665090379968",
      "color_variants" =>  [
        0 =>  [
          "parent_id" => "1130",
          "design" => "ANK",
          "real_design" => "A-103",
          "tiny_image_url" => "https://cdn.shopify.com/s/files/1/0550/8700/5888/products/a103__73625.1544832922.1280.1280_60x60.png?v=1624234251",
          "url" => "https://boutiquerugs.com/collections/all/products/ankeny-area-rug",
          "this" => false
        ]
      ]
    ],
    [
      "real_design" => "A-105blabla",
      "real_collection" => "",
      "design" => "ANK",
      "group_id" => "1130",
      "collection" => "Ankeny",
      "type_id" => 1,
      "sub_type_id" => 1,
      "description" => "Collection: Ankeny<br />Colors: Ink, Ink/Burgundy/Khaki/Dark Brown/Tan/Camel/Sage<br>Construction: Hand Tufted<br />Material: 100% NZ Wool<br />Pile: Medium Pile<br />Pile Height: 0.63<br />Style: Traditional<br />Outdoor Safe: No<br />Made in: India",
      "brand" => "Hauteloom",
      "country_of_origin" => "India",
      "designer" => "Hauteloom",
      "construction" => "Hand Tufted",
      "pile" => "Medium Pile",
      "keywords" => "Traditional  Persian",
      "style" => "Traditional",
      "material" => "100% NZ Wool",
      "fragile" => 0,
      "clearance" => 1,
      "assembly_required" => 0,
      "outdoor_safe" => 0,
      "top_seller" => 0,
      "washable" => 0,
      "recycled" => 0,
      "new_arrival" => 0,
      "featured" => 0,
      "display" => 1,
      "status" => "",
      "variants_count" => 5,
      "variants" =>  [
        0 => [
          "real_sku" => "A103-23",
          "sku" => "ANK-23",
          "real_design" => "A-103",
          "name" => "Ankeny Traditional  Persian 2' x 3' Area Rug",
          "shipping_id" => 1,
          "size" => "2' x 3'",
          "shape" => "Rectangle",
          "fringe" => 0,
          "display" => 1,
          "status" => "",
          "custom_status" => 0,
          "shippings" => [
            "id" => 1,
            "weight_lbs" => "6.000000",
            "length_in" => "36.00",
            "width_in" => "24.00",
            "height_in" => "0.63",
            "shipping_weight_lbs" => "6.00000",
            "shipping_length_in" => "24.00000",
            "shipping_width_in" => "3.000000",
            "shipping_height_in" => "3.000000",
          ],
          "price" =>  [
            "id" => 1,
            "real_sku" => "A103-23",
            "price" => 22425,
            "old_price" => 22425,
            "msrp" => 29900,
            "map" => 17940,
            "store_id" => 1,
          ]
        ],
        1 =>  [
          "real_sku" => "A103-3353",
          "sku" => "ANK-3353",
          "real_design" => "A-103",
          "name" => "Ankeny Traditional  Persian 3'3\" x 5'3\" Area Rug",
          "shipping_id" => 2,
          "size" => "3'3\" x 5'3\"",
          "shape" => "Rectangle",
          "fringe" => 0,
          "display" => 1,
          "status" => "Discontinued",
          "custom_status" => 0,
          "shippings" =>  [
            "id" => 2,
            "weight_lbs" => "14.500000",
            "length_in" => "63.00",
            "width_in" => "39.00",
            "height_in" => "0.63",
            "shipping_weight_lbs" => "15.00000",
            "shipping_length_in" => "39.00000",
            "shipping_width_in" => "7.000000",
            "shipping_height_in" => "7.000000",
          ],
          "price" =>  [
            "id" => 2,
            "real_sku" => "A103-3353",
            "price" => 67575,
            "old_price" => 67575,
            "msrp" => 90100,
            "map" => 54060,
            "store_id" => 1,
          ]
        ],
        2 => [
          "real_sku" => "A103-58",
          "sku" => "ANK-58",
          "real_design" => "A-103",
          "name" => "Ankeny Traditional  Persian 5' x 8' Area Rug",
          "shipping_id" => 3,
          "size" => "5' x 8'",
          "shape" => "Rectangle",
          "fringe" => 0,
          "display" => 1,
          "status" => "",
          "custom_status" => 0,
          "shippings" => [
            "id" => 3,
            "weight_lbs" => "35.000000",
            "length_in" => "96.00",
            "width_in" => "60.00",
            "height_in" => "0.63",
            "shipping_weight_lbs" => "35.00000",
            "shipping_length_in" => "60.00000",
            "shipping_width_in" => "8.000000",
            "shipping_height_in" => "8.000000",
          ],
          "price" =>  [
            "id" => 3,
            "real_sku" => "A103-58",
            "price" => 137550,
            "old_price" => 137550,
            "msrp" => 183400,
            "map" => 110040,
            "store_id" => 1,
          ]
        ],
        3 =>  [
          "real_sku" => "A103-8RD",
          "sku" => "ANK-8RD",
          "real_design" => "A-103",
          "name" => "Ankeny Traditional  Persian 8' Round Area Rug",
          "shipping_id" => 4,
          "size" => "8' Round",
          "shape" => "Round",
          "fringe" => 0,
          "display" => 1,
          "status" => "",
          "custom_status" => 0,
          "shippings" =>  [
            "id" => 4,
            "weight_lbs" => "56.000000",
            "length_in" => "96.00",
            "width_in" => "96.00",
            "height_in" => "0.63",
            "shipping_weight_lbs" => "48.00000",
            "shipping_length_in" => "96.00000",
            "shipping_width_in" => "8.000000",
            "shipping_height_in" => "8.000000",
          ],
          "price" =>  [
            "id" => 4,
            "real_sku" => "A103-8RD",
            "price" => 253125,
            "old_price" => 253125,
            "msrp" => 337500,
            "map" => 202500,
            "store_id" => 1,
          ]
        ],
        4 =>  [
          "real_sku" => "A103-913",
          "sku" => "ANK-913",
          "real_design" => "A-103",
          "name" => "Ankeny Traditional  Persian 9' x 13' Area Rug",
          "shipping_id" => 5,
          "size" => "9' x 13'",
          "shape" => "Rectangle",
          "fringe" => 0,
          "display" => 1,
          "status" => "",
          "custom_status" => 0,
          "shippings" =>  [
            "id" => 5,
            "weight_lbs" => "112.000000",
            "length_in" => "156.00",
            "width_in" => "108.00",
            "height_in" => "0.63",
            "shipping_weight_lbs" => "112.00000",
            "shipping_length_in" => "108.00000",
            "shipping_width_in" => "10.000000",
            "shipping_height_in" => "10.000000",
          ],
          "price" => [
            "id" => 5,
            "real_sku" => "A103-913",
            "price" => 462525,
            "old_price" => 462525,
            "msrp" => 616700,
            "map" => 370020,
            "store_id" => 1,
          ]
        ]
      ],
      "custom_category" =>  [
        0 =>  [
          "id" => 1,
          "real_design" => "A-103",
          "custom_category_id" => 1,
          "value" => "2x3",
          "custom_category_name" => [
            "id" => 1,
            "name" => "size"
          ]
        ],
        1 =>  [
          "id" => 2,
          "real_design" => "A-103",
          "custom_category_id" => 1,
          "value" => "3x5",
          "custom_category_name" =>  [
            "id" => 1,
            "name" => "size",
          ]
        ],
        2 =>  [
          "id" => 3,
          "real_design" => "A-103",
          "custom_category_id" => 1,
          "value" => "5x8",
          "custom_category_name" =>  [
            "id" => 1,
            "name" => "size",
          ]
        ],
        3 =>  [
          "id" => 4,
          "real_design" => "A-103",
          "custom_category_id" => 1,
          "value" => "8x10",
          "custom_category_name" =>  [
            "id" => 1,
            "name" => "size",
          ]
        ],
        4 =>  [
          "id" => 5,
          "real_design" => "A-103",
          "custom_category_id" => 1,
          "value" => "9x12",
          "custom_category_name" =>  [
            "id" => 1,
            "name" => "size",
          ]
        ],
        5 =>  [
          "id" => 6,
          "real_design" => "A-103",
          "custom_category_id" => 2,
          "value" => "rectangle",
          "custom_category_name" =>  [
            "id" => 2,
            "name" => "shape",
          ]
        ],
        6 => [
          "id" => 7,
          "real_design" => "A-103",
          "custom_category_id" => 2,
          "value" => "round",
          "custom_category_name" =>  [
            "id" => 2,
            "name" => "shape",
          ]
        ],
        7 =>  [
          "id" => 8,
          "real_design" => "A-103",
          "custom_category_id" => 3,
          "value" => "200-299",
          "custom_category_name" =>  [
            "id" => 3,
            "name" => "price",
          ]
        ],
        8 =>  [
          "id" => 9,
          "real_design" => "A-103",
          "custom_category_id" => 3,
          "value" => "500-699",
          "custom_category_name" => [
            "id" => 3,
            "name" => "price",
          ]
        ],
        9 => [
          "id" => 10,
          "real_design" => "A-103",
          "custom_category_id" => 3,
          "value" => "over-900",
          "custom_category_name" =>  [
            "id" => 3,
            "name" => "price",
          ]
        ],
        10 =>  [
          "id" => 11,
          "real_design" => "A-103",
          "custom_category_id" => 4,
          "value" => "Traditional",
          "custom_category_name" =>  [
            "id" => 4,
            "name" => "style",
          ]
        ],
        11 =>  [
          "id" => 12,
          "real_design" => "A-103",
          "custom_category_id" => 5,
          "value" => "100% NZ Wool",
          "custom_category_name" =>  [
            "id" => 5,
            "name" => "material"
          ]
        ]
      ],
      "colors" =>  [
        0 =>  [
          "id" => 1,
          "real_design" => "A-103",
          "color_name" =>  [
            "name" => "Ink",
          ]
        ],
        1 =>  [
          "id" => 2,
          "real_design" => "A-103",
          "color_name" =>  [
            "name" => "Burgundy",
          ]
        ],
        2 =>  [
          "id" => 3,
          "real_design" => "A-103",
          "color_name" =>  [
            "name" => "Khaki"
          ]
        ],
        3 =>  [
          "id" => 4,
          "real_design" => "A-103",
          "color_name" =>  [
            "name" => "Dark Brown"
          ]
        ],
        4 =>  [
          "id" => 5,
          "real_design" => "A-103",
          "color_name" =>  [
            "name" => "Tan"
          ]
        ],
        5 =>  [
          "id" => 6,
          "real_design" => "A-103",
          "color_name" =>  [
            "name" => "Camel"
          ]
        ],
        6 =>  [
          "id" => 7,
          "real_design" => "A-103",
          "color_name" =>  [
            "name" => "Sage"
          ]
        ]
      ],
      "type" =>  [
        "id" => 1,
        "name" => "Rugs",
        "tax_code" => "P0000000",
      ],
      "subtype" =>  [
        "id" => 1,
        "type_id" => 1,
        "name" => "Area Rug"
      ],
      "shopify_data" =>  [
        "id" => 568,
        "real_design" => "A-103",
        "shopify_product_id" => 6729675243712,
        "data" => "",
        "bc_product_id" => 18700,
      ],
      "graphql_id" => "gid://shopify/Product/6729675243712",
      "metafield_graphql_api_id" => "gid://shopify/Metafield/19665090379968",
      "color_variants" =>  [
        0 =>  [
          "parent_id" => "1130",
          "design" => "ANK",
          "real_design" => "A-103",
          "tiny_image_url" => "https://cdn.shopify.com/s/files/1/0550/8700/5888/products/a103__73625.1544832922.1280.1280_60x60.png?v=1624234251",
          "url" => "https://boutiquerugs.com/collections/all/products/ankeny-area-rug",
          "this" => false
        ]
      ]
    ]
  ];


  public function __construct(){
    $this->shopify_data = BrugsMigrationTool::$settings['IS_DEV'] ? 'shopify_data_dev':'shopify_data';
  }


  public function getColorVariants(){

    $color_variant_count = DB::connection('mysql2')->select('SELECT count(*) as color_variant_count  FROM shopify_data AS bd, br_products AS mp WHERE bd.real_design = mp.real_design AND (bd.parent_id IS NOT NULL AND bd.parent_id != \'\')  ')[0]->color_variant_count;
    $maxAtOneTime = 2000;
    $color_variant_pages = ceil($color_variant_count / $maxAtOneTime);
    $color_variants = collect();

    for ($i = 1; $i < ($color_variant_pages + 1); $i++) {

      $offset = (($i - 1) * $maxAtOneTime);
      $start = ($offset == 0 ? 0 : ($offset + 1));

      $data = DB::connection('mysql2')->select('SELECT bd.data,bd.parent_id, mp.design,mp.display,bd.real_design,bd.tiny_image_url FROM shopify_data AS bd, br_products AS mp WHERE bd.real_design = mp.real_design AND (bd.parent_id IS NOT NULL AND bd.parent_id != \'\') AND display = 1  LIMIT ' . $maxAtOneTime . ' OFFSET ' . $start . ' ');


      $color_variants = $color_variants->merge($data);
    }

    $color_variants_arr = [];
    foreach ($color_variants as $color_variant) {

      $parent_id = $color_variant->parent_id;
      $bc_data = json_decode($color_variant->data, true);
      $bc_data = collect($bc_data)->except(['body_html', 'created_at', 'product_type', 'updated_at', 'published_at', 'template_suffix', 'published_scope', 'vendor', 'options', 'metafield_graphql_api_id'])->toArray();
      $color_variant->data = $bc_data;
      $color_variant->url = 'https://boutiquerugs.com/collections/all/products/' . $bc_data['handle'];
      $color_variant->this = false;

      $color_variants_arr[$parent_id][] = ['parent_id' => $parent_id, 'design' => $color_variant->design, 'real_design' => $color_variant->real_design, 'tiny_image_url' => $color_variant->tiny_image_url, 'url' => $color_variant->url, 'this' => false];
    }

    Log::debug('variants completed');

    return $color_variants_arr;

  }

  public function getProducts($isDummy = false){
    $this->isDummy = $isDummy;

    if($isDummy){
      return $this->dummyProducts;
    }

    
    $color_variants_arr = $this->getColorVariants();

    $products = collect();

    $total_count = ShopifyProduct::where('real_design', '<', 'KDA-001')->where('display', '=', 1)->count();
    $maxAtOneTime = 1500;
    $pages = ceil($total_count / $maxAtOneTime);

    for ($i = 1; $i < ($pages + 1); $i++) {

      $offset = (($i - 1) * $maxAtOneTime);
      $start = ($offset == 0 ? 0 : ($offset + 1));

      $data = ShopifyProduct::with(['variants.shippings', 'variants.price', 'customCategory.custom_category_name', 'colors.color_name', 'type', 'subtype', $this->shopify_data])->withCount('variants')->where('real_design', '<', 'KDA-001')->where('display', '=', 1)->offset($start)->limit($maxAtOneTime)->get()->toArray();

      $data = collect($data)->map(function ($data) use ($color_variants_arr) {

        $data['graphql_id'] = null;
        if (array_key_exists('shopify_product_id', $data[$this->shopify_data])) {
          $data['graphql_id'] = 'gid://shopify/Product/' . $data[$this->shopify_data]['shopify_product_id'];
        }

        $data['metafield_graphql_api_id'] = null;

        if (isset($data[$this->shopify_data]['data'])) {
          $json_data =  $data[$this->shopify_data]['data'];
          $prod_json = json_decode($json_data, true);
          if (isset($prod_json['metafield_graphql_api_id']['custom_data2'])) {

            $data['metafield_graphql_api_id'] = $prod_json['metafield_graphql_api_id']['custom_data2'];
          }
        }



        $data['color_variants'] = null;
        if (array_key_exists($data['group_id'], $color_variants_arr) && count($color_variants_arr[$data['group_id']]) > 0) {
          $data['color_variants'] = $color_variants_arr[$data['group_id']];
        }

        
        return $data;
      });
      Log::debug('product2 come');


      Log::debug('product fields added');
      
      $products = $products->merge($data);
      
    }


    $total_count = ShopifyProduct::where('real_design', '>', 'KDA-001')->where('display', '=', 1)->count();
    $maxAtOneTime = 1500;
    $pages = ceil($total_count / $maxAtOneTime);


    for (
      $i = 1;
      $i < ($pages + 1);
      $i++
    ) {
      $offset = (($i - 1) * $maxAtOneTime);
      $start = ($offset == 0 ? 0 : ($offset + 1));
      $data = ShopifyProduct::with(['variants.shippings', 'variants.price', 'customCategory.custom_category_name', 'colors.color_name', 'type', 'subtype', $this->shopify_data])->withCount('variants')->where('real_design', '>', 'KDA-001')->where('display', '=', 1)->offset($start)->limit($maxAtOneTime)->get()->toArray();
      $data = collect($data)->map(function ($data) use ($color_variants_arr) {

        $data['graphql_id'] = null;
        if (array_key_exists('shopify_product_id', $data[$this->shopify_data])) {
          $data['graphql_id'] = 'gid://shopify/Product/' . $data[$this->shopify_data]['shopify_product_id'];
        }

        $data['metafield_graphql_api_id'] = null;

        if (isset($data[$this->shopify_data]['data'])) {
          $json_data =  $data[$this->shopify_data]['data'];
          $prod_json = json_decode($json_data, true);
          if (isset($prod_json['metafield_graphql_api_id']['custom_data2'])) {

            $data['metafield_graphql_api_id'] = $prod_json['metafield_graphql_api_id']['custom_data2'];
          }
        }


        $data['color_variants'] = null;
        if (array_key_exists($data['group_id'], $color_variants_arr) && count($color_variants_arr[$data['group_id']]) > 0) {
          $data['color_variants'] = $color_variants_arr[$data['group_id']];
        }


        return $data;
      });
      Log::debug('product come');


      Log::debug('product fields added');


      $products = $products->merge($data);
    }



    $products = $products->toArray();

    foreach ($products as $index => $product) {
      
      if (isset($product['variants']) && isset($product[$this->shopify_data]['variants_data'])) {
        foreach ($product['variants'] as $indexvar => $variant) {
          $variant_json = json_decode($product[$this->shopify_data]['variants_data'], true);
          $variant_graph_id = null;
          foreach ($variant_json as $var) {
            if ($var['sku'] == $variant['sku']) {
              $variant_graph_id = 'gid://shopify/ProductVariant/' . $var['variant_id'];
            }
          }
          $products[$index]['variants'][$indexvar]['variant_graph_id'] = $variant_graph_id;
        }
      }

      if (isset($product['color_variants'])) {
        foreach ($product['color_variants'] as $index2 => $variant) {
          if ($product['real_design'] == $variant['real_design']) {
            $products[$index]['color_variants'][$index2]['this'] = true;
          }
        }
      }
    }
    
    
    return $products;
  }


 




}