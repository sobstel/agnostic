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
            ->with('round')
            ->with('teamA')
            ->with('teamB')
            ->with('events')
            ->fetch();

        // $r = $rf->get("Match")
        //     ->find([157045, 157046, 156746, 156679, 156513, 156531])
        //     ->orderBy('date_time', 'DESC')
        //     // ->refine('') // scope
        //     ->with('round', function($query) { $query->with('season'); })) // shortcut: -> with['round' => ['season' => 'competition']]
        //     ->with(['teamA', 'teamB']) // array support
        //     ->fetch();

        var_dump(count($r[0]->events), $r[0]->events[0]->toArray());

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
