<?php

namespace Api\Controllers;

use Api\Utils\Response;
use Api\Models\Classe;
use Api\Services\ClasseService;
use Api\DTO\ClasseDTO;


class ClasseController
{
    private ClasseService $classeService;

    public function __construct()
    {
        $this->classeService = new ClasseService();
    }


    public function handlerClasse(String $method, array $params): void
    {
        $id = null;
        if (isset($params[0])) {
            $id = $params[0];
        }
        switch ($method) {
            case 'GET':
                $id == null ? $this->getClasses(): $this->getOneClasse($id);
                break;
            case 'POST':
                $this->createClasse();
                break;

            case 'PUT':
                if($id != null){
                    $this->updateClasse($id);
                } else {
                    throw new Exception("Invalid data provided for updating Classe", 400);
                }
                break;
            case 'DELETE':
                if($id != null){
                    $this->deleteClasse($id);
                } else {
                    throw new Exception("Invalid data provided for deleting Classe", 400);
                }
                break;
        }
    }

    private function getClasses(): void
    {
        $classes = $this->classeService->getAll();
        $classesDTO = ClasseDTO::classeToDTOArray($classes);

        Response::sendJson($classesDTO);
    }

    private function getOneClasse(String $id): void
    {
        $classe = $this->classeService->getOne($id);
        $classeDTO = ClasseDTO::classeToDTO($classe);

        Response::sendJson($classeDTO);
    }


    private function createClasse() : void {
        $requestData = json_decode(file_get_contents('php://input'), true);

        if (!isset($requestData['name'])) {
            throw new Exception("Invalid data provided for creating Classe", 400);
        }

        $classe = ClasseDTO::requestToClasse($requestData);
        $newClasse = $this->classeService->create($classe);

        $classeDTO = ClasseDTO::classeToDTO($newClasse);
        Response::sendJson($classeDTO);
    }


    private function updateClasse(String $id) : void 
    {
        $requestData = json_decode(file_get_contents('php://input'), true);

        $classe = ClasseDTO::requestToClasse($requestData, $id);
        $updateClasse = $this->classeService->update($id, $classe);

        $classeDTO = ClasseDTO::classeToDTO($updateClasse);
        Response::sendJson($classeDTO);
    }


    private function deleteClasse(String $id) : void 
    {
        $this->classeService->delete($id);
        $message = ["message" => "Classe $id deleted successfully"];
        Response::sendJson($message, 200);
    }

}