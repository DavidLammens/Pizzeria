<?php

declare(strict_types=1);

namespace Data;

use PDO;
use Exception;
use Data\DBConfig;
use Entities\Ingredient;
use Exceptions\DBException;

class IngredientDAO
{
    public function getByPizzaSoortId(int $pizzaSoortId): array
    {
        try {
            $sql = "SELECT ingredienten.id, ingredienten.naam, ingredienten.opVoorraad 
                    FROM ingredienten INNER JOIN ingredientenpizzasoorten
                    ON ingredienten.id = ingredientenpizzasoorten.ingredientId
                    WHERE ingredientenpizzasoorten.pizzaSoortId = :pizzaSoortId";

            $dbh = new PDO(
                DBConfig::$DB_CONNSTRING,
                DBConfig::$DB_USERNAME,
                DBConfig::$DB_PASSWORD
            );
            $stmt = $dbh->prepare($sql);
            $stmt->execute(array(':pizzaSoortId' => $pizzaSoortId));
            $resultSet = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $ingredienten = array();

            if ($resultSet) {
                foreach ($resultSet as $rij) {

                    $ingredient = Ingredient::create((int)$rij['id'], $rij['naam'],(bool) $rij['opVoorraad']);

                    array_push($ingredienten, $ingredient);
                }
            }

            $dbh = null;
            return $ingredienten;
        } catch (Exception $exception) {
            
            throw new DBException($exception->getMessage());
        }
    }
}
