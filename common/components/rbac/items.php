<?php
return [
    'accessAdmin' => [
        'type' => 2,
        'description' => 'Access to admin panel',
    ],
    'accessManager' => [
        'type' => 2,
        'description' => 'Access to manager panel',
    ],
    'accessClient' => [
        'type' => 2,
        'description' => 'Access to client panel',
    ],
    'Client' => [
        'type' => 1,
        'description' => 'Client',
        'ruleName' => 'userRole',
        'children' => [
            'accessClient',
        ],
    ],
    'Manager' => [
        'type' => 1,
        'description' => 'Manager',
        'ruleName' => 'userRole',
        'children' => [
            'accessManager',
        ],
    ],
    'Admin' => [
        'type' => 1,
        'description' => 'Admin',
        'ruleName' => 'userRole',
        'children' => [
            'accessAdmin',
            'Manager',
            'Client'
        ],
    ],
];
