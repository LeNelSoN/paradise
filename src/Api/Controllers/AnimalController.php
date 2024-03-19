<?php

namespace Api\Controllers;

use Api\Services\AnimalService;
use Api\Utils\Response;
use Api\DTO\AnimalDTO;
use Api\Models\Animal;

/**
 * Class AnimalController
 * @package Api\Controllers
 * 
 * This class is responsible for handling the requests and responses for the Animal resource
 */
class AnimalController
{
    private AnimalService $animalService;

    /**
     * Constructor
     * 
     * Instantiates the AnimalService
     * 
     */
    public function __construct()
    {
        $this->animalService = new AnimalService();
    }

    /**
     * Handler Animal
     * 
     * This method is responsible for handling the requests and responses for the Animal resource
     * 
     * @param String $method
     * @param array $params
     * 
     * @return void
     */
    public function handlerAnimal(String $method, array $params): void
    {
        $id = null;
        if (isset($params[0])) {
            $id = $params[0];
        }
        switch ($method) {
            case 'GET':
                $id == null ? $this->getAnimals(): $this->getOneAnimal($id);
                break;
            case 'POST':
                $this->createOneAnimal();
                break;

            case 'PUT':
                if($id != null){
                    $this->updateOneAnimal($id);
                } else {
                    throw new Exception("Invalid data provided for updating animal", 400);
                }
                break;
            case 'DELETE':
                if($id != null){
                    $this->deleteOneAnimal($id);
                } else {
                    throw new Exception("Invalid data provided for deleting animal", 400);
                }
                break;
        }
    }

    /**
     * Get Animals
     * 
     * This method is responsible for getting all the animals
     * 
     * @return void
     */
    private function getAnimals(): void
    {
        $animals = $this->animalService->getAll();
        $animalsDTO = AnimalDTO::animalToDTOArray($animals);

        Response::sendJson($animalsDTO);
    }

    /**
     * Get One Animal
     * 
     * This method is responsible for getting one animal
     * 
     * @param String $id
     * 
     * @return void
     */
    private function getOneAnimal(String $id): void
    {
        $animal = $this->animalService->getOne($id);
        $animalDTO = AnimalDTO::animalToDTO($animal);

        Response::sendJson($animalDTO);
    }

    /**
     * Create One Animal
     * 
     * This method is responsible for creating one animal
     * 
     * @return void
     */
    private function createOneAnimal() : void {
        $requestData = json_decode(file_get_contents('php://input'), true);

        if (!isset($requestData['name'])) {
            throw new Exception("Invalid data provided for creating animal", 400);
        }

        $animal = AnimalDTO::requestDataToAnimal($requestData);
        $newAnimal = $this->animalService->create($animal);

        $animalDTO = AnimalDTO::animalToDTO($newAnimal);
        Response::sendJson($animalDTO);
    }

    /**
     * Update One Animal
     * 
     * This method is responsible for updating one animal
     * 
     * @param String $id
     * 
     * @return void
     */
    private function updateOneAnimal(String $id) : void 
    {
        $requestData = json_decode(file_get_contents('php://input'), true);

        $animal = AnimalDTO::requestDataToAnimal($requestData, $id);
        $updateAnimal = $this->animalService->update($id, $animal);

        $animalDTO = AnimalDTO::animalToDTO($updateAnimal);
        Response::sendJson($animalDTO);
    }

    /**
     * Delete One Animal
     * 
     * This method is responsible for deleting one animal
     * 
     * @param String $id
     * 
     * @return void
     */
    private function deleteOneAnimal(String $id) : void 
    {
        $this->animalService->delete($id);
        $message = ["message" => "Animal with id $id deleted successfully"];
        Response::sendJson($message, 200);
    }

}