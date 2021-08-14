<?php

namespace Erenkucukersoftware\BrugsMigrationTool\Facades\Product\Components;



use Illuminate\Support\Facades\Log;
use Erenkucukersoftware\BrugsMigrationTool\BrugsMigrationTool;
use Erenkucukersoftware\BrugsMigrationTool\Constants;
use Erenkucukersoftware\BrugsMigrationTool\Facades\Shopify\ShopifyFlowFacade;

ini_set('max_execution_time', 0);


class ProductFields{
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
  
  

  public function __construct($products){
    $this->fields = BrugsMigrationTool::$settings['FIELDS'];
    $this->products = $products;
    $this->shopify_data = BrugsMigrationTool::$settings['IS_DEV'] ? 'shopify_data_dev':'shopify_data';
  }




  //SUB ENTITY GETS
  private function getMaterialForCategory(){

  }
  private function get_description($product){
    $description = $product['description'];

  }

  private function get_material_master(){
    $category = $this->category;
    $this->material_master = null;

    $materials = explode(',',$this->materials);
    $material_categories = collect(Constants::$SPECIAL_MATERIAL_CATEGORIES);

    $materials_master_arr = [];
    foreach ($materials as $material) {
      
      $master_material = $material_categories->first(function ($value, $key) use ($material) {
        return stripos($material, $value);
      });

      empty($master_material) ? null : $materials_master_arr[] = $master_material;
    }

    if(is_array($materials_master_arr) && count($materials_master_arr) > 0){
    $this->material_master = implode(",", $materials_master_arr);
    }
    return $this;

  }

  private function get_color_master(){
    $this->colors_master = null;
    $colors = explode(',', $this->colors);
    $color_categories = Constants::$SPECIAL_COLOR_CATEGORIES;
    
    $colors_master_arr = [];
    foreach($colors as $color){
      if( array_key_exists($color,$color_categories) && !empty($color_categories[$color]) ){
        $colors_master_arr[] = $color_categories[$color];
      }
    }
    $colors_master_arr = collect($colors_master_arr)->flatten()->unique()->toArray();
    

    if(is_array($colors_master_arr) && count($colors_master_arr) > 0){
      $this->colors_master = implode(',', $colors_master_arr);
    }
    
    return $this;


  }
  private function get_materials($product){
    $this->materials = null;
  
    $product = collect($product['custom_category']);
   
    $materials = $product->filter(function ($value, $key) {
      
      return $value != '' && $value["custom_category_id"] == 5;
    })->pluck("value");

    $materials = $materials->filter()->toArray();
    
    if(is_array($materials) && !in_array('',$materials) && !in_array(null, $materials) && count($materials) > 0){
    $materials_string = implode(",", $materials);
    
    $this->materials = $materials_string;
    }
      
    
    return $this;
  }

  private function get_styles($product){
    $this->styles = null;
    $product = collect($product['custom_category']);
    $styles = $product->filter(function ($value, $key) {
      return $value["custom_category_id"] == 4;
    })->pluck("value")->toArray();

    if(is_array($styles) && count($styles) > 0){
    $styles_string = implode(",", $styles);
    $this->styles = $styles_string;
    }
    return $this;
  }
  private function get_sizes($product){
    $this->sizes = null;
    $product = collect($product['custom_category']);
    $sizes = $product->filter(function ($value, $key) {
      return $value["custom_category_id"] == 1;
    })->pluck("value")->toArray();

    if(is_array($sizes) && count($sizes) > 0){
    $sizes_string = implode(",", $sizes);
    $this->sizes = $sizes_string;
    }
    return $this;
  }
  private function get_shapes($product){
    $this->shapes = null;
    $product = collect($product['custom_category']);
    $shapes = $product->filter(function ($value, $key) {
      return $value["custom_category_id"] == 2;
    })->pluck("value")->toArray();

    if(is_array($shapes) && count($shapes) > 0){
    $shapes_string = implode(",", $shapes);
    $this->shapes = $shapes_string;
    }
    return $this;
  }
  private function get_prices($product){
    $this->prices = null;
    $product = collect($product['custom_category']);
    $prices = $product->filter(function ($value, $key) {
      return $value["custom_category_id"] == 3;
    })->pluck("value")->toArray();

    if(is_array($prices) && count($prices) > 0){
    $prices_string = implode(",", $prices);
    $this->prices = $prices_string;
    }
    return $this;
  }

  private function get_category($product){
    $this->category = null;
    $this->category = $product["type"]["name"];
    return $this;
  }

  private function get_colors($product){
    $this->colors = null;
    $product = collect($product['colors']);
    $colors = $product->map(function ($value) {
      return $value["color_name"]["name"];
    })->toArray();

    if(is_array($colors) && count($colors) > 0){

    $colors_string = implode(",", $colors);
    $this->colors = $colors_string;

    }
    return $this;

  }

  private function get_custom_categories($product){
    $this->custom_categories = null;
    $custom_categories = [];
    if($product['clearance']){
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
    if($product['outdoor_safe']){
      $custom_categories[] = 'Outdoor';
    }
    
    return $this->custom_categories = $custom_categories;


  }

  


  //MAIN SHOPIFY ENTITY GETS

  private function get_title($product){
    return $product['collection'] .' '. $product['subtype']['name'];
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
        foreach($parent_data['colors'] as $color){
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
                'title' => 'Machine washable',
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

  private function get_tags($parent_data){
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

    (!empty($this->materials)) ? $materials = explode(',',$this->materials) : null;
    
    if(isset($materials) && count($materials) > 0){
    $material_tags = collect($materials)->map(function ($item, $key) {
      
      return 'Material__'.ucfirst($item);
    });
    }

    
    (!empty($this->material_master)) ? $material_master = explode(',', $this->material_master):null;
    if (isset($material_master) && count($material_master) > 0) {
    
    $material_master_tags = collect($material_master)->map(function ($item, $key) {
      return 'MaterialMaster::' . ucfirst($item);
    })->toArray();
    }



    (!empty($this->styles)) ? $styles = explode(',',$this->styles):null;

    if (isset($styles) && count($styles) > 0) {
    $style_tags =collect($styles)->map(function ($item, $key) {
      return 'Style__'. ucfirst($item);
    });
    }


   (!empty($this->sizes)) ? $sizes = explode(',', $this->sizes):null;

    if (isset($sizes) && count($sizes) > 0) {
    $size_tags = collect($sizes)->map(function ($item, $key) {
      return 'Size__' . ucfirst($item);
    });
    }



    (!empty($this->shapes)) ? $shapes = explode(',', $this->shapes):null;

    if (isset($shapes) && count($shapes) > 0) {
    $shape_tags = collect($shapes)->map(function ($item, $key) {
      return 'Shape__' . ucfirst($item);
    });
    }


    (!empty($this->prices)) ? $prices = explode(',', $this->prices):null;

    if (isset($prices) && count($prices) > 0) {
    $price_tags = collect($prices)->map(function ($item, $key) {
      return 'Price__' . ucfirst($item);
    });
    }



    (!empty($this->category)) ? $category = explode(',', $this->category):null;

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
    if(count($custom_categories) > 0){
      $custom_categories_tags = collect($custom_categories)->map(function ($item, $key) {
        return 'Custom Category__'.$item;
      });
    }
    if($parent_data["real_collection"] == "One of a Kind"){
      $custom_categories_tags[] = 'Custom Category__One of a Kind';
    }
    

    $tags = collect()->push($material_tags,$material_master_tags,$style_tags,$size_tags,$shape_tags,$price_tags,$category_tags,$color_tags,$color_master_tags, $custom_categories_tags)->flatten()->toArray();


    return implode(",",$tags);


  }
  private function get_metafields($product){
    $this->get_materials($product);
    $this->get_styles($product);
    $this->get_category($product);
    $this->get_metakeywords($product);
    $this->get_colors($product);
    $desc = $this->get_body_html($product,true)['description'];

    $product_id = isset($product[$this->shopify_data]['shopify_product_id']) ? $product[$this->shopify_data]['shopify_product_id'] : null;
    $bc_product_id = isset($product[$this->shopify_data]['bc_product_id']) ? $product[$this->shopify_data]['bc_product_id'] : null;
    
    $custom_data = [
      'real_design' => $product['real_design'],
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
    
    $metafield = [['id' => $product['metafield_graphql_api_id'],'namespace' => 'custom_data','key' => 'custom_data2', 'valueType' => 'JSON_STRING',
    'value' => json_encode($custom_data)
    ]];
    $this->metafields = $metafield;

    return $metafield;

  }
  private function get_metakeywords($product){
    $this->get_materials($product);
    $this->get_colors($product);
    $productType = 'Area Rug';
   

   $colors = explode(',',$this->colors);
   $materials = explode(',',$this->materials);

    $k = array();
    $k[] = $product["collection"];
    $k[] = $product["design"];
    foreach ($colors as $color) {
    $k[] = $color . ' ' . $productType;
    }
    if (!empty($product["construction"])) $k[] = $product["construction"] . ' ' . $productType;
    foreach($materials as $material){
    $k[] = $material. ' ' . $productType;
    }

    if (!empty($product["backing"])) $k[] = $product["backing"] . ' ' . $productType;
    if (!empty($product["pile"])) $k[] = $product["pile"] . ' ' . $productType;
    if (!empty($product["style"])) $k[] = $product["style"];

  
    $this->meta_keywords = $k;
    return $this;
  }

  
  private function get_variants($product){
    $variants  = $product['variants'];
    $variants_arr = [];
    foreach($variants as $variant){
      $price = $variant['price']['price'] / 100;
      $sku = $variant['sku'];
      $real_sku = $variant['real_sku'];
      $options = [$variant['size'] .' '.$variant['shape']];
      $taxable = true;
      $requires_shipping = true;
      $barcode = (string) $variant['upc']['upc'];
      $weight = number_format($variant['shippings']['weight_lbs'], 2);
      $imageSrc= '';
      if(!empty($product['images'])){
        foreach ($product['images'] as $image) {
          if ($image->real_sku == $real_sku) {
            $imageSrc = $image->image_path_compressed;
          }
        }
      }

      

    $variants_arr[] = [
      'price' => $price,
      'sku' => $sku,
      'options' => $options,
      'taxable' => $taxable,
      'requiresShipping' => $requires_shipping,
      'imageSrc' => $imageSrc,
      'barcode' => $barcode,
      'weight' => $weight,
      'weightUnit' => 'POUNDS'
  ];
      

    }

    return $variants_arr;
  }


  private function get_vendor(){
    return 'Boutique Rugs';
  }



  private function get_images($product){
    $images = $product['images'];
    $images_array = [];
    if(!empty($images)){
    foreach($images as $image){
      $image_path_compressed = $image->image_path_compressed;
      $images_array[]['src'] = $image_path_compressed;
    }
    }
    return $images_array;

  }




  public function run(){
    
    if(BrugsMigrationTool::$settings['OPERATION'] == 'UPDATE'){

    
      foreach($this->products as $product)
      {


        /*
        foreach($product['variants'] as $variant){
          if(isset($variant['variant_graph_id'])){
          $price = $variant['price']['price'];
          $price = $price / 100;
          $variant_id = $variant['variant_graph_id'];
          $this->result['input']['id'] = $variant_id;
          $this->result['input']['price'] = $price;
          $this->results[] = $this->result;
          }
        }
        */

        //6729297035456

        $tags = $this->get_tags($product);


        //if(!empty($product['graphql_id'])){



        if (in_array('descriptionHtml', $this->fields)) {
          $this->result['input']['descriptionHtml'] = $this->get_body_html($product);
        }

    

        if(in_array('metafields', $this->fields)){
          $this->result['input']['metafields'] = $this->get_metafields($product);
        }


        $this->result['input']['id'] = $product['graphql_id'];
        if (in_array('tags', $this->fields)) {
          $this->result['input']['tags'] = $this->get_tags($product);
          Log::debug($this->result['input']['tags']);
        }
        
        $this->results[] = $this->result;
      }
      
    }
    

    //OPERATION CREATE
    if(BrugsMigrationTool::$settings['OPERATION'] == 'CREATE'){
      foreach($this->products as $product){
        $this->result['input']['title'] = $this->get_title($product);
        $this->result['input']['vendor'] = $this->get_vendor();
        $this->result['input']['variants'] = $this->get_variants($product);
        $this->result['input']['metafields'] = $this->get_metafields($product);
        $this->result['input']['tags'] = $this->get_tags($product);
        $this->result['input']['images'] = $this->get_images($product);
        $this->results[] = $this->result;

      }
    }

    //}
    
    return $this->results;
    
  }



}