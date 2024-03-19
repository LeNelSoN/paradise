<?php

namespace Api\Controllers;

use Api\Utils\Response;
use Api\Models\ZoneGeographique;
use Api\Services\ZoneGeoService;
use Api\DTO\ZoneGeographiqueDTO;


class ZoneGeoController
{
    private ZoneGeoService $zoneGeoService;

    public function __construct()
    {
        $this->zoneGeoService = new ZoneGeoService();
    }


    public function handlerClasse(String $method, array $params): void
    {
        $id = null;
        if (isset($params[0])) {
            $id = $params[0];
        }
        switch ($method) {
            case 'GET':
                $id == null ? $this->getZonesGeo(): $this->getOneZoneGeo($id);
                break;
            case 'POST':
                $this->createZoneGeo();
                break;

            case 'PUT':
                if($id != null){
                    $this->updateZoneGeo($id);
                } else {
                    throw new Exception("Invalid data provided for updating Zone geographique", 400);
                }
                break;
            case 'DELETE':
                if($id != null){
                    $this->deleteZoneGeo($id);
                } else {
                    throw new Exception("Invalid data provided for deleting Zone geographique", 400);
                }
                break;
        }
    }

    private function getZonesGeo(): void
    {
        $zonesGeo = $this->zoneGeoService->getAll();
        $zonesGeoDTO = ZoneGeographiqueDTO::zoneGeoToDTOArray($zonesGeo);

        Response::sendJson($zonesGeoDTO);
    }

    private function getOneZoneGeo(String $id): void
    {
        $zoneGeo = $this->zoneGeoService->getOne($id);
        $zoneGeoDTO = ZoneGeographiqueDTO::zoneGeographiqueToDTO($zoneGeo);

        Response::sendJson($zoneGeoDTO);
    }


    private function createZoneGeo() : void {
        $requestData = json_decode(file_get_contents('php://input'), true);

        if (!isset($requestData['name'])) {
            throw new Exception("Invalid data provided for creating Zone geographique", 400);
        }

        $zoneGeo = ZoneGeographiqueDTO::requestToZoneGeo($requestData);
        $newZoneGeo = $this->zoneGeoService->create($zoneGeo);

        $zoneGeoDTO = ZoneGeographiqueDTO::zoneGeographiqueToDTO($newZoneGeo);
        Response::sendJson($zoneGeoDTO);
    }


    private function updateZoneGeo(String $id) : void 
    {
        $requestData = json_decode(file_get_contents('php://input'), true);
        $zoneGeo = ZoneGeographiqueDTO::requestToZoneGeo($requestData, $id);
        $updatedZoneGeo = $this->zoneGeoService->update($id, $zoneGeo);
        $zoneGeoDTO = ZoneGeographiqueDTO::zoneGeographiqueToDTO($updatedZoneGeo);
        Response::sendJson($zoneGeoDTO);
    }


    private function deleteZoneGeo(String $id) : void 
    {
        $this->zoneGeoService->delete($id);
        Response::sendJson("Zone geographique deleted successfully");
    }

}