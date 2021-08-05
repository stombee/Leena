<?php

namespace Erenkucukersoftware\BrugsMigrationTool\Controllers;

use App\Http\Controllers\API\v1\common\constants\Constants;
use App\Http\Controllers\API\v1\ws\BaseController;


use App\Traits\RespondsWithHttpStatus;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use App\Models\Image;
use PHPShopify\ShopifySDK;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;

use Erenkucukersoftware\BrugsMigrationTool\BrugsMigrationTool;

ini_set('max_execution_time', 0);



class BrugsMigrationToolController extends BaseController
{
  use RespondsWithHttpStatus;


  public function createProducts(Request $request){
    $fields = $request->fields;
    $entity_type = $request->entity_type;
    $tool = new BrugsMigrationTool('all',$fields,$entity_type,'create');
    $response = $tool->run()->getResponse();
    return $response;
  }


/*
  public function updateProducts(Request $request)
  {
    $products = $request->update;
    $fields = $request->fields;
    $entity_type = $request->entity_type;
    $tool = new BrugsMigrationTool($products, $fields, $entity_type);
    $response = $tool->run()->response();
    return $response;
  }

  public function checkUpdateStatus(Request $request)
  {
    $status = $request->status;
    $response = BrugsMigrationTool::checkBulkActionStatus($status);
    $data = json_encode($response->data->node);

    return $data;
  }
  */

}
