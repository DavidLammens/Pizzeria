<?php

declare(strict_types=1);

namespace Data;

use PDO;
use Entities\Plaats;
use Exceptions\DBException;
use Data\DBConfig;
use Exception;

class LeverPlaatsDAO{
    public function getByPlaatsId(int $id): ?Plaats
    {
        try {
            $sql = "SELECT plaatsen.id, postcode, naam
                    FROM plaatsen INNER JOIN leverplaatsen
                    ON plaatsen.id = leverplaatsen.plaatsId
                    WHERE plaatsen.id = :id";

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

    public function getAll(): array
    {
        try {
            $sql = "SELECT plaatsen.id, postcode, naam
                    FROM plaatsen INNER JOIN leverplaatsen
                    on plaatsen.id = leverplaatsen.plaatsId";

            $dbh = new PDO(
                DBConfig::$DB_CONNSTRING,
                DBConfig::$DB_USERNAME,
                DBConfig::$DB_PASSWORD
            );
            $resultSet = $dbh->query($sql);

            $leverPlaatsen = array();

            if ($resultSet) {
                foreach ($resultSet as $rij) {

                    $plaats = new Plaats(
                        (int) $rij['id'],
                        $rij['postcode'],
                        $rij['naam']
                    );

                    array_push($leverPlaatsen, $plaats);
                }
            }

            $dbh = null;
            return $leverPlaatsen;
        } catch (Exception $exception) {

            throw new DBException($exception->getMessage());
        }
    }
}