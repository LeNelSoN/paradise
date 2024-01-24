<?php
namespace Api\Services;

use PDO;
use Api\Services\DAO;
use Api\Models\Animal;

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
        $statement = $this->getPDO()->query($query);

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
}