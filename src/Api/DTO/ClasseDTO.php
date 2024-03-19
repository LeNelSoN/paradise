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

    public static function classeToDTOarray(array $classes): array
    {
        $classesDTO = [];
        foreach ($classes as $classe) {
            $classesDTO[] = self::classeToDTO($classe);
        }
        return $classesDTO;
    }

    public static function requestToClasse(array $request): Classe
    {
        $classe = new Classe($request['name']);

        if (isset($request['description'])) {
            $classe->setDescription($request['description']);
        }

        return $classe;
    }
}