<?php
namespace Api;

use Api\Utils\Response;
use Api\Utils\Error;
use Api\Controllers\HelloController;
use Api\Controllers\AnimalController;
use Exception;

/**
 * Init application and handle the routes for the API
 *
 * @author valentin
 */
class Application {
    
    private array $routeList;
    private String $pathParam;

    public function __construct()
    {
        define('DB_HOST', 'localhost:3306');
        define('DB_NAME', 'paradise_db');
        define('DB_USER', 'root');
        define('DB_PASS', 'password');
        
        $this->routeList = require_once '/var/www/html/src/Api/Configuration/Routes.php';
    }
    
    public function run()
    {
        try{
            $this->handleRoutes();
        }catch (Exception $e){
            Error::handleException($e);
        }
    }
    
    private function handleRoutes(): void
    {
        $uri = filter_var($_SERVER['REQUEST_URI'], FILTER_SANITIZE_URL);
        $method = filter_var($_SERVER['REQUEST_METHOD'], FILTER_SANITIZE_URL);

        $route = $this->matchRoute($uri, $method);

        if ($route != null)
        {
            $instance = $route['instance'];
            $handler = $route['handler'];
            if (is_string($instance) && is_string($handler) )
            {
                if (class_exists($instance) && method_exists($instance, $handler))
                {
                    $controller = new $instance();
                    $controller->$handler($method, ...$route['params']);
                }
            }
        } else {
            throw new Exception("Not Found", 404);
        }
    }

    private function matchRoute(String $uri, String $method): array
    {
        $route = [];
        
        foreach ($this->routeList as $path => $config) {
            $pattern = str_replace('/', '\/', $path);
            $pattern = preg_replace('/\{([^\/]+)\}/', '([^\/]+)', $pattern);
            if (preg_match('/^' . $pattern . '$/', $uri, $matches) && in_array($method, $config['methods'])) {
                $route = $config;
                array_shift($matches);
                $route['params'] = $matches;
            }
        }
        return $route;
    }
}