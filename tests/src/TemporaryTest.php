<?php
namespace Agnostic\Tests;

use Agnostic\Entity\RepositoryFactory;

class TemporaryTest extends TestCase
{
    public function testTemporary()
    {
        $conn = \Doctrine\DBAL\DriverManager::getConnection(['pdo' => self::$dbh]);
        $queryDriver = new \Agnostic\Query\DoctrineQueryDriver($conn);

        $nameResolver = new \Agnostic\Entity\NameResolver();
        $nameResolver->registerEntityNamespace('Agnostic\Tests\Entities', __DIR__.'/Tests/Entities');
        $nameResolver->registerRepositoryNamespace('Agnostic\Tests\Repositories', __DIR__.'/Tests/Repositories');

        $rf = new RepositoryFactory($queryDriver, $nameResolver);

        $r = $rf->get("Match")
            ->find([157045, 157046, 156746, 156679, 156513, 156531])
            ->orderBy('date_time', 'DESC')
            // ->refine('') // scope
            ->with('round') // relation
            ->with('teamA')
            ->with('teamB')
            ->fetch();

        foreach ($r as $k => $v) {
            echo sprintf('%d: %s: (%s) %s v %s  %d - %d'.PHP_EOL, 
                $v['match_id'],
                $v['date_time'],
                $v->round->name,
                $v->teamA ? $v->teamA->name : 'n/a',
                $v->teamB ? $v->teamB->name : 'n/a',
                $v->team_A_score,
                $v->team_B_score
            );
        }
     }
}
