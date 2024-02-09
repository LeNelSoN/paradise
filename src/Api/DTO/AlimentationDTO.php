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
}