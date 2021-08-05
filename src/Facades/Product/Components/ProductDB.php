<?php

namespace Erenkucukersoftware\BrugsMigrationTool\Facades\Product\Components;


use Illuminate\Support\Facades\DB;


class ProductDB{

  public $products;

  public function getProducts(){


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
    
    $this->products = $products;
    return $this;
  }


}