<?php
namespace Api\DTO;

use Api\Models\Animal;

class AnimalDTO
{
    public String $name;
    public String $specie;
    public String $birthday;
    public String $description;

    private function __construct(String $name, String $specie, String $birthday, String $description)
    {
        $this->name = $name;
        $this->specie = $specie;
        $this->birthday = $birthday;
        $this->description = $description;
    }

    public static function animalToDTO(Animal $animal): AnimalDTO
    {
        return new AnimalDTO(
            $animal->getName(),
            $animal->getSpecie(),
            $animal->getBirthday(),
            $animal->getDescription()
        );
    }
}
