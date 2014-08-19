<?php
namespace Agnostic\Tests;

use Agnostic\EntityManager;

class EntityManagerTest extends TestCase
{
    public function testTemporary()
    {
        $conn = \Doctrine\DBAL\DriverManager::getConnection(['pdo' => self::$dbh]);
        $queryDriver = new \Agnostic\Query\Doctrine\DbalQueryDriver($conn);

        $nameResolver = new \Agnostic\Entity\NameResolver();
        $nameResolver->registerEntityNamespace('Agnostic\Tests\Entities', __DIR__.'/Tests/Entities');
        $nameResolver->registerRepositoryNamespace('Agnostic\Tests\Repositories', __DIR__.'/Tests/Repositories');

        $em = new EntityManager($queryDriver, $nameResolver);

        $repository = $em->getRepository("Match");

        // var_dump($repository->findBy('match_id', [157045, 157046]));
        var_dump($repository->find([157045, 157046]));
    }
}
