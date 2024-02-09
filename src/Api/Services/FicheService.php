<?php
namespace Api\Services;

use PDO;
use Api\Utils\ServiceHelper;
use Api\Services\DAO;
use Api\Models\Fiche;
use Api\Models\Classe;
use Api\Models\ZoneGeographique;
use Api\Models\Alimentation;
use PDOException;
use Exception;
use DateTime;

final class FicheService extends DAO
{
        private static $pdo;
        private String $query;
        private array $params;

        public function __construct()
        {
            self::$pdo = self::getPDO();
        }
    
        /**
        * Fetches all fiches from the database.
        *
        * @return Fiche[] An array of Fiche objects representing all fiches.
        */
        public function getAll(): array
        {
            $query = "SELECT fiche.*, classe.description AS classe_description, zone_geographique.description AS zone_geographique_description, alimentation.description AS alimentation_description FROM fiche LEFT JOIN classe ON fiche.classe = classe.classe_name LEFT JOIN zone_geographique ON fiche.zone_geographique = zone_geographique.zone_geographique_name LEFT JOIN alimentation ON fiche.alimentation = alimentation.alimentation_name;";
                        
            try {
                $statement = $this->getPDO()->query($query);
            } catch (PDOException $PDOException) {
                throw new Exception("Error Processing Request : ".$PDOException->message, 500);
            }
            
            $fichesData = $statement->fetchAll(PDO::FETCH_ASSOC);
            $fiches = [];
    
            foreach ($fichesData as $data) {
                $fiche = $this->dataToFiche($data);
                $fiches[] = $fiche;
            }
    
            return $fiches;
        }
        
        /**
         * Fetches a fiche from the database.
         * 
         * @param String $id The name of the specie.
         * 
         * @return Fiche The Fiche object representing the fiche.
         */
        public function getOne(mixed $id): Fiche
        {
            $query = "SELECT fiche.*, classe.description AS classe_description, zone_geographique.description AS zone_geographique_description, alimentation.description AS alimentation_description FROM fiche LEFT JOIN classe ON fiche.classe = classe.classe_name LEFT JOIN zone_geographique ON fiche.zone_geographique = zone_geographique.zone_geographique_name LEFT JOIN alimentation ON fiche.alimentation = alimentation.alimentation_name WHERE specie_name = :id;";
            try {
                $statement = $this->getPDO()->prepare($query);
                $statement->bindParam(':id', $id);
                $statement->execute();
            } catch (PDOException $PDOException) {
                throw new Exception("Error Processing Request : ".$PDOException->message, 500);
            }
            $data = $statement->fetch(PDO::FETCH_ASSOC);
            
            if ($data) {
                return $this->dataToFiche($data);
            } else {
                throw new Exception("Fiche not found", 404);
            }
        }

        public function create(mixed $fiche): Fiche
        {
            $this->query = "INSERT INTO fiche (specie_name, ";
            $this->params = [$fiche->getSpecieName()];

            $collumns = $fiche->getCollumns();

            $this->addDataToCreateQuery($fiche->getClasseId(), $collumns[1]);
            $this->addDataToCreateQuery($fiche->getAlimentationId(), $collumns[2]);
            $this->addDataToCreateQuery($fiche->getPoidMoyen(), $collumns[3]);
            $this->addDataToCreateQuery($fiche->getLongevite(), $collumns[4]);
            $this->addDataToCreateQuery($fiche->getLongueur(), $collumns[5]);
            $this->addDataToCreateQuery($fiche->getTaille(), $collumns[6]);
            $this->addDataToCreateQuery($fiche->getZoneGeographiqueId(), $collumns[7]);
            $this->addDataToCreateQuery($fiche->getDescription(), $collumns[8]);
            
            $this->query = rtrim($this->query, ', ');
            $this->query .= ") ";

            $this->query .= ServiceHelper::generateSQLPrepareValues($this->params);

            try {
                $statement = $this->getPDO()->prepare($this->query);
                $statement->execute($this->params);
            } catch (PDOException $PDOException) {
                throw new Exception("Error creating specie fiche : ".$PDOException->message, 500);
            }

            $lastId = $this->getPDO()->lastInsertId();

            return $this->getOne($lastId);
        }

        public function update(mixed $id, mixed $data): void
        {
            $query = "UPDATE fiche SET specie_name = :specie_name, classe = :classe, alimentation = :alimentation, poidsMoyen = :poidsMoyen, longevite = :longevite, longueur = :longueur, taille = :taille, zone_geographique = :zone_geographique, description = :description WHERE specie_name = :id;";
            try {
                $statement = $this->getPDO()->prepare($query);
                $statement->bindParam(':specie_name', $data['specie_name']);
                $statement->bindParam(':classe', $data['classe']);
                $statement->bindParam(':alimentation', $data['alimentation']);
                $statement->bindParam(':poidsMoyen', $data['poidsMoyen']);
                $statement->bindParam(':longevite', $data['longevite']);
                $statement->bindParam(':longueur', $data['longueur']);
                $statement->bindParam(':taille', $data['taille']);
                $statement->bindParam(':zone_geographique', $data['zone_geographique']);
                $statement->bindParam(':description', $data['description']);
                $statement->bindParam(':id', $id);
                $statement->execute();
            } catch (PDOException $PDOException) {
                throw new Exception("Error Processing Request : ".$PDOException->message, 500);
            }
        }

        public function delete(mixed $id): void
        {
            $query = "DELETE FROM fiche WHERE specie_name = :id;";
            try {
                $statement = $this->getPDO()->prepare($query);
                $statement->bindParam(':id', $id);
                $statement->execute();
            } catch (PDOException $PDOException) {
                throw new Exception("Error Processing Request : ".$PDOException->message, 500);
            }
        }

        private function dataToFiche($data): Fiche
        {
            $fiche = new Fiche(
                $data['specie_name']
            );

            if ($data['classe'] !== null) {
                $fiche->setClasse(new Classe($data['classe'], $data['classe_description']));
            }
            if ($data['alimentation'] !== null) {
                $fiche->setAlimentation(new Alimentation($data['alimentation'], $data['alimentation_description']));
            }
            if ($data['poidsMoyen'] !== null) {
                $fiche->setPoidMoyen($data['poidsMoyen']);
            }
            if ($data['longevite'] !== null) {
                $fiche->setLongevite($data['longevite']);
            }
            if ($data['longueur'] !== null) {
                $fiche->setLongueur($data['longueur']);
            }
            if ($data['taille'] !== null) {
                $fiche->setTaille($data['taille']);
            }
            if ($data['zone_geographique'] !== null) {
                $fiche->setZoneGeographique(new ZoneGeographique($data['zone_geographique'], $data['zone_geographique_description']));
            }

            if ($data['description'] !== null) {
                $fiche->setDescription($data['description']);
            }

            return $fiche;
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