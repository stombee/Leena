<?php

use App\Http\Controllers\API\v1\common\constants\PermissionConstants;

use Erenkucukersoftware\BrugsMigrationTool\Controllers\BrugsMigrationToolController;


use Illuminate\Support\Facades\Route;





Route::middleware('auth:sanctum')->group(
  function () {
    Route::get('v1/migrationtool', function(){echo 'hello';});
    Route::post('v1/migrationtool/products', [BrugsMigrationToolController::class, 'createProducts']);
    Route::put('v1/migrationtool/products', [BrugsMigrationToolController::class, 'updateProducts']);
  }
);


