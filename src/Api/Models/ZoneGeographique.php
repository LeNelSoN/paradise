<?php 
namespace Api\Models;

class ZoneGeographique
{
    private String $name;
    private String $collumnName = "zone_geographique_name";
    private ?String $description = null;
    private String $collumnDescription = "description";

    public function __construct(String $name)
    {
        $this->name = $name;
    }

    public function getName(): String
    {
        return $this->name;
    }

    public function getDescription(): ?String
    {
        return $this->description;
    }

    public function getCollumnName(): String
    {
        return $this->collumnName;
    }

    public function getCollumnDescription(): String
    {
        return $this->collumnDescription;
    }

    public function setName(String $name): void
    {
        $this->name = $name;
    }

    public function setDescription(String $description): void
    {
        $this->description = $description;
    }
}