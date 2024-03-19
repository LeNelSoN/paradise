<?php
namespace Api\DTO;

use Api\Models\ZoneGeographique;

/**
 * Data Transfer Object for table zone_geographique
 * 
 * This class is used to transfer data from the database to the API.
 * 
 * @package Api\DTO
 * @category Data Transfer Object
 * @author Valentin Nelis
 */
class ZoneGeographiqueDTO
{
    public String $name;
    public String $description;

    private function __construct(String $name, String $description)
    {
        $this->name = $name;
        $this->description = $description;
    }

    /**
     * Convert a ZoneGeographique object to a ZoneGeographiqueDTO object
     * 
     * @param ZoneGeographique $zoneGeographique The ZoneGeographique object to convert.
     * @return ZoneGeographiqueDTO The ZoneGeographiqueDTO object.
     */
    public static function zoneGeographiqueToDTO(ZoneGeographique $zoneGeographique): ZoneGeographiqueDTO
    {
        return new ZoneGeographiqueDTO(
            $zoneGeographique->getName(),
            $zoneGeographique->getDescription()
        );
    }

    public static function zoneGeoToDTOArray(array $zoneGeographiques): array
    {
        $zoneGeographiquesDTO = [];
        foreach ($zoneGeographiques as $zoneGeographique) {
            $zoneGeographiquesDTO[] = self::zoneGeographiqueToDTO($zoneGeographique);
        }
        return $zoneGeographiquesDTO;
    }

    public static function requestToZoneGeo(array $request, String $id = ""): ZoneGeographique
    {
        if ($id != "") {
            $zoneGeographique = new ZoneGeographique($id);
        } else {
            $zoneGeographique = new ZoneGeographique($request['name']);
        }

        if (isset($request['description'])) {
            $zoneGeographique->setDescription($request['description']);
        }

        return $zoneGeographique;
    }
}