<?php
namespace Agnostic\Tests;

use Agnostic\EntityManager;

class EntityManagerTest extends TestCase
{
    public function testTemporary()
    {
        $conn = \Doctrine\DBAL\DriverManager::getConnection(['pdo' => self::$dbh]);
        $queryDriver = new \Agnostic\QueryDriver\DoctrineQueryDriver($conn);

        $em = new EntityManager($queryDriver);
        $em->registerEntityNamespace('Agnostic\Tests\Entities', __DIR__.'/Tests/Entities');
        $em->registerRepositoryNamespace('Agnostic\Tests\Repositories', __DIR__.'/Tests/Repositories');

        $repository = $em->getRepository("Match");

        echo $repository->baseQuery();
    }
}
