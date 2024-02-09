<?php
namespace Api\Utils;

use DateTime;

class ServiceHelper
{
    public static function generateSQLPrepareValues(array $values): String
    {
        $sql = 'VALUES ( ';
        foreach ($values as $value) {
            $sql .= '?, ';
        }
        $sql = rtrim($sql, ', ');
        $sql .= ' )';

        return $sql;
    }

    public static function stringToDateTime(?String $date): ?DateTime
    {
        if ($date === null) {
            return null;
        }
        return (new DateTime(str_replace('/', '-', $data['birthday'])))->format('Y-m-d');
    }
}
