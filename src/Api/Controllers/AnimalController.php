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

    public function handlerAnimal(String $method, String $id): void
    {
        switch ($method) {
            case 'GET':
                $id == null ? $this->getAnimals(): $this->getOneAnimal($id);
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
}