<?php
namespace Api\Services;

use PDO;
use PDOException;

/**
 * Data access object for action on database
 */
abstract class DAO {

    private static $pdo;
    
/**
 * Get an instance of the PDO object to interact with the database.
 *
 * @return PDO The instance of the PDO object.
 * @throws PDOException If the database connection fails.
 */
    protected static function getPDO() {
        if (self::$pdo === null) {
            try {
                self::$pdo = new PDO('mysql:host=paradise-db;port=3306;dbname=paradise_db;', 'root', 'password');
                self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                throw new PDOException("Error connexion : ". $e->getMessage());
            }
        }

        return self::$pdo;
    }
}