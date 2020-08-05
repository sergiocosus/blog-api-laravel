<?php

return [
    'role_structure' => [
        'superadministrator' => [
            'user-role' => 'set-super-administrator,set-administrator,set-editor',
            'post' => 'c,r,u,d',
            'category' => 'c,r,u,d',
            'media' => 'c,r,u,d',
            'setting' => 'u',
            'link' => 'c,r,u,d',
            'event' => 'c,r,u,d',
            'gallery' => 'c,r,u,d',
            'member' => 'c,r,u,d',
            'argument' => 'c,r,u,d',
            'organization' => 'c,r,u,d',
            'contact-response' => 'r,d',
        ],
        'administrator' => [
            'user-role' => 'set-super-administrator,set-administrator,set-editor',
            'post' => 'c,r,u,d',
            'category' => 'c,r,u,d',
            'media' => 'c,r,u,d',
            'setting' => 'u',
            'link' => 'c,r,u,d',
            'event' => 'c,r,u,d',
            'gallery' => 'c,r,u,d',
            'member' => 'c,r,u,d',
            'argument' => 'c,r,u,d',
            'organization' => 'c,r,u,d',
            'contact-response' => 'r,d',
        ],
        'editor' => [
            'user-role' => 'set-super-administrator,set-administrator,set-editor',
            'post' => 'c',
            'category' => 'c',
            'media' => 'c',
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
