<?php

declare(strict_types=1);

namespace Entities;

use Entities\Pizza;

class Bestellijn
{
    private ?int $id;

    private Pizza $pizza;

    private int $aantal;

    private float $prijsPerStuk;

    public function __construct(?int $id, Pizza $pizza, int $aantal, float $prijsPerStuk)
    {
        $this->id = $id;
        $this->pizza = $pizza;
        $this->aantal = $aantal;
        $this->prijsPerStuk = $prijsPerStuk;
    }

    public function getId(): ?int 
    {
        return $this->id;
    }

    public function getPizza(): Pizza 
    {
        return $this->pizza;
    }

    public function getAantal(): int 
    {
        return $this->aantal;
    }

    public function getPrijsPerStuk(): float 
    {
        return $this->prijsPerStuk;
    }

    public function getPrintPrijsPerStuk(): string 
    {
        return number_format($this->getPrijsPerStuk(), 2, ",", ".");
    }

    public function getTotaal(): float
    {
        return $this->getPrijsPerStuk() * $this->getAantal();
    }

    public function getPrintTotaal(): string
    {
        return number_format($this->getTotaal(), 2, ",", ".");
    }

    public function setPizza(Pizza $pizza): Bestellijn
    {
        $this->pizza = $pizza;

        return $this;
    }

    public function setAantal(int $aantal): Bestellijn
    {
        $this->aantal = $aantal;

        return $this;
    }

    public function setPrijsPerStuk(float $prijsPerStuk): Bestellijn
    {
        $this->prijsPerStuk = $prijsPerStuk;

        return $this;
    }
}
