<?php

$routes = [
    '/' => [
        'controller' => 'App\\Controller\\SecurityController',
        'action' => 'login',
        'middlewares' => []
    ],
    '/login' => [
        'controller' => 'App\\Controller\\SecurityController',
        'action' => 'login',
        'middlewares' => []
    ],
    '/logout' => [
        'controller' => 'App\\Controller\\SecurityController',
        'action' => 'logout',
        'middlewares' => []
    ],
    '/sign' => [
        'controller' => 'App\\Controller\\UserController',
        'action' => 'store',
        'middlewares' => []
    ],
    '/code-secret' => [
        'controller' => 'App\\Controller\\UserController',
        'action' => 'codeSecret',
        'middlewares' => []
    ],
    '/accueil' => [
        'controller' => 'App\\Controller\\UserController',
        'action' => 'index',
        'middlewares' => ['auth']
    ]
];