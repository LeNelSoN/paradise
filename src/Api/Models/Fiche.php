<?php
namespace Api\Models;

use Api\Models\Classe;
use Api\Models\Alimentation;
use Api\Models\ZoneGeographique;

class Fiche
{
    private String $specieName;
    private String $collumnSpecie = "specie_name";
    private ?String $classeId = null;
    private ?Classe $classe = null;
    private String $collumnClasse = "classe";
    private ?String $alimentationId = null;
    private ?Alimentation $alimentation = null;
    private String $collumnAlimentation = "alimentation";
    private ?String $poidsMoyen = null;
    private String $collumnPoidsMoyen = "poidsMoyen";
    private ?String $longevite = null;
    private String $collumnLongevite = "longevite";
    private ?String $longueur = null;
    private String $collumnLongueur = "longueur";
    private ?String $taille = null;
    private String $collumnTaille = "taille";
    private ?String $zoneGeographiqueId = null;
    private ?ZoneGeographique $zoneGeographique = null;
    private String $collumnZoneGeographique = "zone_geographique";
    private ?String $description = null;
    private String $collumnDescription = "description";

    public function __construct(String $specieName)
    {
        $this->specieName = $specieName;
    }

    public function getSpecieName(): String
    {
        return $this->specieName;
    }

    public function getClasseId(): ?String
    {
        return $this->classeId;
    }

    public function getClasse(): ?Classe
    {
        return $this->classe;
    }

    public function getAlimentationId(): ?String
    {
        return $this->alimentationId;
    }

    public function getAlimentation(): ?Alimentation
    {
        return $this->alimentation;
    }

    public function getPoidsMoyen(): ?String
    {
        return $this->poidsMoyen;
    }

    public function getLongevite(): ?String
    {
        return $this->longevite;
    }

    public function getLongueur(): ?String
    {
        return $this->longueur;
    }

    public function getTaille(): ?String
    {
        return $this->taille;
    }

    public function getZoneGeographiqueId(): ?String
    {
        return $this->zoneGeographiqueId;
    }

    public function getZoneGeographique(): ?ZoneGeographique
    {
        return $this->zoneGeographique;
    }

    public function getDescription(): ?String
    {
        return $this->description;
    }

    public function getCollumns(): array
    {
        return [
            $this->collumnSpecie,
            $this->collumnClasse,
            $this->collumnAlimentation,
            $this->collumnPoidsMoyen,
            $this->collumnLongevite,
            $this->collumnLongueur,
            $this->collumnTaille,
            $this->collumnZoneGeographique,
            $this->collumnDescription
        ];
    }

    public function setSpecieName(String $specieName): void
    {
        $this->specieName = $specieName;
    }

    public function setClasseId(String $classeId): void
    {
        $this->classeId = $classeId;
    }

    public function setClasse(Classe $classe): void
    {
        $this->classe = $classe;
    }

    public function setAlimentationId(String $alimentationId): void
    {
        $this->alimentationId = $alimentationId;
    }

    public function setAlimentation(Alimentation $alimentation): void
    {
        $this->alimentation = $alimentation;
    }

    public function setPoidsMoyen(String $poidsMoyen): void
    {
        $this->poidsMoyen = $poidsMoyen;
    }

    public function setLongevite(String $longevite): void
    {
        $this->longevite = $longevite;
    }

    public function setLongueur(String $longueur): void
    {
        $this->longueur = $longueur;
    }

    public function setTaille(String $taille): void
    {
        $this->taille = $taille;
    }

    public function setZoneGeographiqueId(String $zoneGeographiqueId): void
    {
        $this->zoneGeographiqueId = $zoneGeographiqueId;
    }

    public function setZoneGeographique(ZoneGeographique $zoneGeographique): void
    {
        $this->zoneGeographique = $zoneGeographique;
    }

    public function setDescription(String $description): void
    {
        $this->description = $description;
    }

    public function __toString(): String
    {
        return "Fiche [specieName: {$this->specieName}, classe: {$this->classe}, alimentation: {$this->alimentation}, poidMoyen: {$this->poidMoyen}, longevite: {$this->longevite}, longueur: {$this->longueur}, taille: {$this->taille}, zoneGeographique: {$this->zoneGeographique}, description: {$this->description}]";
    }

    /**
     * Convert an array of fiche to an array of fiche
     * 
     * @param array $data The array of fiche to convert.
     * 
     * @return Fiche The fiche created from the request data.
     * 
     * @author Nelis Valentin
     */
    public static function requestDataToFiche(array $data): Fiche
    {
        $fiche = new Fiche($data['specieName']);

        if (isset($data['classe'])) {
            $fiche->setClasseId($data['classe']);
        }
        if (isset($data['alimentation'])) {
            $fiche->setAlimentationId($data['alimentation']);
        }
        if (isset($data['poidsMoyen'])) {
            $fiche->setPoidsMoyen($data['poidsMoyen']);
        }
        if (isset($data['longevite'])) {
            $fiche->setLongevite($data['longevite']);
        }
        if (isset($data['longueur'])) {
            $fiche->setLongueur($data['longueur']);
        }
        if (isset($data['taille'])) {
            $fiche->setTaille($data['taille']);
        }
        if (isset($data['zoneGeographique'])) {
            $fiche->setZoneGeographiqueId($data['zone_geographique']);
        }
        if (isset($data['description'])) {
            $fiche->setDescription($data['description']);
        }
        return $fiche;
    }

}