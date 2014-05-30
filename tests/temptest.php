<?php
require_once 'bootstrap.php';

$manager = new Agnostic\Manager();

$manager->registerEntityNamespace('Agnostic\Tests\Entities', __DIR__.'/Tests/Entities');

$manager->setTypeFromEntity('Match');

var_dump($manager->matches);
