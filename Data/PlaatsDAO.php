<?php

declare(strict_types=1);

namespace Data;

use PDO;
use Entities\Plaats;
use Exceptions\DBException;
use Data\DBConfig;
use Exception;

class PlaatsDAO{
    public function getById(int $id): ?Plaats
    {
        try {
            $sql = "SELECT id, postcode, naam
                    FROM plaatsen
                    WHERE id = :id";

            $dbh = new PDO(
                DBConfig::$DB_CONNSTRING,
                DBConfig::$DB_USERNAME,
                DBConfig::$DB_PASSWORD
            );
            $stmt = $dbh->prepare($sql);
            $stmt->execute(array(':id' => $id));
            $rij = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($rij) {
                $plaats = new Plaats(
                    (int) $rij['id'],
                    $rij['postcode'],
                    $rij['naam']
                );
            } else {
                $plaats = null;
            }

            $dbh = null;
            return $plaats;
        } catch (Exception $exception) {

            throw new DBException($exception->getMessage());
        }
    }
}