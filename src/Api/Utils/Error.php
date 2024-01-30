<?php
namespace Api\Utils;

/**
 * Helper classe for handling error responses.
 */
class Error {
    /**
     * Send a JSON error reponse
     * @param mixed $exception Exception catch
     * 
     * @author valentin
     */
    public static function handleException($exception)
    {
        $statusCode = $exception->getCode() ?: 500;
        $response = ['error' => $exception->getMessage()];
        Response::sendJson($response, $statusCode);
    }
}