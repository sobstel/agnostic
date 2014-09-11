Agnostic
========

Status
------

Work in progress.

- [x] Queries and relations work.
- [ ] Custom queries and scopes yet to be done.

Examples
--------

** See: https://github.com/sobstel/agnostic/tree/master/tests/playground **

<pre>
$manager
    ->query('matches')
    ->find([57045, 157046, 156746, 156679, 156513, 156531])
    ->orderBy('date_time', 'DESC')
    ->with(['events', 'teamA', 'teamB', 'round' => ['season' => 'competition']])
    ->fetch();

SELECT m.* FROM matches m WHERE match_id IN (57045, 157046, 156746, 156679, 156513, 156531) ORDER BY date_time DESC
SELECT e.* FROM events e WHERE match_id IN (156531, 156513, 156679, 156746, 157046)
SELECT t.* FROM teams t WHERE team_id IN (349, 1348, 1497, 1677, 424)
SELECT t.* FROM teams t WHERE team_id IN (944, 514, 382, 1497)
SELECT r.* FROM rounds r WHERE round_id IN (752, 747, 766, 776, 826)
SELECT s.* FROM seasons s WHERE season_id IN (599, 602, 604, 614)
SELECT c.* FROM competitions c WHERE competition_id IN (72)

156531: 1998-07-12 21:00:00: (World Cup - 1998 France - Final) Brazil v France  0 - 3
156513: 1998-06-20 14:30:00: (World Cup - 1998 France - Group stage) Japan v n/a  0 - 1
156679: 1986-06-15 19:00:00: (World Cup - 1986 Mexico - 16th Finals) Mexico v Bulgaria  2 - 0
156746: 1978-06-10 00:00:00: (World Cup - 1978 Argentina - Group Stage 1) Poland v Mexico  3 - 1
157046: 1930-07-16 17:45:00: (World Cup - 1930 Uruguay - Group stage) Chile v Mexico  3 - 0
</pre>

Fixtures
--------

* http://dev.mysql.com/doc/sakila/en/sakila-structure.html
* http://dev.mysql.com/doc/sakila/en/sakila-structure-tables.html

Quality checks
--------------

* https://scrutinizer-ci.com/g/sobstel/agnostic/
* https://travis-ci.org/sobstel/agnostic

Thanks
------

* Paul M. Jones
* Robert Trzewiczek

