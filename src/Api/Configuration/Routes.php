<?php

return [
    '/hello' => [
        'methods' => ['GET'],
        'instance' => 'Api\Controllers\HelloController',
        'handler' => 'hello'
    ],
    '/animals/{id}' => [
        'methods' => ['GET','PUT','DELETE'],
        'instance' => 'Api\Controllers\AnimalController',
        'handler' => 'handlerAnimal'
    ],
    '/animals' => [
        'methods' => ['GET','POST'],
        'instance' => 'Api\Controllers\AnimalController',
        'handler' => 'handlerAnimal'
    ],
    '/especes/{id}' => [
        'methods' => ['GET','PUT','DELETE'],
        'instance' => 'Api\Controllers\FicheController',
        'handler' => 'handlerFiche'
    ],
    '/especes' => [
        'methods' => ['GET','POST'],
        'instance' => 'Api\Controllers\FicheController',
        'handler' => 'handlerFiche'
    ],
    '/alimentations' => [
        'methods' => ['GET','POST'],
        'instance' => 'Api\Controllers\AlimentationController',
        'handler' => 'handlerAlimentation'
    ],
    '/alimentations/{id}' => [
        'methods' => ['GET','PUT','DELETE'],
        'instance' => 'Api\Controllers\AlimentationController',
        'handler' => 'handlerAlimentation'
    ],
    '/classes' => [
        'methods' => ['GET','POST'],
        'instance' => 'Api\Controllers\ClasseController',
        'handler' => 'handlerClasse'
    ],
    '/classes/{id}' => [
        'methods' => ['GET','PUT','DELETE'],
        'instance' => 'Api\Controllers\ClasseController',
        'handler' => 'handlerClasse'
    ],
    '/zonesgeographique' => [
        'methods' => ['GET','POST'],
        'instance' => 'Api\Controllers\ZoneGeoController',
        'handler' => 'handlerClasse'
    ],
    '/zonesgeographique/{id}' => [
        'methods' => ['GET','PUT','DELETE'],
        'instance' => 'Api\Controllers\ZoneGeoController',
        'handler' => 'handlerClasse'
    ],
];