<?php
namespace Api\DTO;

use Api\Models\Fiche;

use Api\DTO\ClasseDTO;
use Api\DTO\AlimentationDTO;
use Api\DTO\ZoneGeographiqueDTO;

class FicheDTO
{
    public String $specieName;
    public ?ClasseDTO $classe;
    public ?AlimentationDTO $alimentation;
    public ?String $poidsMoyen;
    public ?String $longevite;
    public ?String $longueur;
    public ?String $taille;
    public ?ZoneGeographiqueDTO $zoneGeographique;
    public ?String $description;
    
    private function __construct(String $specieName)
    {
        $this->specieName = $specieName;
    }

    private function setClasseDTO(ClasseDTO $classe): void
    {
        $this->classe = $classe;
    }

    private function setAlimentationDTO(AlimentationDTO $alimentation): void
    {
        $this->alimentation = $alimentation;
    }

    private function setPoidsMoyen(String $poidsMoyen): void
    {
        $this->poidsMoyen = $poidsMoyen;
    }

    private function setLongevite(String $longevite): void
    {
        $this->longevite = $longevite;
    }

    private function setLongueur(String $longueur): void
    {
        $this->longueur = $longueur;
    }

    private function setTaille(String $taille): void
    {
        $this->taille = $taille;
    }

    private function setZoneGeographiqueDTO(ZoneGeographiqueDTO $zoneGeographique): void
    {
        $this->zoneGeographique = $zoneGeographique;
    }

    public static function ficheToDTO(Fiche $fiche): FicheDTO
    {
        $ficheDTO = new FicheDTO($fiche->getSpecieName());

        if ($fiche->getClasse() !== null) {
            $ficheDTO->setClasseDTO(ClasseDTO::classeToDTO($fiche->getClasse()));
        }

        if ($fiche->getAlimentation() !== null) {
            $ficheDTO->setAlimentationDTO(AlimentationDTO::alimentationToDTO($fiche->getAlimentation()));
        }

        if ($fiche->getPoidsMoyen() !== null) {
            $ficheDTO->setPoidsMoyen($fiche->getPoidsMoyen());
        }

        if ($fiche->getLongevite() !== null) {
            $ficheDTO->setLongevite($fiche->getLongevite());
        }

        if ($fiche->getLongueur() !== null) {
            $ficheDTO->setLongueur($fiche->getLongueur());
        }

        if ($fiche->getTaille() !== null) {
            $ficheDTO->setTaille($fiche->getTaille());
        }

        if ($fiche->getZoneGeographique() !== null) {
            $ficheDTO->setZoneGeographiqueDTO(ZoneGeographiqueDTO::zoneGeographiqueToDTO($fiche->getZoneGeographique()));
        }

        if ($fiche->getDescription() !== null) {
            $ficheDTO->description = $fiche->getDescription();
        }

        return $ficheDTO;
    }

    public static function ficheToDTOArray(array $fiches): array
    {
        $fichesDTO = [];

        foreach ($fiches as $fiche) {
            $fichesDTO[] = FicheDTO::ficheToDTO($fiche);
        }

        return $fichesDTO;
    }

}
