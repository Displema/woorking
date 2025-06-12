<?php

namespace TechnicalServiceLayer\Repository;

use Delight\Db\PdoDatabase;

class UserRepository
{
    private static ?UserRepository $instance = null;
    private ?PDODatabase $pdoDatabase;
    private function __construct()
    {
        $this->pdoDatabase = getAuthDb();
    }

    public static function getInstance(): UserRepository
    {
        if (self::$instance === null) {
            self::$instance = new UserRepository();
        }
        return self::$instance;
    }

    public function getEmailByUserId(string $id): ?string
    {
        return $this->pdoDatabase->select("SELECT email FROM users WHERE id = :id", [":id" => $id])[0]['email'] ?? null;
    }
}
