<?php
namespace Api\Models;

class Classe
{
    private String $name;
    private String $description;

    public function __construct(String $name, String $description)
    {
        $this->name = $name;
        $this->description = $description;
    }

    public function getName(): String
    {
        return $this->name;
    }

    public function getDescription(): String
    {
        return $this->description;
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