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
                self::$pdo = new PDO('mysql:host='.$_ENV['DB_HOST'].';dbname='.$_ENV['DB_NAME'].';', $_ENV['DB_USER'], $_ENV['DB_PASS']);
                self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                throw new PDOException("Error connexion : ". $e->getMessage());
            }
        }

        return self::$pdo;
    }

    abstract protected function getAll(): array;
    abstract protected function getOne(mixed $id);
    abstract protected function create(mixed $data);
    abstract protected function update(mixed $id, mixed $data);
    abstract protected function delete(mixed $id);
}