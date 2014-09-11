<?php
return $manager
    ->query('film')
    ->with(['stores' => ['address' => ['city' => 'country'], 'staff']])
    ->setMaxResults(5)
    ->fetch();