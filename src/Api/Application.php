<?php
namespace Api;

use Api\Utils\Response;
use Api\Utils\Error;
use Api\Controllers\HelloController;
use Api\Controllers\AnimalController;
use Dotenv\Dotenv;
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
        $dotenv = Dotenv::createImmutable('/var/www/html');
        $dotenv->safeLoad();

        define('DB_HOST', $_ENV['DB_HOST']);
        define('DB_NAME', $_ENV['DB_NAME']);
        define('DB_USER', $_ENV['DB_USER']);
        define('DB_PASS', $_ENV['DB_PASS']);
        
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