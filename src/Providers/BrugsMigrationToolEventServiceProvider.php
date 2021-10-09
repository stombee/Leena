<?php 

namespace Erenkucukersoftware\BrugsMigrationTool\Providers;


use Illuminate\Foundation\Support\Providers\EventServiceProvider;
use Erenkucukersoftware\BrugsMigrationTool\Events\{ProductsCreated, ProductsUpdated};
use Erenkucukersoftware\BrugsMigrationTool\Listeners\{HandleProductsCreated, HandleProductsUpdated};


class BrugsMigrationToolEventServiceProvider extends EventServiceProvider
{
  protected $listen = [
    ProductsCreated::class => [
      HandleProductsCreated::class,
    ],
    ProductsUpdated::class => [
      HandleProductsUpdated::class,
    ],
  ];

  /**
   * Register any events for your application.
   */
  public function boot(): void
  {
    parent::boot();
  }
}
