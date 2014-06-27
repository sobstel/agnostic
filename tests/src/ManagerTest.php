<?php
namespace Agnostic\Tests;

class ManagerTest extends TestCase
{
    public function testTemporary()
    {
        $manager = new \Agnostic\Manager();

        $manager->registerEntityNamespace('Agnostic\Tests\Entities', __DIR__.'/Tests/Entities');

        $manager->setTypeFromEntity('Competition');
        $manager->setTypeFromEntity('Season');
        $manager->setTypeFromEntity('Round');
        $manager->setTypeFromEntity('Match');

        var_dump($manager->matches);
    }
}