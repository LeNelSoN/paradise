<?php
namespace Api\Models;

use Api\Models\Enclos;
use Api\Models\Building;

/**
 * Object model for table building
 */
class Animal
{
    private int $id;
    private String $name;
    private String $specie;
    private String $birthday;
    private String $description;

    public function __construct(int $id, String $name, String $specie, String $birthday, String $description)
    {
        $this->id = $id;
        $this->name = $name;
        $this->specie = $specie;
        $this->birthday = $birthday;
        $this->description = $description;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): String
    {
        return $this->name;
    }

    public function getDescription(): String
    {
        return $this->description;
    }

    public function getSpecie(): String
    {
        return $this->specie;
    }

    public function getBirthday(): String
    {
        return $this->birthday;
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

    public function setSpecie(String $specie): void
    {
        $this->specie = $specie;
    }

    public function setBirthday(String $birthday): void
    {
        $this->birthday = $birthday;
    }

    public function __toString(): String
    {
        return "Animal [id: {$this->id}, name: {$this->name}, description: {$this->description}]";
    }
}
