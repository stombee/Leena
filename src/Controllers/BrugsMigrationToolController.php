<?php

namespace Erenkucukersoftware\BrugsMigrationTool\Controllers;

use App\Http\Controllers\API\v1\ws\BaseController;
use App\Traits\RespondsWithHttpStatus;
use Illuminate\Http\Request;
use Erenkucukersoftware\BrugsMigrationTool\BrugsMigrationTool;
use Erenkucukersoftware\BrugsMigrationTool\Enums\{Operations};

ini_set('max_execution_time', 0);



class BrugsMigrationToolController extends BaseController
{
  use RespondsWithHttpStatus;


  public function createProducts(Request $request)
  {
    $fields = $request->fields;
    $custom_group = $request->custom_group;
    $entity = $request->entity;
    $response =  
    (new BrugsMigrationTool())
    ->operation(Operations::Create())
    ->entity($entity)
    ->fields($fields)
    ->custom_group($custom_group)
    ->run();
    return $response;
  }

  public function updateProducts(Request $request)
  {
    $products = $request->update;
    $fields = $request->fields;
    $entity = $request->entity;
    $custom_group = $request->custom_group;
    $response = 
    (new BrugsMigrationTool())
    ->operation(Operations::Update())
    ->entity($entity)
    ->fields($fields)
    ->custom_group($custom_group)
    ->run();
    return $response;
  }


  /*
  public function checkUpdateStatus(Request $request)
  {
    $status = $request->status;
    $response = BrugsMigrationTool::checkBulkActionStatus($status);
    $data = json_encode($response->data->node);

    return $data;
  }
  */

}
