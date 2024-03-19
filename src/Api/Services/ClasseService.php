<?php
namespace Api\Services;

use PDO;
use Api\Utils\ServiceHelper;
use Api\Utils\StringUtils;
use Api\Services\DAO;
use Api\Models\Classe;
use PDOException;
use Exception;

final class ClasseService extends DAO
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
        $query = "SELECT * FROM classe";
                    
        try {
            $statement = $this->getPDO()->query($query);
        } catch (PDOException $PDOException) {
            throw new Exception("Error Processing Request : ".$PDOException->getMessage(), 500);
        }
        
        $classesData = $statement->fetchAll(PDO::FETCH_ASSOC);
        $classes = [];

        foreach ($classesData as $data) {
            $classe = $this->dataToClasse($data);
            $classes[] = $classe;
        }

        return $classes;
    }

    public function getOne(mixed $id): Classe
    {
        $query = "SELECT * FROM classe WHERE classe_name = ?;";
        
        try {
            $statement = $this->getPDO()->prepare($query);
            $statement->bindParam(1, $id, PDO::PARAM_INT);
            $statement->execute();
        } catch (PDOException $PDOException) {
            throw new Exception("Error Processing Request : ".$PDOException->getMessage(), 500);
        }

        $classeData = $statement->fetch(PDO::FETCH_ASSOC);

        if ($classeData) {
            return $this->dataToClasse($classeData);
        } else {
            throw new Exception("Classe not found", 404);
        }
    }

    public function create($classe): Classe
    {

        $this->query = "INSERT INTO classe (classe_name, ";

        $this->params = [$classe->getName()];
        
        $this->addDataToCreateQuery($classe->getDescription(), $classe->getCollumnDescription());

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
            throw new Exception("Error creating classe: ".$PDOException->getMessage(), 500);
        }

        $lastInsertId = $this->getPDO()->lastInsertId();
        return $this->getOne($lastInsertId);
    }

    public function update(mixed $id, mixed $classe): Classe
    {
        $this->query = "UPDATE classe SET";
        $this->params = [];
    
        $this->addDataToUpdateQuery(strtolower($classe->getName()), $classe->getCollumnName());

        if($classe->getDescription() !== null){
            $this->addDataToUpdateQuery($classe->getDescription(), $classe->getCollumnDescription());
        }

        $this->query = rtrim($this->query, ',');
    
        $this->query .= " WHERE classe_name = ?";
    
        $this->params[] = $id;
    
        try {
            $statement = $this->getPDO()->prepare($this->query);
            for ($i=0; $i < count($this->params) ; $i++) { 
                $statement->bindParam($i+1, $this->params[$i], PDO::PARAM_STR);
            }
            $statement->execute();
        } catch (PDOException $PDOException) {
            throw new Exception("Error updating classe: ".$PDOException->getMessage(), 500);
        }
    
        return $this->getOne($id);
    }

    public function delete(mixed $id): void
    {
        $query = "DELETE FROM classe WHERE classe_name = ?";

        try {
            $statement = $this->getPDO()->prepare($query);
            $statement->bindParam(1, $id, PDO::PARAM_STR);
            $statement->execute();
        } catch (PDOException $PDOException) {
            throw new Exception("Error deleting classe: ".$PDOException->getMessage(), 500);
        }
    }

    private function dataToClasse($data): Classe
    {
        return new Classe($data['classe_name'],$data['description']);
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