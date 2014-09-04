<?php
namespace Agnostic\Tests;

use Agnostic\Query\Factory as QueryFactory;

class TemporaryTest extends TestCase
{
    public function testTemporary()
    {
        $conn = \Doctrine\DBAL\DriverManager::getConnection(['pdo' => self::$dbh]);
        $queryDriver = new \Agnostic\QueryDriver\DoctrineQueryDriver($conn);
        $queryDriver = new \Agnostic\QueryDriver\DebugQueryDriver($queryDriver);

        $nameResolver = new \Agnostic\NameResolver();
        $nameResolver->registerEntityPrefix('Agnostic\Tests\Entity', __DIR__.'/Tests/Entity');
        $nameResolver->registerQueryPrefix('Agnostic\Tests\Query', __DIR__.'/Tests/Query');

        $qf = new QueryFactory($queryDriver, $nameResolver);

        $r = $qf->create("Match")
            ->find([57045, 157046, 156746, 156679, 156513, 156531])
            ->orderBy('date_time', 'DESC')
            ->with(['events', 'teamA', 'teamB', 'round' => ['season' => 'competition']])
            // ->with('round', function($query) { $query->with('season'); }))
            ->fetch();

        // var_dump(count($r[0]->events), $r[0]->events[0]->toArray());

        foreach ($r as $k => $v) {
            echo sprintf('%d: %s: (%s - %s - %s) %s v %s  %d - %d'.PHP_EOL,
                $v['match_id'],
                $v['date_time'],
                $v->round->season->competition->name,
                $v->round->season->name,
                $v->round->name,
                $v->teamA ? $v->teamA->name : 'n/a',
                $v->teamB ? $v->teamB->name : 'n/a',
                $v->team_A_score,
                $v->team_B_score
            );
        }
     }
}
