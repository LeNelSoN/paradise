<?php
namespace Api\Controllers;

use Api\Utils\Response;

/**
 * Controller for /hello
 */
class HelloController 
{
    /**
     * @return 'Hello, World!'
     */
    public static function hello(): Response
    {
        $response = ['message' => 'Hello, World!'];
        return Response::sendJson($response);
    }
}