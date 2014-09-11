<?php
namespace Agnostic\Tests;

class TemporaryTest extends TestCase
{
    public function testTemporary()
    {
        $conn = \Doctrine\DBAL\DriverManager::getConnection(['pdo' => self::$dbh]);
        $query_driver = new \Agnostic\QueryDriver\DoctrineQueryDriver($conn);
        $query_driver = new \Agnostic\QueryDriver\DebugQueryDriver($query_driver);

        $manager = new \Agnostic\Manager($query_driver);

        $manager->setType('matches', [
            'identity_field' => 'match_id',
            'entity_class' => 'Agnostic\Tests\Model\Match',
            'relation_names' => [
                'round' => [
                    'relationship' => 'belongs_to',
                    'foreign_type' => 'rounds',
                    'native_field' => 'round_id',
                ],
                'teamA' => [
                    'relationship' => 'belongs_to',
                    'foreign_type' => 'teams',
                    'native_field' => 'team_A_id',
                ],
                'teamB' => [
                    'relationship' => 'belongs_to',
                    'foreign_type' => 'teams',
                    'native_field' => 'team_B_id',
                ],
                'events' => [
                    'relationship' => 'has_many',
                    // 'foreign_type' => 'events',
                    // 'native_field' => 'match_id',
                    // 'foreign_field' => 'match_id',
                ]
            ]
        ]);

        $manager->setType('competitions', [
            'identity_field' => 'competition_id',
            'entity_class' => 'Agnostic\Tests\Model\Competition',
        ]);

        $manager->setType('seasons', [
            'identity_field' => 'season_id',
            'entity_class' => 'Agnostic\Tests\Model\Season',
            'relation_names' => [
                'competition' => [
                    'relationship' => 'belongs_to',
                    'foreign_type' => 'competitions',
                ]
            ]
        ]);

        $manager->setType('rounds', [
            'identity_field' => 'round_id',
            'entity_class' => 'Agnostic\Tests\Model\Round',
            'relation_names' => [
                'season' => [
                    'relationship' => 'belongs_to',
                    'foreign_type' => 'seasons',
                ]
            ]
        ]);

        $manager->setType('teams', [
            'identity_field' => 'team_id',
            'entity_class' => 'Agnostic\Tests\Model\Team',
        ]);

        $manager->setType('events', [
            'identity_field' => 'event_id',
            'entity_class' => 'Agnostic\Tests\Model\Event',
        ]);

        $r = $manager
            ->query('matches')
            ->find([57045, 157046, 156746, 156679, 156513, 156531])
            ->orderBy('date_time', 'DESC')
            ->with(['events', 'teamA', 'teamB', 'round' => ['season' => 'competition']])
            ->fetch();

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
