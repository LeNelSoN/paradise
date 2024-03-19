<?php

namespace Api\Controllers;

use Api\Utils\Response;
use Api\Models\Alimentation;
use Api\Services\AlimentationService;
use Api\DTO\AlimentationDTO;

/**
 * Class AlimentationController
 * @package Api\Controllers
 * 
 * This class is responsible for handling the requests and responses for the Alimentation resource
 * 
 */
class AlimentationController
{
    private AlimentationService $alimentationService;

    /**
     * Constructor
     * 
     * Instantiates the AlimentationService
     * 
     */
    public function __construct()
    {
        $this->alimentationService = new AlimentationService();
    }

    /**
     * Handler Alimentation
     * 
     * This method is responsible for handling the requests and responses for the Alimentation resource
     * 
     * @param String $method
     * @param array $params
     * 
     * @return void
     */
    public function handlerAlimentation(String $method, array $params): void
    {
        $id = null;
        if (isset($params[0])) {
            $id = $params[0];
        }
        switch ($method) {
            case 'GET':
                $id == null ? $this->getAlimentations(): $this->getOneAlimentation($id);
                break;
            case 'POST':
                $this->createAlimentation();
                break;

            case 'PUT':
                if($id != null){
                    $this->updateAlimentation($id);
                } else {
                    throw new Exception("Invalid data provided for updating alimentation", 400);
                }
                break;
            case 'DELETE':
                if($id != null){
                    $this->deleteAlimentation($id);
                } else {
                    throw new Exception("Invalid data provided for deleting alimentation", 400);
                }
                break;
        }
    }
    /**
     * Get Alimentations
     * 
     * This method is responsible for getting all alimentations
     * 
     * @return void
     */
    private function getAlimentations(): void
    {
        $alimentations = $this->alimentationService->getAll();
        $alimentationsDTO = AlimentationDTO::alimentationToDTOArray($alimentations);

        Response::sendJson($alimentationsDTO);
    }

    private function getOneAlimentation(String $id): void
    {
        $alimentation = $this->alimentationService->getOne($id);
        $alimentationDTO = AlimentationDTO::alimentationToDTO($alimentation);

        Response::sendJson($alimentationDTO);
    }

    /**
     * Create Alimentation
     * 
     * This method is responsible for creating a new alimentation
     * 
     * @return void
     */
    private function createAlimentation() : void {
        $requestData = json_decode(file_get_contents('php://input'), true);

        if (!isset($requestData['name'])) {
            throw new Exception("Invalid data provided for creating alimentation", 400);
        }

        $alimentation = AlimentationDTO::requestToAlimentation($requestData);
        $newAlimentation = $this->alimentationService->create($alimentation);

        $alimentationDTO = AlimentationDTO::alimentationToDTO($newAlimentation);
        Response::sendJson($alimentationDTO);
    }

    /**
     * Update Alimentation
     * 
     * This method is responsible for updating an alimentation
     * 
     * @param String $id
     * 
     * @return void
     */
    private function updateAlimentation(String $id) : void 
    {
        $requestData = json_decode(file_get_contents('php://input'), true);

        $alimentation = AlimentationDTO::requestToAlimentation($requestData, $id);
        $updateAlimentation = $this->alimentationService->update($id, $alimentation);

        $alimentationDTO = AlimentationDTO::alimentationToDTO($updateAlimentation);
        Response::sendJson($alimentationDTO);
    }

    /**
     * Delete Alimentation
     * 
     * This method is responsible for deleting an alimentation
     * 
     * @param String $id
     * 
     * @return void
     */
    private function deleteAlimentation(String $id) : void 
    {
        $this->alimentationService->delete($id);
        $message = ["message" => "Alimentation $id deleted successfully"];
        Response::sendJson($message, 200);
    }

}