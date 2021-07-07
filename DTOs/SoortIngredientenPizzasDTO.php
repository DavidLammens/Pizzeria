<?php

declare(strict_types=1);

namespace DTOs;

use Entities\Pizza;
use Entities\PizzaSoort;

//Ik heb (tegen mijn gevoel en het ORM-plaatje in) ingredienten uit soorten laten vallen,
//aangezien er geen ingrediënten moeten kunnen worden gewijzigd, 
//(anders had ik ze ook in een bestelling moeten opnemen)
//en de ingredienten dus uiteindelijk alleen informatief in het pizzamenu getoond worden.

//de ingredienten van een pizzasoort blijven natuurlijk perfect bereikbaar via de ingredientDAO,
//die ze per pizzasoort kan opvragen.

//Ik neem ingredienten in dit DTO, dat de pizzas van een pizzasoort groepeert voor de pizzamenu weergave,
//dus wel weer op om ze in het pizzamenu te kunnen weergeven.

class SoortIngredientenPizzasDTO
{
    private PizzaSoort $soort;

    private array $ingredienten;

    private array $pizzas;

    public function __construct(PizzaSoort $soort, array $ingredienten, array $pizzas = [])
    {
        $this->soort = $soort;
        $this->ingredienten = $ingredienten;
        $this->pizzas = $pizzas;
    }

    public function getSoort(): PizzaSoort
    {
        return $this->soort;
    }

    public function getIngredienten(): array
    {
        return $this->ingredienten;
    }

    public function getPizzas(): array
    {
        return $this->pizzas;
    }

    public function addPizza(Pizza $pizza)
    {
        $this->pizzas[] = $pizza;
    }

    public function getPrintIngrediëntenLijst(): string
    {
        $ingredientenLijst = "";
        foreach ($this->ingredienten as $ingredient) {
            $ingredientenLijst .= $ingredient->getNaam() . ", ";
        }
        if (strlen($ingredientenLijst)>0){
            $ingredientenLijst = substr($ingredientenLijst, 0, -2);
        }

        return $ingredientenLijst;
    }
}
