<?php

namespace Api\Controllers;

use Api\Services\AnimalService;
use Api\Utils\Response;
use Api\DTO\AnimalDTO;

class AnimalController
{
    private $animalService;

    public function __construct()
    {
        $this->animalService = new AnimalService();
    }

    public function getAnimals(): void
    {
        $animals = $this->animalService->getAll();
        $animalsDTO = [];

        foreach ($animals as $animal) {
            $animalsDTO[] = AnimalDTO::animalToDTO($animal);
        }

        Response::sendJson($animalsDTO);
    }
}