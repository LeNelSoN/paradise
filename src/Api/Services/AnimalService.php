<?php
namespace Api\Services;

use PDO;
use Api\Services\DAO;
use Api\Models\Animal;
use PDOException;
use Exception;
use DateTime;

final class AnimalService extends DAO
{

    private static $pdo;

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
        $query = "SELECT * FROM animal";
        try {
            $statement = $this->getPDO()->query($query);
        } catch (PDOException $PDOException) {
            throw new Exception("Error Processing Request : ".$PDOException->message, 500);
        }
        

        $animalsData = $statement->fetchAll(PDO::FETCH_ASSOC);
        $animals = [];

        foreach ($animalsData as $data) {
            $animal = new Animal(
                $data['Id'],
                $data['name'],
                $data['specie'],
                $data['birthday'],
                $data['description']
            );

            $animals[] = $animal;
        }

        return $animals;
    }

    public function getOne(String $id): Animal
    {
        $query = "SELECT * FROM animal WHERE Id = ?";
        try {
            $statement = $this->getPDO()->prepare($query);
            $statement->bindParam(1, $id, PDO::PARAM_INT);
            $statement->execute();
        } catch (PDOException $PDOException) {
            throw new Exception("Error Processing Request : ".$PDOException->message, 500);
        }

        $animalData = $statement->fetch(PDO::FETCH_ASSOC);

        if ($animalData) {
            return new Animal(
                $animalData['Id'],
                $animalData['name'],
                $animalData['specie'],
                $animalData['birthday'],
                $animalData['description']
            );
        } else {
            throw new Exception("Animal not found", 404);
        }
    }

    public function create(string $name, string $specie, string $birthday, string $description = ""): Animal
    {
        $formattedBirthday = (new DateTime(str_replace('/', '-', $birthday)))->format('Y-m-d');

        $query = "INSERT INTO animal (name, specie, birthday, description) VALUES (?, ?, ?, ?)";

        try {
            $statement = $this->getPDO()->prepare($query);
            $statement->bindParam(1, $name, PDO::PARAM_STR);
            $statement->bindParam(2, $specie, PDO::PARAM_STR);
            $statement->bindParam(3, $formattedBirthday, PDO::PARAM_STR);
            $statement->bindParam(4, $description, PDO::PARAM_STR);
            $statement->execute();
        } catch (PDOException $PDOException) {
            throw new Exception("Error creating animal: ".$PDOException->getMessage(), 500);
        }

        $lastInsertId = $this->getPDO()->lastInsertId();
        return $this->getOne($lastInsertId);
    }

    public function update(string $id, string $name, string $specie, string $birthday, string $description = ""): Animal
    {
        $formattedBirthday = (new DateTime(str_replace('/', '-', $birthday)))->format('Y-m-d');

        $query = "UPDATE animal SET name = ?, specie = ?, birthday = ?, description = ? WHERE Id = ?";

        try {
            $statement = $this->getPDO()->prepare($query);
            $statement->bindParam(1, $name, PDO::PARAM_STR);
            $statement->bindParam(2, $specie, PDO::PARAM_STR);
            $statement->bindParam(3, $formattedBirthday, PDO::PARAM_STR);
            $statement->bindParam(4, $description, PDO::PARAM_STR);
            $statement->bindParam(5, $id, PDO::PARAM_INT);
            $statement->execute();
        } catch (PDOException $PDOException) {
            throw new Exception("Error updating animal: ".$PDOException->getMessage(), 500);
        }

        return $this->getOne($id);
    }
}