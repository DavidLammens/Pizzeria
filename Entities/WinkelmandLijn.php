<?php

declare(strict_types=1);

namespace Entities;

use Entities\Pizza;

class WinkelmandLijn
{
    private Pizza $pizza;

    private int $aantal;
    
    private bool $promo;

    public function __construct(Pizza $pizza, int $aantal, bool $promo)
    {
        $this->pizza = $pizza;
        $this->aantal = $aantal;
        $this->promo = $promo;
    }

    public function getPizza(): Pizza
    {
        return $this->pizza;
    }

    public function getPizzaNaam(): string
    {
        return $this->pizza->getSoort()->getNaam() . " " . $this->pizza->getFormaat();
    }

    public function getAantal(): int
    {
        return $this->aantal;
    }

    public function getTotaal(): float
    {
        $pizzaPrijs = ($this->promo ? $this->pizza->getPromotieprijs() : $this->pizza->getPrijs());
        $totaal = $this->aantal * $pizzaPrijs;

        return $totaal;
    }

    public function getPrintTotaal(): string
    {
        $totaal = number_format($this->getTotaal(), 2, ",", ".");

        return $totaal;
    }

    public function setPizza(Pizza $pizza): WinkelmandLijn
    {
        $this->pizza = $pizza;

        return $this;
    }

    public function setAantal(int $aantal): WinkelmandLijn
    {
        $this->aantal = $aantal;

        return $this;
    }

    public function setPromo(bool $promo): WinkelmandLijn
    {
        $this->promo = $promo;
        
        return $this;
    }
}
