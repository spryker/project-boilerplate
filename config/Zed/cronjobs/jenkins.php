<?php

/**
 * Notes:
 *
 * - jobs[]['name'] must not contains spaces or any other characters, that have to be urlencode()'d
 * - jobs[]['role'] default value is 'admin'
 */

$stores = require(APPLICATION_ROOT_DIR . '/config/Shared/stores.php');

$allStores = array_keys($stores);

/* Example */
//$jobs[] = [
//    'name' => 'check-product-validity',
//    'command' => '$PHP_BIN vendor/bin/console product:check-validity',
//    'schedule' => '0 6 * * *',
//    'enable' => true,
//    'stores' => $allStores,
//];
