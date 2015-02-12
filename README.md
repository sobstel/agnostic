Agnostic
========

PHP Non-ORM for those scared off by complexity, slowness and strictness of Doctrine
(which makes programming hardly a pressure), but still want more control than
any ActiveRecord-like ORM gives.

Your queries (any engine allowed... Doctrine DBAL, Laravel database, plain SQL query).
Our mapping to objects + scalable bulletproof relations loading.

It's a bit like Aura.Marshal (wich it uses internally) on steroids (more convention
over configuration, less writing, more magic).

Still needs to be more polished, tested and documented.

Examples
--------

** See: https://github.com/sobstel/agnostic/tree/master/tests/playground **

Old example
-----------

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

Random notes
------------

* any query builder you want
* multiple entities might be stored in different storages easily (no joins)
* having way to query database flexible way yet having entities and collections
* all or n+1 issue solved (http://webadvent.org/2011/a-stitch-in-time-saves-nine-by-paul-jones,
  http://auraphp.com/blog/2014/02/17/aura-marshal/)
* prefers convention over configuration, a bit more magic and convenience
* Agnostic is like Doctrine, but more flexible and less strict
* Agnostic is like Aura.Marshal, but easier and faster to use
* Lightweight CPU-firendly hydration -> Getting rid of CPU-heavy complex hydration
* Maximum query building flexibility (no strict ORM query builder rules) -> easy to write scalable/fast
  queries -> possible to write ANY query
* Querying by PK (low cost, easy to cache and have shared cache)
* SELECT * -> http://use-the-index-luke.com/de/blog/2013-08/its-not-about-the-star-stupid
* easier to lavarege HANDLER https://mariadb.com/kb/en/handler-commands/
* possible to use without entities (just type names)
* Entity != tableName, it's more like one row of resultset
* caveats: no support for compound keys

Join decomposition
------------------

Many high-performance web sites use join decomposition. It's done by running multiple single-table
queries instead of a multitable join, and then performing the join in the application.

Gains are:

* caching can be more efficient,
* for MyISAM tables it use table locks more efficiently,
* doing joins in the application makes it easier to scale the database by placing tables on different servers,
* queries themselves can be more efficient (using an IN list instead of a join lets MySQL sort row IDs and
  retrieve rows more optimally than might be possible with a join),
* it can reduce redundant row accesses (the total network traffic and memory usage),
* we can easily switch/use in-between key-value storage or use in-built Percona Server optimizations.

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

