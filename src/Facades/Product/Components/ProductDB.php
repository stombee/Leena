<?php

namespace Erenkucukersoftware\BrugsMigrationTool\Facades\Product\Components;

use Erenkucukersoftware\BrugsMigrationTool\Enums\{Fields, Operations, ToolStatus, CustomGroups, ApiMode};
use Erenkucukersoftware\BrugsMigrationTool\Models\{ShopifyProduct, ShopifyProductShopifyData};
use Erenkucukersoftware\BrugsMigrationTool\Facades\Shopify\ShopifyFlowFacade;
use Erenkucukersoftware\BrugsMigrationTool\BrugsMigrationTool;
use Illuminate\Support\Facades\{DB, Log};
use Illuminate\Support\Facades\Storage;
use Rs\JsonLines\JsonLines;

class ProductDB
{
  public $products;
  public $shopify_data;
  public $shopify_images;


  public function __construct()
  {      
    $this->shopify_data = BrugsMigrationTool::$settings['IS_DEV'] ? 'shopify_data_dev':'shopify_data';
  }
  public function getImages()
  {
    $images = DB::connection('mysql2')->select('SELECT * FROM br_images INNER JOIN br_image_variations on  br_images.id = br_image_id WHERE br_image_variations.image_type = \'large\' ');

    $images_arr = [];
    
    foreach($images as $image)
    {
      $images_arr[$image->real_design][] = $image;
    }
    
    Log::debug('Images retrieved');
    $this->images = $images_arr;
    return $images_arr;
  }
  public function getColorVariants()
  {
    $color_variant_count = DB::connection('mysql2')->select('SELECT count(*) as color_variant_count  FROM shopify_data AS bd, br_products AS mp WHERE bd.real_design = mp.real_design AND (bd.parent_id IS NOT NULL AND bd.parent_id != \'\')  ')[0]->color_variant_count;
    $maxAtOneTime = 2000;
    $color_variant_pages = ceil($color_variant_count / $maxAtOneTime);
    $color_variants = collect();

    for ($i = 1; $i < ($color_variant_pages + 1); $i++) 
    {
      $offset = (($i - 1) * $maxAtOneTime);
      $start = ($offset == 0 ? 0 : ($offset + 1));

      $data = DB::connection('mysql2')->select('SELECT bd.data,bd.parent_id,bd.variants_data, mp.design,mp.display,bd.shopify_product_id,bd.real_design,bd.tiny_image_url FROM shopify_data AS bd, br_products AS mp WHERE bd.real_design = mp.real_design AND (bd.parent_id IS NOT NULL AND bd.parent_id != \'\') AND display = 1  LIMIT ' . $maxAtOneTime . ' OFFSET ' . $start . ' ');


      $color_variants = $color_variants->merge($data);
    }

    $color_variants_arr = [];
    
    foreach ($color_variants as $color_variant)
    {
      
      $tiny_image = isset($this->shopify_images[$color_variant->shopify_product_id][0]) ? $this->shopify_images[$color_variant->shopify_product_id][0] : '';
      $url_replacer = '_60x60';
      $url = $tiny_image;

      $new_url = preg_replace('/(\.[^.]+)$/', sprintf('%s$1', $url_replacer), $url);

      $tiny_image = $new_url;
      
      $parent_id = $color_variant->parent_id;
      $bc_data = json_decode($color_variant->data, true);
      $bc_data = collect($bc_data)->except(['body_html', 'created_at', 'product_type', 'updated_at', 'published_at', 'template_suffix', 'published_scope', 'vendor', 'options', 'metafield_graphql_api_id'])->toArray();
      $color_variant->data = $bc_data;
      $color_variant->url = 'https://boutiquerugs.com/collections/all/products/' . $bc_data['handle'];
      $color_variant->this = false;

      $color_variants_arr[$parent_id][] = ['parent_id' => $parent_id, 'design' => $color_variant->design, 'real_design' => $color_variant->real_design, 'tiny_image_url' => $tiny_image, 'url' => $color_variant->url, 'this' => false];
    }

    Log::debug('Color Variants Retrieved');

    return $color_variants_arr;

  }
  public function getCustomTags()
  {
    $custom_tags = DB::connection('mysql2')->select('SELECT pct.real_design, ct.tag FROM br_product_custom_tags as pct INNER JOIN br_custom_tags as ct on  pct.custom_tag_id  = ct.id WHERE pct.deleted_at is NULL ');

    $custom_tags_arr = [];

    foreach ($custom_tags as $custom_tag) 
    {
      $custom_tags_arr[$custom_tag->real_design][] = ["real_design" => $custom_tag->real_design, "tag" => $custom_tag->tag];
    }

    Log::debug('Custom tags are retrieved');
    return $custom_tags_arr;
  }
  public function sortImages($images_arr)
  {
    
    foreach($images_arr as &$images)
    {
      usort($images, 'ImageSort');
    }
    
    return $images_arr;
  }
  public function getShopifyData()
  {
    return DB::connection('mysql2')->select('SELECT * FROM'.' '.$this->shopify_data);
  }
  public function productsDiff($products_arr,$products_arr2)
  {
    $products_arr_diff = [];

    foreach ($products_arr as $product)
    {
      $founded = false;
      foreach ($products_arr2 as $product2)
      {
        if($product['real_design'] == $product2->real_design)
        {
          $founded = true;
        }
      }
      if($founded == false)
      {
        $products_arr_diff[] = $product;
      }
    }
    
    return $products_arr_diff;
  }
  public function getProducts()
  {

    $images = $this->getImages();
    $images = $this->sortImages($images);
    $color_variants_arr = $this->getColorVariants();
    $custom_tags = $this->getCustomTags();
    $products = collect();
    $total_count = ShopifyProduct::where('display', '=', 1)->count();
    $maxAtOneTime = 3000;
    $pages = ceil($total_count / $maxAtOneTime);

    for ($i = 1; $i < ($pages + 1); $i++) 
    {
      $offset = (($i - 1) * $maxAtOneTime);
      $start = ($offset == 0 ? 0 : ($offset + 1));

      $data = ShopifyProduct::with(['variants.shippings', 'variants.price','variants.upc', 'variants.inventory', 'customCategory.custom_category_name', 'colors.color_name', 'type', 'subtype',  $this->shopify_data])->withCount('variants')->offset($start)->limit($maxAtOneTime);
      
      if (!empty(BrugsMigrationTool::$settings['ENTITY'][0]) && BrugsMigrationTool::$settings['ENTITY'][0] != 'All') 
      {
        foreach(BrugsMigrationTool::$settings['ENTITY'] as $index => $entity)
        {
          $index == 0 ? $data = $data->where('real_design', '=', $entity): $data = $data->orWhere('real_design', '=', $entity) ;
        }
        
      }
      
      if(!empty(BrugsMigrationTool::$settings['CUSTOM_GROUP'][0]))
      {
        foreach(BrugsMigrationTool::$settings['CUSTOM_GROUP'] as $index => $custom_group)
        {
          $index == 0 ? $data = $data->where($custom_group->key, '=', '1') : $data = $data->orWhere($custom_group->key, '=', '1');
        }
      }
      $data = $data->get()->toArray();
      

      $data = collect($data)->map(function ($data) use ($color_variants_arr,$images, $custom_tags) 
      {

        $data['graphql_id'] = null;
        if (array_key_exists('shopify_product_id', $data[$this->shopify_data])) 
        {
          $data['graphql_id'] = 'gid://shopify/Product/' . $data[$this->shopify_data]['shopify_product_id'];
        }

        $data['metafield_graphql_api_id'] = null;

        if (isset($data[$this->shopify_data]['data'])) 
        {
          $json_data =  $data[$this->shopify_data]['data'];
          $prod_json = json_decode($json_data, true);
          if (isset($prod_json['metafield_graphql_api_id']['custom_data2'])) 
          {
            $data['metafield_graphql_api_id'] = $prod_json['metafield_graphql_api_id']['custom_data2'];
          }
        }



        $data['color_variants'] = null;
        if (array_key_exists($data['group_id'], $color_variants_arr) && count($color_variants_arr[$data['group_id']]) > 0) 
        {
          $data['color_variants'] = $color_variants_arr[$data['group_id']];
        }
        $data['images'] = isset($images[$data['real_design']]) ? $images[$data['real_design']] : null ;
        return $data;
      });
      Log::debug('Products Retrieved');
      $products = $products->merge($data);
      
    }
    $products = $this->addMissingFields($products);
    if(BrugsMigrationTool::$settings['OPERATION']->is(Operations::Create))
    {
      $shopify_data = $this->getShopifyData();
      $new_products = $this->productsDiff($products,$shopify_data);
      return $new_products;
    }

    Log::debug('Raw DB Product Ready');
    
    return $products;
  }
  public function addMissingFields($products)
  {
    $products = $products->toArray();

    foreach ($products as $index => $product)
    {  
      if (isset($product['variants']) && isset($product[$this->shopify_data]['variants_data']))
      {
        foreach ($product['variants'] as $indexvar => $variant) 
        {
          $variant_json = json_decode($product[$this->shopify_data]['variants_data'], true);
          $variant_graph_id = null;
          foreach ($variant_json as $var) 
          {
            if ($var['sku'] == $variant['sku']) 
            {
              $variant_graph_id = 'gid://shopify/ProductVariant/' . $var['variant_id'];
            }
          }
          $products[$index]['variants'][$indexvar]['variant_graph_id'] = $variant_graph_id;
        }
      }

      if (isset($product['color_variants'])) 
      {
        foreach ($product['color_variants'] as $index2 => $variant) 
        {
          if ($product['real_design'] == $variant['real_design']) 
          {
            $products[$index]['color_variants'][$index2]['this'] = true;
          }
        }
      }
    }
    
    return $products;

  }
}