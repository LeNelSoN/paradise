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
    ]
];