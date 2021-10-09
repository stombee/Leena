<?php

namespace Erenkucukersoftware\BrugsMigrationTool;


use Erenkucukersoftware\BrugsMigrationTool\Enums\{Fields, Operations, ToolStatus, CustomGroups, ApiMode};
use Erenkucukersoftware\BrugsMigrationTool\Facades\Shopify\ShopifyFlowFacade;
use Erenkucukersoftware\BrugsMigrationTool\Facades\Product\ProductFacade;
use Erenkucukersoftware\BrugsMigrationTool\Exports\ProductsExport;
use Erenkucukersoftware\BrugsMigrationTool\Events\{ProductsCreated,ProductsUpdated};
use Illuminate\Support\Facades\{Log, Storage};
use App\Traits\RespondsWithHttpStatus;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\EventStates;
use Carbon\Carbon;

ini_set('max_execution_time', 0);
ini_set('memory_limit', '5G');


class BrugsMigrationTool
{
    use RespondsWithHttpStatus;

    public $response;
    public static $settings;

    public function __construct()
    {
        self::$settings =
            [
                'IS_DEV' => false,
                'API_MODE' =>  ApiMode::GraphQL(),
            ];
    }

    public function operation($operation)
    {
        self::$settings['OPERATION'] = $operation;
        return $this;
    }
    public function entity($entity)
    {


        self::$settings['ENTITY'] = explode(',', $entity);

        return $this;
    }
    public function fields($fields)
    {
        self::$settings['FIELDS'] = explode(',', $fields);
        return $this;
    }
    public function custom_group($custom_group)
    {
        self::$settings['CUSTOM_GROUP'] = explode(',', $custom_group);
        return $this;
    }

    public function createProducts()
    {
        
        
        $products_facade = new ProductFacade();
        $products = $products_facade->run();
        $raw_products = $products_facade->rawProducts();
        $file_name = 'products_' . Carbon::now()->format('Y-m-d_H-i') . '.xlsx';
        $time_stamp = Carbon::now('America/New_York');
        $shopify_response = (new ShopifyFlowFacade())
            ->run($products);
        $this->createProcessedList($file_name, $raw_products, $products[0]['input']);
        $result =
            [
                'processed_list' => [
                    'src' => asset('storage/' . $file_name)
                ]

            ];

        ProductsCreated::dispatch($time_stamp);
        return $result;
        

    }

    public function updateProducts()
    {
        $products_facade = new ProductFacade();
        $products = $products_facade->run();
        
        $raw_products = $products_facade->rawProducts();
        $file_name = 'products_' . Carbon::now()->format('Y-m-d_H-i') . '.xlsx';
        $time_stamp = Carbon::now('America/New_York');
        $shopify_response = (new ShopifyFlowFacade())
            ->run($products);
        $this->createProcessedList($file_name, $raw_products,$products[0]['input']);
        $result =
            [
                'processed_list' => [
                    'src' => asset('storage/' . $file_name)
                ]

            ];
        //ProductsUpdated::dispatch($time_stamp);
        return $result;
    }


    public function checkMigrationToolStatus()
    {

        $state = $this->getToolStatus();
        $shopify_data_state = $this->getShopifyDataStatus();
        if ($state->is(ToolStatus::Busy)) {
            throw new \Erenkucukersoftware\BrugsMigrationTool\Exceptions\MigrationToolBusyException('Migration tool is busy');
        }
        if ($shopify_data_state->is(ToolStatus::Busy)) {
            throw new \Erenkucukersoftware\BrugsMigrationTool\Exceptions\ShopifyDataBusyException('Shopify Data is busy');
        }

    }
    public function setToolStatus($value)
    {
        if ($value->is(ToolStatus::Busy)) {
            EventStates::where('name', '=', 'is_migration_tool_busy')->update(['value' => true]);
        } elseif ($value->is(ToolStatus::Free)) {
            EventStates::where('name', '=', 'is_migration_tool_busy')->update(['value' => false]);
        } else {
            throw new \Exception('Migration tool set status failed.');
        }
    }
    public function getToolStatus()
    {
        $status = (int) EventStates::where('name', '=', 'is_migration_tool_busy')->get()->pluck('value')->first();
        return ToolStatus::fromValue($status);
    }
    public function getShopifyDataStatus()
    {
        $status = (int) EventStates::where('name', '=', 'is_shopify_data_busy')->get()->pluck('value')->first();
        return ToolStatus::fromValue($status);
    }

    public function validateFields($fields)
    {
        $fields_enum = Fields::getKeys();

        foreach ($fields as $value) {
            if (!empty($value) && !in_array($value, $fields_enum)) {
                throw new \Exception('Invalid field');
            }
        }
    }

    public function validateCustomGroups($custom_groups)
    {
        $custom_groups_enum = CustomGroups::getKeys();
        foreach ($custom_groups as $value) {
            if (!empty($value) && !in_array($value, $custom_groups_enum)) {
                throw new \Exception('Invalid custom group');
            }
        }
    }

    public static function getFields($asArray = false)
    {
        $keys = [];
        if ($asArray) {
            foreach (self::$settings['FIELDS'] as $field) {
                foreach ($field->getKeys() as $key) {
                    $keys[] = $key;
                }
            }
        }
        return $keys;
    }

    public function createProcessedList($file_name, $products,$fields)
    {
        $fields = array_keys($fields);
        
        $product_export = new ProductsExport($products, BrugsMigrationTool::$settings['OPERATION']->key,$fields);
        return Excel::store($product_export, $file_name, 'migration_tool');
    }

    public function run()
    {
        try {
            $this->checkMigrationToolStatus();
            $this->validateFields(self::$settings['FIELDS']);
            $this->validateCustomGroups(self::$settings['CUSTOM_GROUP']);
            self::$settings['FIELDS'] = convertEnum(self::$settings['FIELDS'], Fields::class);
            self::$settings['CUSTOM_GROUP'] = convertEnum(self::$settings['CUSTOM_GROUP'], CustomGroups::class);

            $this->setToolStatus(ToolStatus::Busy());


            //PRODUCT UPDATES
            if (self::$settings['OPERATION']->is(Operations::Update)) {
                $this->response = $this->updateProducts();
            }
            if (self::$settings['OPERATION']->is(Operations::Create)) {
                $this->response = $this->createProducts();
            }

            $this->setToolStatus(ToolStatus::Free());


            return $this->success('Product Process Completed Successfully.', $this->response);
        } catch (\Exception $e) {
            if (!$e instanceof \Erenkucukersoftware\BrugsMigrationTool\Exceptions\MigrationToolBusyException) {
                $this->setToolStatus(ToolStatus::Free());
            }
            echo $e->getMessage();
        }
    }
}
