<?php

return [
  'update_logs' => [
    'driver' => 'single',
    'level'  => 'debug',
    'path'   => storage_path('logs/product_updates.log'),
  ],
  'create_logs' => [
    'driver' => 'single',
    'level'  => 'debug',
    'path'   => storage_path('logs/product_creates.log'),
  ],
];