<?php

declare(strict_types=1);

namespace Data;

use PDO;
use Exception;
use Data\DBConfig;
use Data\PizzaDAO;
use Entities\Bestellijn;
use Exceptions\DBException;

class BestellijnDAO
{
    public function getByBestellingId(int $bestellingId): array
    {
        $pizzaDAO = new PizzaDAO;
        try {
            $sql = "SELECT id, pizzaId, aantal, bestelPrijsPerStuk
                    FROM bestellijnen 
                    WHERE bestellingId = :bestellingId";

            $dbh = new PDO(
                DBConfig::$DB_CONNSTRING,
                DBConfig::$DB_USERNAME,
                DBConfig::$DB_PASSWORD
            );
            $stmt = $dbh->prepare($sql);
            $stmt->execute(array(':bestellingId' => $bestellingId));
            $resultSet = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $bestellijnen = [];
            
            if ($resultSet) {
                foreach ($resultSet as $rij) {
                    $pizza = $pizzaDAO->getById((int)$rij['pizzaId']);
                    $bestellijn = new Bestellijn(
                        (int) $rij['id'],
                        $pizza,
                        (int) $rij['aantal'],
                        (float) $rij['bestelPrijsPerStuk']
                    );
                    $bestellijnen[] = $bestellijn;
                }
            }

            $dbh = null;
            return $bestellijnen;
        } catch (Exception $exception) {

            throw new DBException($exception->getMessage());
        }
    }
}
