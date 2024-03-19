<?php
namespace Api\Services;

use PDO;
use Api\Utils\ServiceHelper;
use Api\Services\DAO;
use Api\Models\Alimentation;
use PDOException;
use Exception;

final class AlimentationService extends DAO
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
        $query = "SELECT * FROM alimentation";
                    
        try {
            $statement = $this->getPDO()->query($query);
        } catch (PDOException $PDOException) {
            throw new Exception("Error Processing Request : ".$PDOException->getMessage(), 500);
        }
        
        $alimentationsData = $statement->fetchAll(PDO::FETCH_ASSOC);
        $alimentations = [];

        foreach ($alimentationsData as $data) {
            $alimentation = $this->dataToAlimentation($data);
            $alimentations[] = $alimentation;
        }

        return $alimentations;
    }

    public function getOne(mixed $id): Alimentation
    {
        $query = "SELECT * FROM alimentation WHERE alimentation_name = ?;";
        
        try {
            $statement = $this->getPDO()->prepare($query);
            $statement->bindParam(1, $id, PDO::PARAM_INT);
            $statement->execute();
        } catch (PDOException $PDOException) {
            throw new Exception("Error Processing Request : ".$PDOException->getMessage(), 500);
        }

        $animalData = $statement->fetch(PDO::FETCH_ASSOC);

        if ($animalData) {
            return $this->dataToAlimentation($animalData);
        } else {
            throw new Exception("Alimentation not found", 404);
        }
    }

    public function create($alimentation): Alimentation
    {

        $this->query = "INSERT INTO alimentation (alimentation_name, ";
        
        $this->params = [strtolower($alimentation->getName())];
        
        $this->addDataToCreateQuery($alimentation->getDescription(), $alimentation->getCollumnDescription());

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
            throw new Exception("Error creating alimentation: ".$PDOException->getMessage(), 500);
        }

        $lastInsertId = $this->getPDO()->lastInsertId();
        return $this->getOne($lastInsertId);
    }

    public function update(mixed $id, mixed $alimentation): Alimentation
    {
        $this->query = "UPDATE alimentation SET";
        $this->params = [];
    
        $this->addDataToUpdateQuery(strtolower($alimentation->getName()), $alimentation->getCollumnName());

        if($alimentation->getDescription() !== null){
            $this->addDataToUpdateQuery($alimentation->getDescription(), $alimentation->getCollumnDescription());
        }

        $this->query = rtrim($this->query, ',');
    
        $this->query .= " WHERE alimentation_name = ?";
    
        $this->params[] = $id;
    
        try {
            $statement = $this->getPDO()->prepare($this->query);
            for ($i=0; $i < count($this->params) ; $i++) { 
                $statement->bindParam($i+1, $this->params[$i], PDO::PARAM_STR);
            }
            $statement->execute();
        } catch (PDOException $PDOException) {
            throw new Exception("Error updating alimentation: ".$PDOException->getMessage(), 500);
        }
    
        return $this->getOne($id);
    }

    public function delete(mixed $id): void
    {
        $query = "DELETE FROM alimentation WHERE alimentation_name = ?";

        try {
            $statement = $this->getPDO()->prepare($query);
            $statement->bindParam(1, $id, PDO::PARAM_STR);
            $statement->execute();
        } catch (PDOException $PDOException) {
            throw new Exception("Error deleting alimentation: ".$PDOException->getMessage(), 500);
        }
    }

    private function dataToAlimentation($data): Alimentation
    {
        $alimentation = new Alimentation($data['alimentation_name']);
        
        if (isset($data['description'])) {
            $alimentation->setDescription($data['description']);
        }

        return $alimentation;
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