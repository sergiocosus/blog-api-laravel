<?php

return [
    'role_structure' => [
        'superadministrator' => [
            'post' => 'c,r,u,d,',
            'category' => 'c,r,u,d',
            'media' => 'c,r,u,d',
            'setting' => 'u',
            'link' => 'c,r,u,d',
            'event' => 'c,r,u,d',
            'gallery' => 'c,r,u,d',
        ],
        'administrator' => [
            'post' => 'c,r,u,d',
            'category' => 'c,r,u,d',
            'media' => 'c,r,u,d',
            'setting' => 'u',
            'link' => 'c,r,u,d',
            'event' => 'c,r,u,d',
            'gallery' => 'c,r,u,d',
        ],
        'user' => [
            'post' => '',
            'category' => '',
            'media' => '',
        ],
    ],
    'permission_structure' => [
        'cru_user' => [
            'profile' => 'c,r,u'
        ],
    ],
    'permissions_map' => [
        'c' => 'create',
        'r' => 'read',
        'u' => 'update',
        'd' => 'delete'
    ]
];
