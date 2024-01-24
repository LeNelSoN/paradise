<?php
namespace Api\Models;

/**
 * Object model for table zone
 */
class Zone
{
    private int $id;
    private String $name;
    private String $description;

    public function __construct(int $id, String $name, String $description)
    {
        $this->id = $id;
        $this->name = $name;
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

    public function __toString(): String
    {
        return "Zone [id: {$this->id}, name: {$this->name}, description: {$this->description}]";
    }
}
