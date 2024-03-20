<?php
namespace Api;

use Api\Utils\Response;
use Api\Utils\Error;
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

    /**
     * Constructor
     * 
     * Load the routes and the environment variables
     * 
     * @package Api
     * @author valentin
     */
    public function __construct()
    {
        $dotenv = Dotenv::createImmutable('/var/www/html');
        $dotenv->safeLoad();
        
        $this->routeList = require_once '/var/www/html/src/Api/Configuration/Routes.php';
    }
    
    /**
     * Run the application
     * 
     * @package Api
     * @category Application
     * @author valentin
     */
    public function run()
    {
        try{
            $this->handleRoutes();
        }catch (Exception $e){
            Error::handleException($e);
        }
    }
    
    /**
     * Handle the routes
     * 
     * @package Api
     */
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
                    $controller->$handler($method, $route['params']);
                }
            }
        } else {
            throw new Exception("Not Found", 404);
        }
    }

    /**
     * Match the route with the request
     * 
     * @param String $uri The request URI
     * @param String $method The request method
     * @return array The route configuration
     */
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