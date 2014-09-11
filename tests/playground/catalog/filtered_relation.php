<?php
return $manager
    ->query('film')
    ->with('actors', function($query){
        $query->andWhere('actor_id > 100');
    })
    ->setMaxResults(5)
    ->fetch();
