<?php
return [
    'project' => [
        'type' => 1,
        'description' => 'Проект',
        'ruleName' => 'userProject',
    ],
    'user' => [
        'type' => 1,
        'description' => 'Пользователь',
        'ruleName' => 'userRole',
    ],
    'partner' => [
        'type' => 1,
        'description' => 'Партнер',
        'ruleName' => 'userRole',
        'children' => [
            'user',
        ],
    ],
    'manager' => [
        'type' => 1,
        'description' => 'Менеджер',
        'ruleName' => 'userRole',
        'children' => [
            'partner',
            'project',
        ],
    ],
    'admin' => [
        'type' => 1,
        'description' => 'Администратор',
        'ruleName' => 'userRole',
        'children' => [
            'manager',
            'project',
        ],
    ],
];
