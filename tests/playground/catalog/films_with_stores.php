<?php
return $manager
    ->query('film')
    ->with(['stores' => ['address', 'staff']])
    ->setMaxResults(5)
    ->fetch();