<?php
namespace Api\DTO;

use Api\Models\Animal;
use Api\Models\Fiche;

class AnimalDTO
{
    public ?String $id;
    public String $name;
    public String $birthday;
    public ?String $description;
    public ?FicheDTO $fiche;
    
    private function __construct(String $id, String $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    /**
     * Convert an animal to an animal DTO
     * 
     * @param Animal $animal The animal to convert.
     * @return AnimalDTO The animal DTO.
     * 
     * @author Nelis Valentin
     */
    public static function animalToDTO(Animal $animal): AnimalDTO
    {
        $animalDTO = new AnimalDTO(
            $animal->getId(),
            $animal->getName()
        );

        if ($animal->getDescription() !== null) {
            $animalDTO->setDescription($animal->getDescription());
        }

        if ($animal->getSpecie() !== null) {
            $animalDTO->setFiche(FicheDTO::ficheToDTO($animal->getSpecie()));
        }

        if ($animal->getBirthday() !== null) {
            $animalDTO->setBirthday($animal->getBirthday());
        }
        return $animalDTO;
    }

    /**
     * Convert an array of animals to an array of animals DTO
     * 
     * @param array $animals The array of animals to convert.
     * @return array The array of animals DTO.
     * 
     * @author Nelis Valentin
     */
    public static function animalToDTOArray(array $animals): array
    {
        $animalsDTO = [];
        foreach ($animals as $animal) {
            $animalsDTO[] = self::animalToDTO($animal);
        }
        return $animalsDTO;
    }

    /**
     * Convert request data to Animal
     * 
     * @param array $data The data from the request.
     * @param int $id The id of the animal.
     * 
     * @return Animal The animal created from the request data.
     * 
     * @author Nelis Valentin
     */
    public static function requestDataToAnimal(array $data, ?int $id = 0): Animal
    {
        $animal = new Animal($id, $data['name']);

        if (isset($data['description'])) {
            $animal->setDescription($data['description']);
        }
        if (isset($data['specie'])) {
            $animal->setSpecieId($data['specie']);
        }
        if (isset($data['birthday'])) {
            $animal->setBirthday($data['birthday']);
        }
        if (isset($data['enclos'])) {
            $animal->setEnclosId($data['enclos']);
        }
        return $animal;
    }

    private function setDescription(String $description)
    {
        $this->description = $description;
    }

    private function setFiche(FicheDTO $fiche)
    {
        $this->fiche = $fiche;
    }

    private function setBirthday(String $birthday)
    {
        $this->birthday = $birthday;
    }
}
