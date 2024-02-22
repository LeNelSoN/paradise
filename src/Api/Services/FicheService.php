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
                throw new Exception("Error Processing Request : ".$PDOException->getMessage(), 500);
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
                throw new Exception("Error Processing Request : ".$PDOException->getMessage(), 500);
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
            $this->addDataToCreateQuery($fiche->getPoidsMoyen(), $collumns[3]);
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
                return $this->getOne($fiche->getSpecieName());
            } catch (PDOException $PDOException) {
                throw new Exception("Error creating specie fiche : ".$PDOException->getMessage(), 500);
            }

            $lastId = $this->getPDO()->lastInsertId();

            return $this->getOne($lastId);
        }
        /**
         * Updates a fiche in the database.
         * 
         * @param String $id The name of the specie.
         * @param Fiche $fiche The Fiche object representing the fiche.
         */
        public function update(mixed $id, mixed $data): Fiche
        {
            $this->query = "UPDATE fiche SET";
            $this->params = [];

            $collumns = $data->getCollumns();

            $this->addDataToUpdateQuery($data->getClasseId(), $collumns[1]);
            $this->addDataToUpdateQuery($data->getAlimentationId(), $collumns[2]);
            $this->addDataToUpdateQuery($data->getPoidsMoyen(), $collumns[3]);
            $this->addDataToUpdateQuery($data->getLongevite(), $collumns[4]);
            $this->addDataToUpdateQuery($data->getLongueur(), $collumns[5]);
            $this->addDataToUpdateQuery($data->getTaille(), $collumns[6]);
            $this->addDataToUpdateQuery($data->getZoneGeographiqueId(), $collumns[7]);
            $this->addDataToUpdateQuery($data->getDescription(), $collumns[8]);

            $this->query = rtrim($this->query, ',');

            $this->query .= " WHERE specie_name = ?";
            $this->params[] = $id;

            try {
                $statement = $this->getPDO()->prepare($this->query);
                for ($i=0; $i < count($this->params) ; $i++) { 
                    $statement->bindParam($i+1, $this->params[$i], PDO::PARAM_STR);
                }
                $statement->execute();

                return $this->getOne($id);
            } catch (PDOException $PDOException) {
                throw new Exception("Error Processing Request : ".$PDOException->getMessage(), 500);
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
                throw new Exception("Error Processing Request : ".$PDOException->getMessage(), 500);
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
                $fiche->setPoidsMoyen($data['poidsMoyen']);
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