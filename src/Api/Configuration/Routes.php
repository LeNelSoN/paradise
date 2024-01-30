<?php

return [
    '/hello' => [
        'methods' => ['GET'],
        'instance' => 'Api\Controllers\HelloController',
        'handler' => 'hello'
    ],
    '/animals/{id}' => [
        'methods' => ['GET'],
        'instance' => 'Api\Controllers\AnimalController',
        'handler' => 'handlerAnimal'
    ],
    '/animals' => [
        'methods' => ['GET'],
        'instance' => 'Api\Controllers\AnimalController',
        'handler' => 'handlerAnimal'
    ]
];