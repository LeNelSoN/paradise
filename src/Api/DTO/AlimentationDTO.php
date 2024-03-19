<?php
namespace Api\DTO;

use Api\Models\Alimentation;

class AlimentationDTO
{
    public String $name;
    public String $description;

    private function __construct(String $name, String $description)
    {
        $this->name = $name;
        $this->description = $description;
    }

    public static function alimentationToDTO(Alimentation $alimentation): AlimentationDTO
    {
        return new AlimentationDTO(
            $alimentation->getName(),
            $alimentation->getDescription()
        );
    }

    public static function alimentationToDTOarray(array $alimentations): array
    {
        $alimentationsDTO = [];
        foreach ($alimentations as $alimentation) {
            $alimentationsDTO[] = self::alimentationToDTO($alimentation);
        }
        return $alimentationsDTO;
    }

    public static function requestToAlimentation(array $request): Alimentation
    {
        $alimentation = new Alimentation($request['name']);

        if (isset($request['description'])) {
            $alimentation->setDescription($request['description']);
        }

        return $alimentation;
    }
}