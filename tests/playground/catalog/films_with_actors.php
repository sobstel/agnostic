<?php
return $manager
    ->query('film')
    ->with('language')
    ->with('actors')
    ->addOrderBy('replacement_cost', 'DESC')
    ->addOrderBy('rental_rate', 'DESC')
    ->setMaxResults(30)
    ->fetch();
