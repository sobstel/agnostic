<?php
return $manager
    ->query('film')
    ->with(['language'])
    ->setMaxResults(3)
    ->fetch();
