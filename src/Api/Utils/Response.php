<?php
namespace Api\Utils;

/**
 * Helper class for handling JSON responses.
 */
class Response {
    /**
     * Send a JSON response.
     *
     * @param mixed $data Data to be encoded as JSON.
     * @param int   $statusCode HTTP status code (default is 200 OK).
     * 
     * @author valentin
     */
    public static function sendJson(mixed $data, int $statusCode = 200)
    {
        header('Content-Type: application/json');
        http_response_code($statusCode);
        $response = json_encode($data);
        echo $response;
    }
}