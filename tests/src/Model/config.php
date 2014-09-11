<?php
return [
    'actor' => [
        'identity_field' => 'actor_id',
    ],
    'address' => [
        'identity_field' => 'address_id',
        'relation_names' => [
            'city' => [
                'relationship' => 'belongs_to'
            ]
        ]
    ],
    'city' => [
        'identity_field' => 'city_id',
        'entity_class' => 'Agnostic\Tests\Model\City',
        'relation_names' => [
            'country' => [
                'relationship' => 'belongs_to'
            ]
        ]
    ],
    'country' => [
        'identity_field' => 'country_id',
    ],
    'film' => [
        'identity_field' => 'film_id',
        'relation_names' => [
            'actors' => [
                'relationship' => 'has_many_through',
                // 'through_type' => 'film_actor',
                'foreign_type' => 'actor'
            ],
            'language' => [
                'relationship' => 'belongs_to',
                'native_field' => 'language_id'
            ],
            'stores' => [
                'relationship' => 'has_many_through',
                'through_type' => 'inventory',
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
