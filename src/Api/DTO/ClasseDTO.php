<?php
namespace Api\DTO;

use Api\Models\Classe;

class ClasseDTO
{
    public String $name;
    public String $description;

    private function __construct(String $name, String $description)
    {
        $this->name = $name;
        $this->description = $description;
    }

    public static function classeToDTO(Classe $classe): ClasseDTO
    {
        return new ClasseDTO(
            $classe->getName(),
            $classe->getDescription()
        );
    }
}