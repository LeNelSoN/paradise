<?php
namespace Api\Models;

use Api\Models\Zone;

/**
 * Object model for table enclos
 */
class Enclos
{
    private int $id;
    private String $name;
    private String $description;
    private Zone $zone;

    public function __construct(int $id, String $name, String $description, Zone $zone)
    {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->zone = $zone;
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

    public function getZone(): Zone
    {
        return $this->zone;
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

    public function setZone(Zone $zone): void
    {
        $this->zone = $zone;
    }

    public function __toString(): String
    {
        return "Enclos [id: {$this->id}, name: {$this->name}, description: {$this->description}, zone:{$this->zone->getName()}]";
    }
}
