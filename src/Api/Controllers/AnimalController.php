<?php

namespace Api\Controllers;

use Api\Services\AnimalService;
use Api\Utils\Response;
use Api\DTO\AnimalDTO;

class AnimalController
{
    private AnimalService $animalService;

    public function __construct()
    {
        $this->animalService = new AnimalService();
    }

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
        }
    }

    private function getAnimals(): void
    {
        $animals = $this->animalService->getAll();
        $animalsDTO = [];

        foreach ($animals as $animal) {
            $animalsDTO[] = AnimalDTO::animalToDTO($animal);
        }

        Response::sendJson($animalsDTO);
    }

    private function getOneAnimal(String $id): void
    {
        $animal = $this->animalService->getOne($id);
        $animalDTO = AnimalDTO::animalToDTO($animal);

        Response::sendJson($animalDTO);
    }

    private function createOneAnimal() : void {
        $requestData = json_decode(file_get_contents('php://input'), true);
        if (isset($requestData['name']) && isset($requestData['specie']) && isset($requestData['birthday'])){
            $description = isset($requestData['description']) ? $requestData['description'] : "";
            $animal = $this->animalService->create($requestData["name"], $requestData["specie"], $requestData["birthday"], $description);

            $animalDTO = AnimalDTO::animalToDTO($animal);
            Response::sendJson($animalDTO);
        } else {
            throw new Exception("Invalid data provided for creating animal", 400);
        }
    }

    private function updateOneAnimal(String $id) : void {
        $requestData = json_decode(file_get_contents('php://input'), true);
        if (isset($requestData['name']) && isset($requestData['specie']) && isset($requestData['birthday'])){
            $description = isset($requestData['description']) ? $requestData['description'] : "";
            $animal = $this->animalService->update($id, $requestData["name"], $requestData["specie"], $requestData["birthday"], $description);

            $animalDTO = AnimalDTO::animalToDTO($animal);
            Response::sendJson($animalDTO);
        } else {
            throw new Exception("Invalid data provided for updating animal", 400);
        }
    }
}