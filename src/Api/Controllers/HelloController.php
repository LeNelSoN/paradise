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
    public function hello()
    {
        $response = ['message' => 'Hello, World!'];
        Response::sendJson($response);
    }
}