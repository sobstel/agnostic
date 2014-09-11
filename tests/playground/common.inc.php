<?php
require __DIR__.'/../../vendor/autoload.php';

$catalog = [];

foreach (glob('catalog/*.php') as $file) {
    $name = substr($file, 8, -4);

    $catalog[$name] = [
        'name' => $name,
        'file' => $file
    ];
}
