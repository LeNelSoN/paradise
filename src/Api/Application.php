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

    public function run()
    {
        define('DB_HOST', 'localhost:3306');
        define('DB_NAME', 'paradise_db');
        define('DB_USER', 'root');
        define('DB_PASS', 'password');
        
        try{
            $this->handleRoutes();
        }catch (Exception $e){
            Error::handleException($e);
        }
    }

    private function handleRoutes()
    {
        $route = filter_var($_SERVER['REQUEST_URI'], FILTER_SANITIZE_URL);

        if ($route == '/hello') 
        {
            HelloController::hello();
        }
        if ($route == '/animals') 
        {
            $animalController = new AnimalController();
            $animalController->getAnimals();
        }
    }

}