<?php
namespace Api\Controllers;

use Api\Models\Fiche;
use Api\Services\FicheService;
use Api\Utils\Response;
use Api\DTO\FicheDTO;

class FicheController
{
    private FicheService $ficheService;

    public function __construct()
    {
        $this->ficheService = new FicheService();
    }

    public function handlerFiche(String $method, array $params): void
    {
        $id = null;
        if (isset($params[0])) {
            $id = $params[0];
        }
        switch ($method) {
            case 'GET':
                $id == null ? $this->getFiches(): $this->getOneFiche($id);
                break;
            case 'POST':
                $this->createOneFiche();
                break;

            case 'PUT':
                if($id != null){
                    $this->updateOneFiche($id);
                } else {
                    throw new Exception("Invalid data provided for updating fiche", 400);
                }
                break;
            case 'DELETE':
                if($id != null){
                    $this->deleteOneFiche($id);
                } else {
                    throw new Exception("Invalid data provided for deleting fiche", 400);
                }
                break;
        }
    }

    private function getFiches(): void
    {
        $fiches = $this->ficheService->getAll();
        $fichesDTO = [];

        foreach ($fiches as $fiche) {
            $fichesDTO[] = FicheDTO::ficheToDTO($fiche);
        }

        Response::sendJson($fichesDTO);
    }

    private function getOneFiche(String $id): void
    {
        $fiche = $this->ficheService->getOne($id);
        $ficheDTO = FicheDTO::ficheToDTO($fiche);
        Response::sendJson($ficheDTO);
    }

    private function createOneFiche(): void
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $newFiche = Fiche::requestDataToFiche($data);

        $fiche = $this->ficheService->create($newFiche);
        $ficheDTO = FicheDTO::ficheToDTO($fiche);
        Response::sendJson($ficheDTO, 201);
    }

    private function updateOneFiche(String $id): void
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $fiche = Fiche::requestDataToFiche($data);
        $fiche->setSpecieName($id);

        $updatedFiche = $this->ficheService->update($id, $fiche);
        $ficheDTO = FicheDTO::ficheToDTO($updatedFiche);
        Response::sendJson($ficheDTO);
    }

    private function deleteOneFiche(String $id): void
    {
        $this->ficheService->delete($id);
        Response::sendJson(null, 204);
    }
}