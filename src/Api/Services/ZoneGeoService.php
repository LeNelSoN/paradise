<?php
namespace Api\Services;

use PDO;
use Api\Utils\ServiceHelper;
use Api\Utils\StringUtils;
use Api\Services\DAO;
use Api\Models\ZoneGeographique;
use PDOException;
use Exception;

final class ZoneGeoService extends DAO
{

    private static $pdo;
    private String $query;
    private array $params;

    public function __construct()
    {
        self::$pdo = self::getPDO();
    }

    public function getAll(): array
    {
        $query = "SELECT * FROM zone_geographique";
                    
        try {
            $statement = $this->getPDO()->query($query);
        } catch (PDOException $PDOException) {
            throw new Exception("Error Processing Request : ".$PDOException->getMessage(), 500);
        }
        
        $zonesData = $statement->fetchAll(PDO::FETCH_ASSOC);
        $zones = [];

        foreach ($zonesData as $data) {
            $zone = $this->dataToZoneGeo($data);
            $zones[] = $zone;
        }

        return $zones;
    }

    public function getOne(mixed $id): ZoneGeographique
    {
        $query = "SELECT * FROM zone_geographique WHERE zone_geographique_name = ?;";
        
        try {
            $statement = $this->getPDO()->prepare($query);
            $statement->bindParam(1, $id, PDO::PARAM_INT);
            $statement->execute();
        } catch (PDOException $PDOException) {
            throw new Exception("Error Processing Request : ".$PDOException->getMessage(), 500);
        }

        $zoneData = $statement->fetch(PDO::FETCH_ASSOC);

        if ($zoneData) {
            return $this->dataToZoneGeo($zoneData);
        } else {
            throw new Exception("Zone geographique not found", 404);
        }
    }

    public function create(mixed $zoneGeo): ZoneGeographique
    {

        $this->query = "INSERT INTO zone_geographique (zone_geographique_name, ";

        $this->params = [$zoneGeo->getName()];
        
        $this->addDataToCreateQuery($zoneGeo->getDescription(), $zoneGeo->getCollumnDescription());

        $this->query = rtrim($this->query, ', ');
        $this->query .= ") ";

        $this->query .= ServiceHelper::generateSQLPrepareValues($this->params);

        try {
            $statement = $this->getPDO()->prepare($this->query);
            for ($i=0; $i < count($this->params) ; $i++) { 
                $statement->bindParam($i+1, $this->params[$i], PDO::PARAM_STR);
            }
            $statement->execute();
        } catch (PDOException $PDOException) {
            throw new Exception("Error creating zone geographique: ".$PDOException->getMessage(), 500);
        }

        $lastInsertId = $this->getPDO()->lastInsertId();
        return $this->getOne($lastInsertId);
    }

    public function update(mixed $id, mixed $zoneGeo): ZoneGeographique
    {
        $this->query = "UPDATE zone_geographique SET";
        $this->params = [];
    
        if($zoneGeo->getName() !== null){
            $this->addDataToUpdateQuery($zoneGeo->getName(), $zoneGeo->getCollumnName());
        }

        if($zoneGeo->getDescription() !== null){
            $this->addDataToUpdateQuery($zoneGeo->getDescription(), $zoneGeo->getCollumnDescription());
        }

        $this->query = rtrim($this->query, ',');
    
        $this->query .= " WHERE zone_geographique_name = ?";
    
        $this->params[] = $id;
    
        try {
            $statement = $this->getPDO()->prepare($this->query);
            for ($i=0; $i < count($this->params) ; $i++) { 
                $statement->bindParam($i+1, $this->params[$i], PDO::PARAM_STR);
            }
            $statement->execute();
        } catch (PDOException $PDOException) {
            throw new Exception("Error updating zone geographique: ".$PDOException->getMessage(), 500);
        }
    
        return $this->getOne($id);
    }

    public function delete(mixed $id): void
    {
        $query = "DELETE FROM zone_geographique WHERE zone_geographique_name = ?";

        try {
            $statement = $this->getPDO()->prepare($query);
            $statement->bindParam(1, $id, PDO::PARAM_STR);
            $statement->execute();
        } catch (PDOException $PDOException) {
            throw new Exception("Error deleting zone geographique: ".$PDOException->getMessage(), 500);
        }
    }

    private function dataToZoneGeo(array $data): ZoneGeographique
    {
        $zoneGeo = new ZoneGeographique($data['zone_geographique_name']);

        if (isset($data['description'])) {
            $zoneGeo->setDescription($data['description']);
        }

        return $zoneGeo;
    }

    private function addDataToCreateQuery($data, $collumn)
    {
        if ($data !== null) {
            $this->query .= " $collumn ,";
            $this->params[] = $data;
        }
    }

    private function addDataToUpdateQuery($data, $collumn)
    {
        if ($data !== null) {
            $this->query .= " $collumn = ?,";
            $this->params[] = $data;
        }
    }
}