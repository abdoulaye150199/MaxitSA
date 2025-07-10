<?php
$routes = [
    '/' => [
        'controller' => 'App\\Controller\\SecutiryController',
        'action' => 'login',
        'middlewares' => []
    ],
    '/login' => [
        'controller' => 'App\\Controller\\SecutiryController',
        'action' => 'login',
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
        'middlewares' => ['hashCodeSecret']
    ],
    '/accueil' => [
        'controller' => 'App\\Controller\\UserController', // ou un autre contrôleur
        'action' => 'index', // ou 'accueil' selon ton code
        'middlewares' => []
    ],
    '/logout' => [
        'controller' => 'App\\Controller\\SecutiryController',
        'action' => 'logout',
        'middlewares' => []
    ],
];