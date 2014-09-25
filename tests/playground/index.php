<?php
use Agnostic\Tests\Registry;

$active_item_name = current(array_keys($_GET));

require __DIR__.'/common.inc.php';

$active_item = (isset($catalog[$active_item_name]) ? $catalog[$active_item_name] : false);
if ($active_item) {
    $manager = Registry::manager();
    $result = require $active_item['file'];
    $queries = \Agnostic\QueryDriver\DebugQueryDriver::getQueries();
}

require __DIR__.'/index.tpl.php';
