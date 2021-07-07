<?php

declare(strict_types=1);

namespace Entities;

use Entities\PizzaSoort;

class Pizza
{
    private int $id;

    private PizzaSoort $soort;

    private string $formaat;

    private float $prijs;

    private float $promotieprijs;

    public function __construct(
        int $id,
        PizzaSoort $soort,
        string $formaat,
        float $prijs,
        float $promotieprijs
    ) {
        $this->id = $id;
        $this->soort = $soort;
        $this->formaat = $formaat;
        $this->prijs = $prijs;
        $this->promotieprijs = $promotieprijs;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getSoort(): PizzaSoort
    {
        return $this->soort;
    }

    public function getFormaat(): string
    {
        return $this->formaat;
    }

    public function getPrijs(): float
    {
        return $this->prijs;
    }

    public function getPrintPrijs(): string
    {
        return number_format($this->getPrijs(), 2, ",", ".");
    }

    public function getPromotieprijs(): float
    {
        return $this->promotieprijs;
    }

    public function getPrintPromotieprijs(): string
    {
        return number_format($this->getPromotieprijs(), 2, ",", ".");
    }
}
