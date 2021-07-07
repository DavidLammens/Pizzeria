<?php

declare(strict_types=1);

namespace Business;

use Data\IngredientDAO;
use Data\PizzaDAO;
use DTOs\SoortIngredientenPizzasDTO;
use Entities\Pizza;

class PizzaService
{
    public function getBeschikbarePizzasInSoorten(): array //array van SoortIngredientenPizzasDTO's
    {
        $ingredientDAO = new IngredientDAO;
        $pizzaDAO = new PizzaDAO;
        $allePizzas = $pizzaDAO->getAll();
        $beschikbarePizzasInSoorten = array();

        foreach ($allePizzas as $pizza) {

            if (!isset($beschikbarePizzasInSoorten[$pizza->getSoort()->getId()])) {

                $ingredienten = $ingredientDAO->getByPizzaSoortId($pizza->getSoort()->getId());

                foreach ($ingredienten as $ingredient) {

                    if (!$ingredient->getOpVoorraad()) {
                        continue 2;
                    }
                }
                $beschikbarePizzasInSoorten[$pizza->getSoort()->getId()] = new SoortIngredientenPizzasDTO(
                    $pizza->getSoort(),
                    $ingredienten
                );
            }
            $beschikbarePizzasInSoorten[$pizza->getSoort()->getId()]->addPizza($pizza);
        }
        return array_filter($beschikbarePizzasInSoorten);
    }

    public function getPizzaById(int $id): Pizza
    {
        $pizzaDAO = new PizzaDAO;

        return $pizzaDAO->getById($id);
    }
}
