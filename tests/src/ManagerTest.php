<?php
namespace Agnostic\Tests;

class ManagerTest extends TestCase
{
    public function testTemporary()
    {
        $manager = new \Agnostic\Manager();

        $manager->registerEntityNamespace('Agnostic\Tests\Entities', __DIR__.'/Tests/Entities');

        // $manager->setTypeByEntity('Competition');
        // $manager->setTypeByEntity('Season');
        // $manager->setTypeByEntity('Round');
        // $manager->setTypeByEntity('Match');

        var_dump($manager->matches);
    }
}