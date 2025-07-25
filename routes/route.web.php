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
    ],
    '/compte' => [
        'controller' => 'App\\Controller\\UserController',
        'action' => 'nouveauCompte',
        'middlewares' => []
    ],
    '/compte/nouveau' => [
        'controller' => 'App\\Controller\\CompteController',
        'action' => 'nouveauCompte',
        'middlewares' => ['auth']
    ],
    '/compte/create' => [
        'controller' => 'App\\Controller\\CompteController',
        'action' => 'createCompte',
        'middlewares' => ['auth']
    ],
    '/comptes' => [
        'controller' => 'App\\Controller\\CompteController',
        'action' => 'index',
        'middlewares' => ['auth']
    ],
    '/paiement' => [
        'controller' => 'App\\Controller\\PaymentController',
        'action' => 'index',
        'middlewares' => []
    ],
    '/listetransaction' => [
        'controller' => 'App\\Controller\\TransactionController',
        'action' => 'index',
        'middlewares' => []
    ],
    '/transfert' => [
        'controller' => 'App\\Controller\\TransferController',
        'action' => 'index',
        'middlewares' => []
    ],
    
    // Routes Service Commercial
    '/service-commercial' => [
        'controller' => 'App\\Controller\\ServiceCommercialController',
        'action' => 'index',
        'middlewares' => ['auth']
    ],
    
    '/service-commercial/search' => [
        'controller' => 'App\\Controller\\ServiceCommercialController',
        'action' => 'searchAccount',
        'middlewares' => ['auth']
    ],
    
    '/service-commercial/transactions' => [
        'controller' => 'App\\Controller\\ServiceCommercialController',
        'action' => 'allTransactions',
        'middlewares' => ['auth']
    ]
];