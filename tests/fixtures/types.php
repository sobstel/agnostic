<?php
return [
    'film' => [
        'identity_field' => 'film_id',
        'relation_names' => [
            'language' => [
                'relationship' => 'belongs_to',
                'native_field' => 'language_id'
            ]
        ]
    ],
    'language' => [
        'identity_field' => 'language_id',
    ]
];
