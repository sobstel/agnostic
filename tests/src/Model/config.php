<?php
return [
    'actor' => [
        'identity_field' => 'actor_id',
    ],
    'address' => [
        'identity_field' => 'address_id',
    ],
    'film' => [
        'identity_field' => 'film_id',
        'relation_names' => [
            'actors' => [
                'relationship' => 'has_many_through',
                'native_field' => 'film_id',
                'through_type' => 'film_actor',
                'through_native_field' => 'film_id',
                'through_foreign_field' => 'actor_id',
                'foreign_field' => 'actor_id',
                'foreign_type' => 'actor'
            ],
            'language' => [
                'relationship' => 'belongs_to',
                'native_field' => 'language_id'
            ],
            'stores' => [
                'relationship' => 'has_many_through',
                'native_field' => 'film_id',
                'through_type' => 'inventory',
                'through_native_field' => 'film_id',
                'through_foreign_field' => 'store_id',
                'foreign_field' => 'store_id',
                'foreign_type' => 'store',
            ],
        ]
    ],
    'inventory' => [
        'identity_field' => 'inventory_id',
    ],
    'language' => [
        'identity_field' => 'language_id',
    ],
    'staff' => [
        'identity_field' => 'staff_id',
    ],
    'store' => [
        'identity_field' => 'store_id',
        'relation_names' => [
            'address' => [
                'relationship' => 'belongs_to',
            ],
            'staff' => [
                'relationship' => 'has_many',
            ]
        ]
    ]
];
