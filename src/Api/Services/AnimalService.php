<?php
namespace Api\Services;

use PDO;
use Api\Utils\ServiceHelper;
use Api\Services\DAO;
use Api\Models\Animal;
use Api\Models\Fiche;
use Api\Models\Classe;
use Api\Models\ZoneGeographique;
use Api\Models\Alimentation;
use Api\Models\Enclos;
use Api\Models\Zone;
use PDOException;
use Exception;
use DateTime;

/**
 * Service class for Animal.
 * 
 * This class is responsible for handling the business logic for Animal.
 * It is responsible for communicating with the database and for handling the data.
 * 
 * @category Service
 * @package  Api/Services
 * @author   Valentin Nelis
 */
final class AnimalService extends DAO
{

    private static $pdo;
    private String $query;
    private array $params;

    public function __construct()
    {
        self::$pdo = self::getPDO();
    }

    /**
     * Fetches all animals from the database.
     *
     * @return Animal[] An array of Animal objects representing all animals.
     */
    public function getAll(): array
    {
        $query = "SELECT animal.*,
                fiche.classe, 
                fiche.alimentation, 
                fiche.zone_geographique, 
                fiche.poidsMoyen, 
                fiche.longevite, 
                fiche.longueur, 
                fiche.taille, 
                fiche.description               AS fiche_description, 
                classe.description              AS classe_description, 
                zone_geographique.description   AS zone_geographique_description, 
                alimentation.description        AS alimentation_description,
                enclos.name                     AS enclos_name, 
                enclos.description              AS enclos_description,
                zone.Id                         AS zone_id, 
                zone.name                       AS zone_name, 
                zone.description                AS zone_description  
            FROM animal 
            LEFT JOIN fiche                 ON animal.specie = fiche.specie_name 
            LEFT JOIN classe                ON fiche.classe = classe.classe_name 
            LEFT JOIN zone_geographique     ON fiche.zone_geographique = zone_geographique.zone_geographique_name 
            LEFT JOIN alimentation          ON fiche.alimentation = alimentation.alimentation_name
            LEFT JOIN enclos                ON animal.enclos_id = enclos.Id 
            LEFT JOIN zone                  ON enclos.zone_id = zone.Id;";
                    
        try {
            $statement = $this->getPDO()->query($query);
        } catch (PDOException $PDOException) {
            throw new Exception("Error Processing Request : ".$PDOException->getMessage(), 500);
        }
        
        $animalsData = $statement->fetchAll(PDO::FETCH_ASSOC);
        $animals = [];

        foreach ($animalsData as $data) {
            $animal = $this->dataToAnimal($data);
            $animals[] = $animal;
        }

        return $animals;
    }

    /**
     * Fetches an animal from the database.
     * 
     * @param int $id The id of the animal.
     * 
     * @return Animal The Animal object representing the animal.
     */
    public function getOne(mixed $id): Animal
    {
        $query = "SELECT animal.*, 
                fiche.classe, 
                fiche.alimentation, 
                fiche.zone_geographique, 
                fiche.poidsMoyen, 
                fiche.longevite, 
                fiche.longueur, 
                fiche.taille, 
                fiche.description               AS fiche_description, 
                classe.description              AS classe_description, 
                zone_geographique.description   AS zone_geographique_description, 
                alimentation.description        AS alimentation_description, 
                enclos.name                     AS enclos_name, 
                enclos.description              AS enclos_description, 
                zone.Id                         AS zone_id, 
                zone.name                       AS zone_name, 
                zone.description                AS zone_description 
            FROM animal 
            LEFT JOIN fiche                 ON animal.specie = fiche.specie_name 
            LEFT JOIN classe                ON fiche.classe = classe.classe_name 
            LEFT JOIN zone_geographique     ON fiche.zone_geographique = zone_geographique.zone_geographique_name 
            LEFT JOIN alimentation          ON fiche.alimentation = alimentation.alimentation_name 
            LEFT JOIN enclos                ON animal.enclos_id = enclos.Id 
            LEFT JOIN zone                  ON enclos.zone_id = zone.Id 
            WHERE animal.Id = ?;";
        
        try {
            $statement = $this->getPDO()->prepare($query);
            $statement->bindParam(1, $id, PDO::PARAM_INT);
            $statement->execute();
        } catch (PDOException $PDOException) {
            throw new Exception("Error Processing Request : ".$PDOException->getMessage(), 500);
        }

        $animalData = $statement->fetch(PDO::FETCH_ASSOC);

        if ($animalData) {
            return $this->dataToAnimal($animalData);
        } else {
            throw new Exception("Animal not found", 404);
        }
    }

    /**
     * Creates an animal in the database.
     * 
     * @param array $data The data of the animal.
     * 
     * @return Animal The Animal object representing the created animal.
     */
    public function create($animal): Animal
    {

        $this->query = "INSERT INTO animal (name, ";
        $this->params = [$animal->getName()];
        
        $this->addDataToCreateQuery($animal->getSpecieId(), $animal->getCollumnSpecie());
        $this->addDataToCreateQuery(ServiceHelper::stringToDateTime($animal->getBirthday()), $animal->getCollumnBirthday());
        $this->addDataToCreateQuery($animal->getDescription(), $animal->getCollumnDescription());
        $this->addDataToCreateQuery($animal->getEnclosId(), $animal->getCollumnEnclos());

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
            throw new Exception("Error creating animal: ".$PDOException->getMessage(), 500);
        }

        $lastInsertId = $this->getPDO()->lastInsertId();
        return $this->getOne($lastInsertId);
    }

    /**
     * Updates an animal in the database.
     * 
     * @param int $id The id of the animal.
     * @param array $data The data of the animal.
     * 
     * @return Animal The Animal object representing the updated animal.
     */
    public function update(mixed $id, mixed $animal): Animal
    {
        $this->query = "UPDATE animal SET";
        $this->params = [];
    
        $this->addDataToUpdateQuery($animal->getName(), $animal->getCollumnName());
        $this->addDataToUpdateQuery($animal->getSpecieId(), $animal->getCollumnSpecie());
        $this->addDataToUpdateQuery(ServiceHelper::stringToDateTime($animal->getBirthday()), $animal->getCollumnBirthday());
        $this->addDataToUpdateQuery($animal->getDescription(), $animal->getCollumnDescription());
    
        $this->query = rtrim($this->query, ',');
    
        $this->query .= " WHERE Id = ?";
    
        $this->params[] = $id;
    
        try {
            $statement = $this->getPDO()->prepare($this->query);
            for ($i=0; $i < count($this->params) ; $i++) { 
                $statement->bindParam($i+1, $this->params[$i], PDO::PARAM_STR);
            }
            $statement->execute();
        } catch (PDOException $PDOException) {
            throw new Exception("Error updating animal: ".$PDOException->getMessage(), 500);
        }
    
        return $this->getOne($id);
    }

    /**
     * Deletes an animal from the database.
     * 
     * @param int $id The id of the animal.
     */
    public function delete(mixed $id): void
    {
        $query = "DELETE FROM animal WHERE Id = ?";

        try {
            $statement = $this->getPDO()->prepare($query);
            $statement->bindParam(1, $id, PDO::PARAM_INT);
            $statement->execute();
        } catch (PDOException $PDOException) {
            throw new Exception("Error deleting animal: ".$PDOException->getMessage(), 500);
        }
    }

    /**
     * Converts data from the database to an Animal object.
     * 
     * @param array $data The data from the database.
     * 
     * @return Animal The Animal object.
     */
    private function dataToAnimal($data): Animal
    {
        $animal = new Animal(
            $data['Id'],
            $data['name'],
            $data['birthday']);

        if (isset($data['description'])){
            $animal->setDescription($data['description']);
        }

        if (isset($data['enclos_id'])){
            $animal->setEnclos(
                new Enclos(
                    $data['enclos_id'],
                    $data['enclos_name'],
                    $data['enclos_description'],
                    new Zone(
                        $data['zone_id'],
                        $data['zone_name'],
                        $data['zone_description'])
                )
            );
        }

        if (isset($data['specie'])){
            $animal->setSpecie(
                new Fiche(
                    $data['specie'],
                    new Classe(
                        $data['classe'],
                        $data['classe_description']
                    ),
                    new Alimentation(
                        $data['alimentation'],
                        $data['alimentation_description']
                    ),
                    $data['poidsMoyen'],
                    $data['longevite'],
                    $data['longueur'],
                    $data['taille'],
                    new ZoneGeographique(
                        $data['zone_geographique'],
                        $data['zone_geographique_description']
                    ),
                    $data['fiche_description']
                ),
            );
        }

        return $animal;
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