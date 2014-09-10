<?php
namespace Agnostic\Tests;

use Agnostic\Query\Factory as QueryFactory;

class TemporaryTest extends TestCase
{
    public function testTemporary()
    {
        $conn = \Doctrine\DBAL\DriverManager::getConnection(['pdo' => self::$dbh]);
        $query_driver = new \Agnostic\QueryDriver\DoctrineQueryDriver($conn);
        $query_driver = new \Agnostic\QueryDriver\DebugQueryDriver($query_driver);

        $type_builder = new \Agnostic\Type\Builder($query_driver);
        $type_builder->registerClassPrefix('Agnostic\Tests\Model');

        $manager = new \Agnostic\Manager($type_builder);

        $r = $manager
            ->get('Match')
            ->find([57045, 157046, 156746, 156679, 156513, 156531])
            ->orderBy('date_time', 'DESC')
            ->fetch();
// var_dump($r[0]->round);
// exit;
        $r = $qf->create("Match")
            ->find([57045, 157046, 156746, 156679, 156513, 156531])
            ->orderBy('date_time', 'DESC')
            ->with('round')
            // ->with('events')
            // ->with(['events', 'teamA', 'teamB', 'round' => ['season' => 'competition']])
            // ->with('round', function($targetQuery) { $targetQuery->with('season'); }))
            // ->with('goals', function($targetQuery){
//
            // })
           ->fetch();
var_dump($r[0]->round);exit;
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
