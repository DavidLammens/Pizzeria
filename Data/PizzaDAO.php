<?php

declare(strict_types=1);

namespace Data;

use PDO;
use Exception;
use Data\DBConfig;
use Exceptions\DBException;
use Data\IngredientDAO;
use Entities\Pizza;
use Entities\PizzaSoort;

class PizzaDAO
{
    public function getAll(): array
    {
        try {
            $sql = "SELECT pizzas.id as pizzaId, pizzas.prijs, pizzas.promotieprijs,
                    pizzasoorten.id as pizzaSoortId, pizzasoorten.naam as pizzaSoortNaam,
                    pizzaformaten.naam as pizzaFormaatNaam
                    FROM pizzas INNER JOIN pizzasoorten
                    ON pizzas.pizzaSoortId = pizzasoorten.id
                    INNER JOIN pizzaformaten
                    ON pizzas.pizzaFormaatId = pizzaformaten.id";

            $dbh = new PDO(
                DBConfig::$DB_CONNSTRING,
                DBConfig::$DB_USERNAME,
                DBConfig::$DB_PASSWORD
            );
            $resultSet = $dbh->query($sql);

            $pizzas = array();

            if ($resultSet) {
                foreach ($resultSet as $rij) {

                    $soort = new PizzaSoort(
                        (int) $rij['pizzaSoortId'],
                        $rij['pizzaSoortNaam']
                    );
                    $pizza = new Pizza(
                        (int)$rij['pizzaId'],
                        $soort,
                        $rij['pizzaFormaatNaam'],
                        (float) $rij['prijs'],
                        (float) $rij['promotieprijs']
                    );

                    array_push($pizzas, $pizza);
                }
            }

            $dbh = null;
            return $pizzas;
        } catch (Exception $exception) {

            throw new DBException($exception->getMessage());
        }
    }

    public function getById(int $id): ?Pizza
    {
        try {
            $sql = "SELECT pizzas.id as pizzaId, pizzas.prijs, pizzas.promotieprijs,
                    pizzasoorten.id as pizzaSoortId, pizzasoorten.naam as pizzaSoortNaam,
                    pizzaformaten.naam as pizzaFormaatNaam
                    FROM pizzas INNER JOIN pizzasoorten
                    ON pizzas.pizzaSoortId = pizzasoorten.id
                    INNER JOIN pizzaformaten
                    ON pizzas.pizzaFormaatId = pizzaformaten.id
                    WHERE pizzas.id = :id";

            $dbh = new PDO(
                DBConfig::$DB_CONNSTRING,
                DBConfig::$DB_USERNAME,
                DBConfig::$DB_PASSWORD
            );
            $stmt = $dbh->prepare($sql);
            $stmt->execute(array(':id' => $id));
            $rij = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($rij) {
                $soort = new PizzaSoort(
                    (int) $rij['pizzaSoortId'],
                    $rij['pizzaSoortNaam']
                );
                $pizza = new Pizza(
                    (int)$rij['pizzaId'],
                    $soort,
                    $rij['pizzaFormaatNaam'],
                    (float) $rij['prijs'],
                    (float) $rij['promotieprijs']
                );
            } else {
                $pizza = null;
            }

            $dbh = null;
            return $pizza;
        } catch (Exception $exception) {

            throw new DBException($exception->getMessage());
        }
    }
}
