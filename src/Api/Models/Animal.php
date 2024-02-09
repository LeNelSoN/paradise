<?php
namespace Api\Models;

use DateTime;
use Api\Utils\ServiceHelper;
use Api\Models\Enclos;
use Api\Models\Building;
use Api\Models\Fiche;

/**
 * Object model for table building
 * 
 * This class is used to represent a row from the table building.
 * 
 * @package Api\Models
 * @category Model
 * @author Valentin Nelis
 */
class Animal
{
    private int $id;
    private String $collumnId = "Id";
    private String $name;
    private String $collumnName = "name";
    private ?String $specieId = null;
    private ?Fiche $specie = null;
    private String $collumnSpecie = "specie";
    private ?DateTime $birthday = null;
    private String $collumnBirthday = "birthday";
    private ?String $description = null;
    private String $collumnDescription = "description";
    private ?int $enclosId = null;
    private ?Enclos $enclos = null;
    private String $collumnEnclos = "enclos_id";

    /**
     * Constructor for class Animal
     * 
     * @param int $id The id of the animal.
     * @param String $name The name of the animal.
     */
    public function __construct(int $id, String $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getCollumnId(): String
    {
        return $this->collumnId;
    }

    public function getName(): String
    {
        return $this->name;
    }

    public function getCollumnName(): String
    {
        return $this->collumnName;
    }

    public function getDescription(): ?String
    {
        return $this->description;
    }

    public function getCollumnDescription(): String
    {
        return $this->collumnDescription;
    }

    public function getSpecieId(): ?String
    {
        return $this->specieId;
    }

    public function getSpecie(): ?Fiche
    {
        return $this->specie;
    }

    public function getCollumnSpecie(): String
    {
        return $this->collumnSpecie;
    }

    public function getBirthday(): ?DateTime
    {
        return $this->birthday;
    }

    public function getCollumnBirthday(): String
    {
        return $this->collumnBirthday;
    }

    public function getEnclosId(): ?int
    {
        return $this->enclosId;
    }

    public function getEnclos(): ?Enclos
    {
        return $this->enclos;
    }

    public function getCollumnEnclos(): String
    {
        return $this->collumnEnclos;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setName(String $name): void
    {
        $this->name = $name;
    }

    public function setDescription(String $description): void
    {
        $this->description = $description;
    }

    public function setSpecieId(String $specieId): void
    {
        $this->specieId = $specieId;
    }

    public function setSpecie(Fiche $specie): void
    {
        $this->specie = $specie;
    }

    public function setBirthday(String $birthday): void
    {
        $this->birthday = ServiceHelper::stringToDateTime($birthday);
    }

    public function setEnclosId(int $enclosId): void
    {
        $this->enclosId = $enclosId;
    }

    public function setEnclos(Enclos $enclos): void
    {
        $this->enclos = $enclos;
    }

    public function __toString(): String
    {
        return "Animal [id: {$this->id}, name: {$this->name}, description: {$this->description}]";
    }
}
