<?php
return $manager
    ->query('film')
    ->with('language')
    ->with('actors')
    ->setMaxResults(30)
    ->fetch();
