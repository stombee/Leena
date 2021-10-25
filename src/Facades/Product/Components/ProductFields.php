<?php

namespace Erenkucukersoftware\BrugsMigrationTool\Facades\Product\Components;


use Erenkucukersoftware\BrugsMigrationTool\Enums\{Fields, Operations, ToolStatus, CustomGroups, ApiMode};
use Illuminate\Support\Facades\Log;
use Erenkucukersoftware\BrugsMigrationTool\{BrugsMigrationTool, Constants};
use Erenkucukersoftware\BrugsMigrationTool\Facades\Shopify\ShopifyFlowFacade;



ini_set('max_execution_time', 0);
ini_set('memory_limit', '5G');


class ProductFields
{

  public $products;
  public $variants;
  public $variant_shippings;
  public $variant_count = 0;
  public $body_html;
  public $materials;
  public $meta_keyword;
  public $dimensions;
  public $tags;
  public $color_variants;
  public $results;
  public $result;
  public $shopify_data;
  public $styles;
  public $sizes;
  public $shapes;
  public $prices;
  public $category;
  public $colors;
  public $colors_master;
  public $material_master;
  public $metafields;
  public $meta_keywords;
  public $custom_categories;



  public function __construct($products)
  {
    $this->fields = BrugsMigrationTool::$settings['FIELDS'];
    $this->products = $products;
    $this->shopify_data = BrugsMigrationTool::$settings['IS_DEV'] ? 'shopify_data_dev' : 'shopify_data';
  }




  //SUB ENTITY GETS

  private function get_description($product)
  {
    $description = $product['description'];
  }

  private function get_material_master()
  {
    $category = $this->category;
    $this->material_master = null;

    $materials = explode(',', $this->materials);
    $material_categories = collect(Constants::$SPECIAL_MATERIAL_CATEGORIES);

    $materials_master_arr = [];
    foreach ($materials as $material) {
      $master_material = $material_categories->first(function ($value, $key) use ($material) {
        return like_match('%'.$material.'%', $value);
      });

      empty($master_material) ? null : $materials_master_arr[] = $master_material;
    }

    if (is_array($materials_master_arr) && count($materials_master_arr) > 0) {
      $this->material_master = implode(",", $materials_master_arr);
    }
    return $this;
  }

  private function get_color_master()
  {
    $this->colors_master = null;
    $colors = explode(',', $this->colors);
    $color_categories = Constants::$SPECIAL_COLOR_CATEGORIES;

    $colors_master_arr = [];
    foreach ($colors as $color) {
      if (array_key_exists($color, $color_categories) && !empty($color_categories[$color])) {
        $colors_master_arr[] = $color_categories[$color];
      }
    }
    $colors_master_arr = collect($colors_master_arr)->flatten()->unique()->toArray();


    if (is_array($colors_master_arr) && count($colors_master_arr) > 0) {
      $this->colors_master = implode(',', $colors_master_arr);
    }

    return $this;
  }
  private function get_materials($product)
  {
    $this->materials = null;

    $product = collect($product['custom_category']);

    $materials = $product->filter(function ($value, $key) {

      return $value != '' && $value["custom_category_id"] == 5;
    })->pluck("value");

    $materials = $materials->filter()->toArray();

    if (is_array($materials) && !in_array('', $materials) && !in_array(null, $materials) && count($materials) > 0) {
      $materials_string = implode(",", $materials);

      $this->materials = $materials_string;
    }


    return $this;
  }

  private function get_styles($product)
  {
    $this->styles = null;
    $product = collect($product['custom_category']);
    $styles = $product->filter(function ($value, $key) {
      return $value["custom_category_id"] == 4;
    })->pluck("value")->toArray();

    if (is_array($styles) && count($styles) > 0) {
      $styles_string = implode(",", $styles);
      $this->styles = $styles_string;
    }
    return $this;
  }
  private function get_sizes($product)
  {
    $this->sizes = null;
    $product = collect($product['custom_category']);
    $sizes = $product->filter(function ($value, $key) {
      return $value["custom_category_id"] == 1;
    })->pluck("value")->toArray();

    if (is_array($sizes) && count($sizes) > 0) {
      $sizes_string = implode(",", $sizes);
      $this->sizes = $sizes_string;
    }
    return $this;
  }
  private function get_shapes($product)
  {
    $this->shapes = null;
    $product = collect($product['custom_category']);
    $shapes = $product->filter(function ($value, $key) {
      return $value["custom_category_id"] == 2;
    })->pluck("value")->toArray();

    if (is_array($shapes) && count($shapes) > 0) {
      $shapes_string = implode(",", $shapes);
      $this->shapes = $shapes_string;
    }
    return $this;
  }
  private function get_prices($product)
  {
    $this->prices = null;
    $product = collect($product['custom_category']);
    $prices = $product->filter(function ($value, $key) {
      return $value["custom_category_id"] == 3;
    })->pluck("value")->toArray();

    if (is_array($prices) && count($prices) > 0) {
      $prices_string = implode(",", $prices);
      $this->prices = $prices_string;
    }
    return $this;
  }

  private function get_category($product)
  {
    $this->category = null;
    $this->category = $product["type"]["name"];
    return $this;
  }

  private function get_colors($product)
  {
    $this->colors = null;
    $product = collect($product['colors']);
    $colors = $product->map(function ($value) {
      return $value["color_name"]["name"];
    })->toArray();

    if (is_array($colors) && count($colors) > 0) {

      $colors_string = implode(",", $colors);
      $this->colors = $colors_string;
    }
    return $this;
  }

  private function get_custom_categories($product)
  {
    $this->custom_categories = null;
    $custom_categories = [];
    if ($product['clearance']) {
      $custom_categories[] = 'Clearance';
    }
    if ($product['top_seller']) {
      $custom_categories[] = 'Top Seller';
    }
    if ($product['washable']) {
      $custom_categories[] = 'Washable';
    }
    if ($product['recycled']) {
      $custom_categories[] = 'Recycled';
    }
    if ($product['new_arrival']) {
      $custom_categories[] = 'New Arrival';
    }
    if ($product['featured']) {
      $custom_categories[] = 'Featured';
    }
    if ($product['outdoor_safe']) {
      $custom_categories[] = 'Outdoor';
    }

    return $this->custom_categories = $custom_categories;
  }

  private function get_subtype($product)
  {
    return $product['subtype']['name'];
  }
  private function get_type($product)
  {
    return $product['type']['name'];
  }



  //MAIN SHOPIFY ENTITY GETS

  private function get_title($product)
  {
    $title_collection = ($product['collection'] == 'Tigris' || $product['collection'] == 'Istanbul' || $product['collection'] == 'MARASH' || $product['collection'] == 'Magnolia'|| $product['collection'] == 'HOLI') ? $product['design'] . ' ' : '';
    $title_washable = $product['washable'] ? 'Washable ' : '';
    return $product['collection'] . ' ' . $title_collection . '' . $title_washable . '' . $product['subtype']['name'];
  }


  private function get_body_html($parent_data, $get_custom_description = false)
  {

    $this->get_materials($parent_data);
    $this->get_styles($parent_data);

    $cv = '';
    $customDescription['description'] = [];

    if (isset($parent_data["group_id"])) { // was parentname
      $master_parent_id = $parent_data["group_id"];
      $design_id = $parent_data["design"];

      $tabs = array();
      if (!isset($d)) $d = '';

      $d .= '
    <div style="font-size:14px;">
    ';

      //Brand: Surya
      $brandInfo = '<b>Brand:</b> ' . $parent_data['brand'] . '<br>';
      $d .= $brandInfo;
      $customDescription['description'][] = [
        'title' => 'Brand',
        'content' => $parent_data['brand']
      ];

      if (!empty($parent_data["collection"])) {
        $collectionInfo = '<b>Collection:</b> ' . $parent_data["collection"] . '<br>';
        $d .= $collectionInfo;
        $customDescription['description'][] = [
          'title' => 'Collection',
          'content' => $parent_data["collection"]
        ];
      }
      //COLORS


      if (isset($parent_data['colors'])) {
        $colors = [];
        foreach ($parent_data['colors'] as $color) {
          $colors[] = $color['color_name']['name'];
        }
      }


      if (count($colors) > 0) {
        $colorsInfo = "<b>Colors:</b> " . implode(", ", $colors) . "<br>";
        $d .= $colorsInfo;
        $customDescription['description'][] = [
          'title' => 'Colors',
          'content' => implode(", ", $colors)
        ];
      }

      //if(!empty($parent_data[colors])) $d.= "<b>Colors:</b> $parent_data[colors]<br>";
      //if(!empty($parent_data[color])) $d.= "<b>Colors:</b> $parent_data[color]<br>";

      if (!empty($parent_data["construction"])) {
        $constructionInfo = '<b>Construction:</b> ' . $parent_data["construction"] . '<br>';
        $d .= $constructionInfo;
        $customDescription['description'][] = [
          'title' => 'Construction',
          'content' => $parent_data["construction"]
        ];
      }
      /*
            echo "<br>parent_data:<pre>";
            print_r($parent_data);
            echo "</pre>";
        */

      $materialInfo = '<b>Material: </b>' . $this->materials . '<br>';
      $d .= $materialInfo;
      $customDescription['description'][] = [
        'title' => 'Material',
        'content' => $this->materials
      ];

      //if(!empty($parent_data[backing])) $d.= "<b>Backing:</b> $parent_data[backing]<br>";
      if (!empty($parent_data["pile"])) {
        $pileInfo = '<b>Pile:</b> ' . $parent_data["pile"] . '<br>';
        $d .= $pileInfo;
        $customDescription['description'][] = [
          'title' => 'Pile',
          'content' => $parent_data["pile"]
        ];
      }
      //X if (!empty($parent_data["pileheight-in"])) $d .= '<b>Pile Height:</b> ' . number_format((float)$parent_data["pileheight-in"], 2, '.', '') . '<br>';
      if (!empty($parent_data["variants"][0]["shippings"]["height_in"]) && $parent_data["type"]["name"] == "Rugs") {
        $pileHeightInfo = '<b>Pile Height:</b> ' . number_format((float)$parent_data["variants"][0]["shippings"]["height_in"], 2, '.', '') . '"<br>';
        $d .= $pileHeightInfo;
        $customDescription['description'][] = [
          'title' => 'Pile Height',
          'content' => number_format((float)$parent_data["variants"][0]["shippings"]["height_in"], 2, '.', '')
        ];
      }

      if (
        ($parent_data["subtype"]["name"] == "Table Lamp" || $parent_data["subtype"]["name"] == "Floor Lamp"
        || $parent_data["subtype"]["name"] == "Ceiling Lighting" || $parent_data["subtype"]["name"] == "Wall Sconces")
        && !empty($parent_data["size"])
      ) {
        $pileHeightInfo = '<b>Dimensions:</b> ' . $parent_data["size"] . '"<br>';
        $d .= $pileHeightInfo;
        $customDescription['description'][] = [
          'title' => 'Dimensions',
          'content' => $parent_data["size"]
        ];
      }

      //if($surya_data["pileheight-in"] > 0 )
      //if(!empty($parent_data[navigationalcolor])) $d.= "<b>Navigational Color:</b> $parent_data[navigationalcolor]<br>";

      //if(!empty($parent_data[pantone])) $d.= "<b>Pantone #:</b> $parent_data[pantone]<br>";
      if (!empty($this->styles)) {
        $styleInfo = "<b>Style:</b> " . $this->styles . "<br>";
        $d .= $styleInfo;
        $customDescription['description'][] = [
          'title' => 'Style',
          'content' => $this->styles
        ];
      }

      //if(!empty($parent_data[shape])) $d.= "Shape: $parent_data[shape]<br>";
      //
      //if(!empty($parent_data[bordermaterial])) $d.= "<b>Border Material:</b> $parent_data[bordermaterial]<br>";
      //if(!empty($parent_data[outdoorsafe])) $d.= "<b>Outdoor Safe?:</b> $parent_data[outdoorsafe]<br>";
      //if(!empty($parent_data[rugpadneeded])) $d.= "<b>Rug Pad Needed?:</b> $parent_data[rugpadneeded]<br>";
      //X if (!empty($parent_data["countryoforigin"])) $d .= "<b>Made In:</b> " . $parent_data["countryoforigin"] . "<br>";
      if (
        isset($parent_data['outdoor_safe']) &&
        ($parent_data['outdoor_safe'] == 1 || $parent_data['outdoor_safe'] == 0)
        && $parent_data["type"]["name"] == 'Rugs'
      ) {
        $outdoorStatus = $parent_data['outdoor_safe'] == 1 ? 'Yes' : 'No';
        $outdoorInfo = "<b>Outdoor Safe:</b> $outdoorStatus<br>";
        $d .= $outdoorInfo;
        $customDescription['description'][] = [
          'title' => 'Outdoor Safe',
          'content' => $outdoorStatus
        ];
      }






      if ($parent_data['washable'] == 1) {
        $washableInfo = "<b>Machine washable:</b> Yes<br>";
        $d .= $washableInfo;
        $customDescription['description'][] = [
          'title' => 'Machine Washable',
          'content' => 'Yes'
        ];
      }
      if ($parent_data['recycled'] == 1) {
        $recycledInfo = "<b>Recycled:</b> Yes<br>";
        $d .= $recycledInfo;
        $customDescription['description'][] = [
          'title' => 'Recycled',
          'content' => 'Yes'
        ];
      }






      if (!empty($parent_data["country_of_origin"])) {
        $customOfOriginInfo = "<b>Made In:</b> " . $parent_data["country_of_origin"] . "<br>";
        $d .= $customOfOriginInfo;
        $customDescription['description'][] = [
          'title' => 'Made In',
          'content' => $parent_data["country_of_origin"]
        ];
      }
      //if(!empty($parent_data[productwarranty])) $d.= "<b>Product Warranty:</b> $parent_data[productwarranty]<br>";

      if (preg_match('/\bwool\b/', strtolower($this->materials)) && $parent_data["type"]["name"] == "Rugs") {
        $sheddingInfo = '<br>
    <p>
        <b>Shedding</b>: All wool rugs will shed. Shedding will subside over time, depending on traffic and wear. It typically takes 20-25 vacuums, at a minimum, to curtail shedding. Some will shed for the lifetime of the rug.</p>';
        $d .= $sheddingInfo;
        $customDescription['description'][] = [
          'title' => 'Shedding',
          'content' => 'All wool rugs will shed. Shedding will subside over time, depending on traffic and wear. It typically takes 20-25 vacuums, at a minimum, to curtail shedding. Some will shed for the lifetime of the rug.'
        ];
      }

      $d .= '<br>
    <p>
        <b>Please Note</b>: The images we display have the most accurate color possible. Due to differences in computer monitors, we cannot be responsible for variations in color between the actual product and your screen. Please be advised that in some cases patterns and colors may vary according to size. Lengths and widths may vary from the published dimensions. We do our best to provide you with an accurate measurement, but please be advised that some variation exists and this is not a manufacturing defect.
        </p>';


      $d .= '</div>';
      $tabs[] = array("id" => "features", "title" => "Features", "content" => $d);


      $pinfo = '';
      $customDescription['product_care'] = '';
      //if(!empty($parent_data[brandcopy])) $pinfo.= "<p>$parent_data[brandcopy]</p>";

      //if(!empty($parent_data[romancecopy])) $pinfo.= "<p>$parent_data[romancecopy]</p>";

      if (!empty($parent_data["productcare"])) {
        $productCareInfo = "<p><b>Product Care:</b> " . $parent_data["productcare"] . "</p>";
        $pinfo .= $productCareInfo;
        $customDescription['product_care'] = $parent_data["productcare"];
      }

      if (strlen($pinfo) > 1) {
        $pinfofinal = '<div style="font-size:14px;">' . $pinfo . '</div>';
        $tabs[] = array("id" => "product-info", "title" => "Product Care", "content" => $pinfofinal);
      }


      $ret = '
            <div style="font-size:14px;">

            <p>
            <b>Returns:</b> Returns accepted within 14 days of delivery. Return shipping is the sole responsibility of the buyer.
        </p><p>
        <b>Cancellations:</b> Online orders can be modified or canceled within 24 hours of placing the order. Because we may begin processing your order immediately, it may not be possible to modify or cancel existing orders even within 24 hours of placement.
        </p><p>
        Vacuum regularly as new wool rugs can shed yarn fibers for up to three months. Avoid direct and continuous exposure to sunlight. Do not pull loose ends, clip them with scissors to remove. Clean liquid spills immediately by blotting with cloth or sponge. For hard to remove stains, professional rug cleaning is recommended.
        </p>

        </div>';
      $tabs[] = array("id" => "returns", "title" => "Returns", "content" => $ret);

      $html = $cv . '<ul class="tabs" data-tab>';
      foreach ($tabs as $tab) {
        $html .= '<li class="tab';

        if ($tab["title"] == 'Features') $html .= ' is-active ';
        $html .= '">
                <a class="tab-title" href="#tab-' . $tab["id"] . '">' . $tab["title"] . '</a>
                </li>';
      }

      $html .= '
    </ul>
    <div class="tabs-contents">';

      foreach ($tabs as $tab) {
        $html .= '<div class="tab-content';
        if ($tab["title"] == 'Features') $html .= ' is-active ';
        $html .= '" id="tab-' . $tab["id"] . '">' . $tab["content"] . '</div>';
      }


      $html .= '
    </div>';
      //$html.='<script type="text/javascript"> var productId=' . $product_id . ';</script>';
      if (!$get_custom_description) {
        if (count($tabs) > 0) {
          return $html;
        } else {
          return '';
        }
      } else {
        return $customDescription;
      }
    } else { // !isset($parent_data["parentname"]
      return '';
    }
  }

  private function get_tags($parent_data)
  {
    $this->get_category($parent_data);
    $this->get_materials($parent_data);
    $this->get_material_master();
    $this->material_master;
    $this->get_styles($parent_data);
    $this->get_sizes($parent_data);
    $this->get_shapes($parent_data);
    $this->get_prices($parent_data);

    $this->get_colors($parent_data);
    $this->get_color_master();
    $this->get_custom_categories($parent_data);

    //TAGS



    $material_tags = null;
    $style_tags = null;
    $size_tags = null;
    $shape_tags = null;
    $price_tags = null;
    $category_tags = null;
    $color_tags = null;
    $custom_categories_tags = null;
    $material_master_tags = null;
    $color_master_tags = null;

    (!empty($this->materials)) ? $materials = explode(',', $this->materials) : null;

    if (isset($materials) && count($materials) > 0) {
      $material_tags = collect($materials)->map(function ($item, $key) {

        return 'Material__' . ucfirst($item);
      });
    }


    (!empty($this->material_master)) ? $material_master = explode(',', $this->material_master) : null;
    if (isset($material_master) && count($material_master) > 0) {

      $material_master_tags = collect($material_master)->map(function ($item, $key) {
        return 'MaterialMaster::' . ucfirst($item);
      })->toArray();
    }



    (!empty($this->styles)) ? $styles = explode(',', $this->styles) : null;

    if (isset($styles) && count($styles) > 0) {
      $style_tags = collect($styles)->map(function ($item, $key) {
        return 'Style__' . ucfirst($item);
      });
    }


    (!empty($this->sizes)) ? $sizes = explode(',', $this->sizes) : null;

    if (isset($sizes) && count($sizes) > 0) {
      $size_tags = collect($sizes)->map(function ($item, $key) {
        return 'Size__' . ucfirst($item);
      });
    }



    (!empty($this->shapes)) ? $shapes = explode(',', $this->shapes) : null;

    if (isset($shapes) && count($shapes) > 0) {
      $shape_tags = collect($shapes)->map(function ($item, $key) {
        return 'Shape__' . ucfirst($item);
      });
    }


    (!empty($this->prices)) ? $prices = explode(',', $this->prices) : null;

    if (isset($prices) && count($prices) > 0) {
      $price_tags = collect($prices)->map(function ($item, $key) {
        return 'Price__' . ucfirst($item);
      });
    }



    (!empty($this->category)) ? $category = explode(',', $this->category) : null;

    if (isset($category) && count($category) > 0) {
      $category_tags = collect($category)->map(function ($item, $key) {
        return 'Category__' . ucfirst($item);
      });
    }


    (!empty($this->colors)) ? $colors = explode(',', $this->colors) : null;

    if (isset($colors) && count($colors) > 0) {
      $color_tags = collect($colors)->map(function ($item, $key) {
        return 'Color__' . ucfirst($item);
      });
    }


    (!empty($this->colors_master)) ? $colors_master = explode(',', $this->colors_master) : null;

    if (isset($colors_master) && count($colors_master) > 0) {
      $color_master_tags = collect($colors_master)->map(function ($item, $key) {
        return 'ColorMaster::' . ucfirst($item);
      });
    }

    $custom_categories_tags = null;
    $custom_categories = $this->custom_categories;
    if (count($custom_categories) > 0) {
      $custom_categories_tags = collect($custom_categories)->map(function ($item, $key) {
        return 'Custom Category__' . $item;
      });
    }
    if ($parent_data["real_collection"] == "One of a Kind") {
      $custom_categories_tags[] = 'Custom Category__One of a Kind';
    }
    if ($parent_data["pile"] == "Plush Pile" && !in_array($parent_data["real_design"], Constants::$PLUSH_PILE_NOT_SHAGS)) {
      $style_tags[] = 'Style__Shags';
    }


    $tags = collect()->push($material_tags, $material_master_tags, $style_tags, $size_tags, $shape_tags, $price_tags, $category_tags, $color_tags, $color_master_tags, $custom_categories_tags)->flatten()->toArray();


    return implode(",", $tags);
  }
  private function get_metafields($product)
  {
    
    $this->get_materials($product);
    $this->get_styles($product);
    $this->get_category($product);
    $this->get_metakeywords($product);
    $this->get_colors($product);
    $desc = $this->get_body_html($product, true);
    $desc = isset($desc['description']) ? $desc['description'] : '';
    

    $product_id = isset($product[$this->shopify_data]['shopify_product_id']) ? $product[$this->shopify_data]['shopify_product_id'] : null;
    $bc_product_id = isset($product[$this->shopify_data]['bc_product_id']) ? $product[$this->shopify_data]['bc_product_id'] : null;

    $custom_data = [
      'real_design' => $product['real_design'],
      'group_id' => $product['group_id'],
      'this_design' => $product['design'],
      'product_id'  => $product_id,
      'category' => $this->category,
      'colors' => $this->colors,
      'materials' => $this->materials,
      'brand' => $product['brand'],
      'has_fringe' => $product['variants'][0]['fringe'],
      'bc_product_id' => $bc_product_id,
      'washable' => $product['washable'],
      'best_seller' => $product['top_seller'],
      'new_arrival' => $product['new_arrival'],
      'featured' => $product['featured'],
      'clearance' => $product['clearance'],
      'size_variant_count' => $product['variants_count'],
      'color_variants' => $product['color_variants'],
      'description' => $desc,
      'product_care' => '',
      'meta_keywords' => $this->meta_keywords
    ];

    $metafield = [[
      'id' => $product['metafield_graphql_api_id'], 'namespace' => 'custom_data', 'key' => 'custom_data2', 'valueType' => 'JSON_STRING',
      'value' => json_encode($custom_data)
    ]];
    $this->metafields = $metafield;

    return $metafield;
  }
  private function get_new_metafields($product)
  {
    $this->get_materials($product);
    $this->get_styles($product);
    $this->get_category($product);
    $this->get_metakeywords($product);
    $this->get_colors($product);
    $desc = $this->get_body_html($product, true)['description'];

    $product_id = isset($product[$this->shopify_data]['shopify_product_id']) ? $product[$this->shopify_data]['shopify_product_id'] : null;
    $bc_product_id = isset($product[$this->shopify_data]['bc_product_id']) ? $product[$this->shopify_data]['bc_product_id'] : null;

    $keywords = collect($this->meta_keywords)->map(function ($item) {
      return ['keyword' => $item];
    })->toJson();
    $style = collect(explode(',', $this->styles))->map(function ($item) {
      return ['style' => $item];
    })->toJson();
    $material
      = collect(explode(',', $this->materials))->map(function ($item) {
        return ['material' => $item];
      })->toJson();
    $other_colors
      = collect(explode(',', $this->colors))->map(function ($item) {
        return ['color' => $item];
      })->toJson();
    $colors = explode(',', $this->colors);



    $metafields = [

      [
        'namespace' => 'global', 'key' => 'real_design', 'valueType' => 'STRING',
        'value' => $product['real_design'], 'description' => 'Real Design'
      ],
      [
        'namespace' => 'global', 'key' => 'design', 'valueType' => 'STRING',
        'value' => $product['design'], 'description' => 'Design'
      ],
      [
        'namespace' => 'global', 'key' => 'group_id', 'valueType' => 'STRING',
        'value' => $product['group_id'], 'description' => 'Group'
      ],
      [
        'namespace' => 'global', 'key' => 'real_collection', 'valueType' => 'STRING',
        'value' => $product['real_collection'], 'description' => 'Real Collection'
      ],
      [
        'namespace' => 'global', 'key' => 'collection', 'valueType' => 'STRING',
        'value' => $product['collection'], 'description' => 'Collection'
      ],
      [
        'namespace' => 'global', 'key' => 'type', 'valueType' => 'STRING',
        'value' => $this->get_type($product), 'description' => 'Type'
      ],

      [
        'namespace' => 'global', 'key' => 'brand', 'valueType' => 'STRING',
        'value' => $product['brand'], 'description' => 'Brand'
      ],
      [
        'namespace' => 'global', 'key' => 'designer', 'valueType' => 'STRING',
        'value' => $product['designer'], 'description' => 'Designer'
      ],
      [
        'namespace' => 'global', 'key' => 'country_of_origin', 'valueType' => 'STRING',
        'value' => $product['country_of_origin'], 'description' => 'Country of Origin'
      ],
      [
        'namespace' => 'global', 'key' => 'pile', 'valueType' => 'STRING',
        'value' => $product['pile'], 'description' => 'Pile'
      ],
      [
        'namespace' => 'global', 'key' => 'keywords', 'valueType' => 'JSON_STRING',
        'value' => $keywords, 'description' => 'Keywords'
      ],
      [
        'namespace' => 'global', 'key' => 'style', 'valueType' => 'JSON_STRING',
        'value' => $style, 'description' => 'Style'
      ],
      [
        'namespace' => 'global', 'key' => 'material', 'valueType' => 'JSON_STRING',
        'value' => $material, 'description' => 'Material'
      ],
      [
        'namespace' => 'global', 'key' => 'main_color', 'valueType' => 'STRING',
        'value' => $colors[0], 'description' => 'Main Color'
      ],
      [
        'namespace' => 'global', 'key' => 'colors', 'valueType' => 'JSON_STRING',
        'value' => $other_colors, 'description' => 'Color'
      ],
      [
        'namespace' => 'global', 'key' => 'construction', 'valueType' => 'STRING',
        'value' => $product['construction'], 'description' => 'Construction'
      ],
      [
        'namespace' => 'global', 'key' => 'fragile', 'valueType' => 'BOOLEAN',
        'value' => $product['fragile'] ? "true" : "false", 'description' => 'Fragile'
      ],
      [
        'namespace' => 'global', 'key' => 'clearance', 'valueType' => 'BOOLEAN',
        'value' => $product['clearance'] ? "true" : "false", 'description' => 'Clearance'
      ],
      [
        'namespace' => 'global', 'key' => 'assembly_required', 'valueType' => 'BOOLEAN',
        'value' => $product['assembly_required'] ? "true" : "false", 'description' => 'Assembly Required'
      ],
      [
        'namespace' => 'global', 'key' => 'outdoor_safe', 'valueType' => 'BOOLEAN',
        'value' => $product['outdoor_safe'] ? "true" : "false", 'description' => 'Outdoor Safe'
      ],
      [
        'namespace' => 'global', 'key' => 'washable', 'valueType' => 'BOOLEAN',
        'value' => $product['washable'] ? "true" : "false", 'description' => 'Washable'
      ],
      [
        'namespace' => 'global', 'key' => 'top_seller', 'valueType' => 'BOOLEAN',
        'value' => $product['top_seller'] ? "true" : "false", 'description' => 'Top Seller'
      ],
      [
        'namespace' => 'global', 'key' => 'recycled', 'valueType' => 'BOOLEAN',
        'value' => $product['recycled'] ? "true" : "false", 'description' => 'Recycled'
      ],
      [
        'namespace' => 'global', 'key' => 'new_arrival', 'valueType' => 'BOOLEAN',
        'value' => $product['new_arrival'] ? "true" : "false", 'description' => 'New Arrival'
      ],
      [
        'namespace' => 'global', 'key' => 'featured', 'valueType' => 'BOOLEAN',
        'value' => $product['featured'] ? "true" : "false", 'description' => 'Featured'
      ],
      [
        'namespace' => 'global', 'key' => 'description', 'valueType' => 'STRING',
        'value' => $product['description'], 'description' => 'Description'
      ],
      [
        'namespace' => 'global', 'key' => 'status', 'valueType' => 'STRING', 'description' => 'Status'
      ],


    ];

    $this->metafields = $metafields;

    return $metafields;
  }
  private function get_metakeywords($product)
  {
    $this->get_materials($product);
    $this->get_colors($product);
    $productType = 'Area Rug';


    $colors = explode(',', $this->colors);
    $materials = explode(',', $this->materials);

    $k = array();
    $k[] = $product["collection"];
    $k[] = $product["design"];
    foreach ($colors as $color) {
      $k[] = $color . ' ' . $productType;
    }
    if (!empty($product["construction"])) $k[] = $product["construction"] . ' ' . $productType;
    foreach ($materials as $material) {
      $k[] = $material . ' ' . $productType;
    }

    if (!empty($product["backing"])) $k[] = $product["backing"] . ' ' . $productType;
    if (!empty($product["pile"])) $k[] = $product["pile"] . ' ' . $productType;
    if (!empty($product["style"])) $k[] = $product["style"];


    $this->meta_keywords = $k;
    return $this;
  }
  private function get_taxcode($product)
  {
    $tax_code = $product['type']['tax_code'];
    return $tax_code;
  }

  private function get_variants($product, $with_metafield = false)
  {
    $variants  = $product['variants'];
    $prod_type = $this->get_type($product);

    $variants_arr = [];
    foreach ($variants as $variant) {
      $price = !isset($variant['price']['price']) ? null : $variant['price']['price'] / 100;
      $sku = $variant['sku'];
      $skus_last = substr($sku, -1);
      $real_sku = $variant['real_sku'];
      $suffix = '';
      $explodedSize = explode(' ', trim($variant['size']));
      $uniqueSizeDimensions = array_unique($explodedSize);
      $compareAtPrice = $price * 0.4;

      // check if there is only one unique dimention. Ex: 10 x 10 should be 10.
      if (count($uniqueSizeDimensions) == 2) {
        // if dimensions without x is only one, unset x.
        $xPosition = array_search('x', $uniqueSizeDimensions);
        if ($xPosition) {
          unset($uniqueSizeDimensions[$xPosition]);
        }
      }

      // if only one unique dimension use it, if not use size as it is.
      $sizeDimensions = (count($uniqueSizeDimensions) == 1) ? $uniqueSizeDimensions : $explodedSize;

      // add shape to variant size
      $variant["size"] = implode(' ', $sizeDimensions);

      // if shape is not in size, add shape too.
      if ($variant["shape"] != "") {
        if (!strpos($variant["size"], $variant["shape"])) {
          $variant["size"] .= " " . $variant["shape"];
        }
      }

      if ($prod_type == 'Pillow Kit' || $prod_type == 'Pillows' || $prod_type == 'Pillow Cover') {
        $suffix .= ($skus_last == 'P') ? ' with Polyester Insert' : null;
        $suffix .= ($skus_last == 'D') ? ' with Down Insert' : null;
        $suffix .= ($prod_type == 'Pillow Cover') ? ' Pillow Cover' : null;
      }
      $options = [$variant['size'] . $suffix];
      $taxable = true;
      $taxCode = $this->get_taxcode($product);
      $requires_shipping = true;
      $barcode = (string) $variant['upc'][0]['upc'];
      $weight = (float) number_format($variant['shippings']['weight_lbs'], 2);
      $imageSrc = '';
      if (!empty($product['images'])) {
        foreach ($product['images'] as $image) {
          if ($image->real_sku == $real_sku) {
            $imageSrc = $image->image_path_compressed;
          }
        }
      }

      $single_variant = [
        'price' => $price,
        'sku' => $sku,
        'options' => $options,
        'taxable' => $taxable,
        'taxCode' => $taxCode,
        'requiresShipping' => $requires_shipping,
        'imageSrc' => $imageSrc,
        'barcode' => $barcode,
        'weight' => $weight,
        'weightUnit' => 'POUNDS',
        'inventoryItem' => ['tracked' => true],
        'compareAtPrice' => $compareAtPrice
      ];

      if ($with_metafield) {
        $single_variant['metafields'] = $this->get_variant_metafields($product, $variant);
        $narvar_metafields = $this->get_narvar_metafields($product, $variant);
        $single_variant['metafields'] = array_merge($single_variant['metafields'], $narvar_metafields);
      }
      
      
      


      $variants_arr[] = $single_variant;
    }

    return $variants_arr;
  }


  private function get_vendor()
  {
    return 'Hauteloom';
  }

  private function get_variant_metafields($product, $variant)
  {
    $this->get_materials($product);
    $this->get_styles($product);
    $this->get_category($product);
    $this->get_metakeywords($product);
    $this->get_colors($product);
    $desc = $this->get_body_html($product, true);
    $desc = isset($desc['description']) ? $desc['description'] : '';

    $product_id = isset($product[$this->shopify_data]['shopify_product_id']) ? $product[$this->shopify_data]['shopify_product_id'] : null;


    $keywords = collect($this->meta_keywords)->map(function ($item) {
      return ['keyword' => $item];
    })->toJson();
    $style = collect(explode(',', $this->styles))->map(function ($item) {
      return ['style' => $item];
    })->toJson();
    $material
      = collect(explode(',', $this->materials))->map(function ($item) {
        return ['material' => $item];
      })->toJson();
    $other_colors
      = collect(explode(',', $this->colors))->map(function ($item) {
        return ['color' => $item];
      })->toJson();
    $colors = explode(',', $this->colors);
    $size = preg_replace("/((?!x)[a-z]|[A-Z])/", "", $variant['size']);
    $size = str_replace(' ', '', $size);

    $metafields = [
      [
        'namespace' => 'global', 'key' => 'real_sku', 'valueType' => 'STRING',
        'value' => $variant['real_sku'], 'description' => 'Real Sku'
      ],
      [
        'namespace' => 'global', 'key' => 'sku', 'valueType' => 'STRING',
        'value' => $variant['sku'], 'description' => 'Sku'
      ],
      [
        'namespace' => 'global', 'key' => 'name', 'valueType' => 'STRING',
        'value' => $this->get_title($product), 'description' => 'Name'
      ],
      [
        'namespace' => 'global', 'key' => 'shape', 'valueType' => 'STRING',
        'value' => $variant['shape'], 'description' => 'Shape'
      ],
      [
        'namespace' => 'global', 'key' => 'has_fringe', 'valueType' => 'BOOLEAN',
        'value' => $variant['fringe'] ? "true" : "false", 'description' => 'Fringe'
      ],
      [
        'namespace' => 'global', 'key' => 'upc', 'valueType' => 'STRING',
        'value' => (string) $variant['upc'][0]['upc'], 'description' => 'UPC'
      ],
      [
        'namespace' => 'global', 'key' => 'real_upc', 'valueType' => 'STRING',
        'value' => (string) $variant['upc'][1]['upc'], 'description' => 'Real UPC'
      ],
      [
        'namespace' => 'global', 'key' => 'weight_lbs', 'valueType' => 'STRING',
        'value' => $variant['shippings']['weight_lbs'], 'description' => 'Weight'
      ],
      [
        'namespace' => 'global', 'key' => 'length_in', 'valueType' => 'STRING',
        'value' => $variant['shippings']['length_in'], 'description' => 'Length'
      ],
      [
        'namespace' => 'global', 'key' => 'width_in', 'valueType' => 'STRING',
        'value' => $variant['shippings']['width_in'], 'description' => 'Width'
      ],
      [
        'namespace' => 'global', 'key' => 'height_in', 'valueType' => 'STRING',
        'value' => $variant['shippings']['height_in'], 'description' => 'Height'
      ],
      [
        'namespace' => 'global', 'key' => 'shipping_weight_lbs', 'valueType' => 'STRING',
        'value' => $variant['shippings']['shipping_weight_lbs'], 'description' => 'Shipping Weight'
      ],
      [
        'namespace' => 'global', 'key' => 'shipping_length_in', 'valueType' => 'STRING',
        'value' => $variant['shippings']['shipping_length_in'], 'description' => 'Shipping Length'
      ],
      [
        'namespace' => 'global', 'key' => 'shipping_width_in', 'valueType' => 'STRING',
        'value' => $variant['shippings']['shipping_width_in'], 'description' => 'Shipping Width'
      ],
      [
        'namespace' => 'global', 'key' => 'shipping_height_in', 'valueType' => 'STRING',
        'value' => $variant['shippings']['shipping_height_in'], 'description' => 'Shipping Height'
      ],
      [
        'namespace' => 'global', 'key' => 'fulfillment_latency', 'valueType' => 'INTEGER',
        'value' => (string)$variant['inventory']['fulfillment_latency'], 'description' => 'Fulfillment Latency'
      ],
      [
        'namespace' => 'global', 'key' => 'size', 'valueType' => 'STRING',
        'value' => $size, 'description' => 'Size'
      ],
    ];



    return $metafields;
  }



  private function get_images($product)
  {
    $images = $product['images'];
    $images_array = [];
    if (!empty($images)) {
      foreach ($images as $image) {
        $image_path_compressed = $image->image_path_compressed;
        $images_array[]['src'] = $image_path_compressed;
      }
    }
    return $images_array;
  }


  private function get_narvar_metafields($product,$variant)
  {
    $shipping_length = $variant['shippings']['shipping_length_in'];
    $product_type = $this->get_type($product);
    $shipping_group = '';
    if($product_type == 'Rugs'){

      if($shipping_length >= 0 && $shipping_length <= 29){
        $shipping_group = 'very_small';
      }
      if ($shipping_length >= 30 && $shipping_length <= 47) {
        $shipping_group = 'small';
      }
      if ($shipping_length >= 48 && $shipping_length <= 59) {
        $shipping_group = 'small_plus';
      }
      if ($shipping_length >= 60 && $shipping_length <= 71) {
        $shipping_group = 'medium';
      }
      if ($shipping_length >= 72 && $shipping_length <= 83) {
        $shipping_group = 'medium_plus';
      }
      if ($shipping_length >= 84 && $shipping_length <= 95) {
        $shipping_group = 'large';
      }
      if ($shipping_length >= 96 && $shipping_length <= 105) {
        $shipping_group = 'large_plus';
      }
      if ($shipping_length >= 106) {
        $shipping_group = 'oversize ';
      }



    }
    if($product_type != 'Rugs' ){
      $girth = 2 * ($variant['shippings']['shipping_width_in'] + $variant['shippings']['shipping_height_in']) +  ($variant['shippings']['shipping_length_in']);
      if($girth >= 0 && $girth <= 44){
        $shipping_group = 'very_small';
      }
      if ($girth >= 45 && $girth <= 67) {
        $shipping_group = 'small';
      }
      
      if ($girth >= 68 && $girth <= 89) {
        $shipping_group = 'small_plus';
      }
      
      if ($girth >= 90 && $girth <= 104) {
        $shipping_group = 'medium';
      }
      if ($girth >= 105 && $girth <= 114) {
        $shipping_group = 'medium_plus';
      }
      if ($girth >= 115 && $girth <= 129) {
        $shipping_group = 'large';      
      }
      if ($girth >= 130 && $girth <= 164) {
        $shipping_group = 'large_plus';      
      }
      if ($girth >= 165 ) {
        $shipping_group = 'oversize ';      
      }
    }

    $length = number_format($variant['shippings']['shipping_length_in'],2);
    $width = number_format($variant['shippings']['shipping_width_in'],2);
    $height = number_format($variant['shippings']['shipping_height_in'],2);

    $metafields = [
      [
        'namespace' => 'global', 'key' => 'SHIPPING_GROUPS', 'valueType' => 'STRING',
        'value' => $shipping_group, 'description' => 'Shipping Groups'
      ],
      [
        'namespace' => 'global', 'key' => 'WIDTH', 'valueType' => 'STRING',
        'value' => $width, 'description' => 'Width Description'
      ],
      [
        'namespace' => 'global', 'key' => 'HEIGHT', 'valueType' => 'STRING',
        'value' => $height, 'description' => 'Height Description'
      ],

    ];

    return $metafields;
  }


  public function run()
  {
    if (BrugsMigrationTool::$settings['OPERATION']->is(Operations::Update)) {
      foreach ($this->products as $product) {
        
        $skip = false;
        $variants = $this->get_variants($product);
        $images = $this->get_images($product);
/*
        if (count($images) == 0) {
          $skip = true;
        }

        foreach ($variants as $variant) {

          if ($variant['price'] == 0 || $variant['price'] == '' || $variant['price'] == null) {
            $skip = true;
          }
        }
*/
  


        if ($skip) {
          continue;
        }

        $tags = $this->get_tags($product);

        $this->result['input']['id'] = $product['graphql_id'];

        if (in_array_r(Fields::Title()->key, $this->fields) || in_array_r(Fields::All()->key, $this->fields)) 
        {
          $this->result['input']['title'] = $this->get_title($product);
        }

        if (in_array_r(Fields::DescriptionHtml()->key, $this->fields) || in_array_r(Fields::All()->key, $this->fields)) 
        {
          $this->result['input']['descriptionHtml'] = $this->get_body_html($product);
        }


        
        if (in_array_r(Fields::Metafields()->key, $this->fields) || in_array_r(Fields::All()->key, $this->fields)) {
          $this->result['input']['metafields'] = $this->get_metafields($product);
        }
        /*
        if (in_array_r(Fields::NewMetafields()->key, $this->fields) ) {
          $this->result['input']['metafields'] = $this->get_new_metafields($product);
        }
        */

        if (in_array_r(Fields::Tags()->key, $this->fields)|| in_array_r(Fields::All()->key, $this->fields))
        {
          $this->result['input']['tags'] = $this->get_tags($product);
        }


        if (in_array_r(Fields::Variants()->key, $this->fields) ) {
          $this->result['input']['variants'] = $this->get_variants($product);
        }

        if (in_array_r(Fields::VariantMetafields()->key, $this->fields) || in_array_r(Fields::All()->key, $this->fields)) 
        {
          $this->result['input']['variants'] = $this->get_variants($product, true);
        }

        
        if (in_array_r(Fields::Images()->key, $this->fields) || in_array_r(Fields::All()->key, $this->fields)) 
        {
          $this->result['input']['images'] = $this->get_images($product);
        }

        $this->result['input']['productType'] = $this->get_type($product);
        $this->result['input']['vendor'] = $this->get_vendor($product);

        $this->results[] = $this->result;
      }
    }


    //OPERATION CREATE
    if (BrugsMigrationTool::$settings['OPERATION']->is(Operations::Create)) 
    {
      foreach ($this->products as $product) 
      {
        $skip = false;
        $variants = $this->get_variants($product);
        $images = $this->get_images($product);

        /*
        if (count($images) == 0) 
        {
          $skip = true;
        }
        foreach ($variants as $variant) 
        {
          if ($variant['price'] == 0 || $variant['price'] == '' || $variant['price'] == null) 
          {
            $skip = true;
          }
        }


        if ($skip) 
        {
          continue;
        }
        */
        $this->result['input']['title'] = $this->get_title($product);
        $this->result['input']['vendor'] = $this->get_vendor();
        $this->result['input']['variants'] = $this->get_variants($product,true);
        $this->result['input']['metafields'] = $this->get_metafields($product);
        $this->result['input']['tags'] = $this->get_tags($product);
        $this->result['input']['images'] = $this->get_images($product);
        $this->result['input']['descriptionHtml'] = $this->get_body_html($product);
        $this->result['input']['status'] = 'ACTIVE';
        $this->result['input']['published'] = true;
        $this->result['input']['productType'] = $this->get_type($product);
        $this->result['input']['vendor'] = $this->get_vendor($product);
        $this->results[] = $this->result;
      }
    }



    return $this->results;
  }
}
