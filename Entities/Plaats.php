<?php

declare(strict_types=1);

namespace Entities;

class Plaats{
    private int $id;

    private string $postcode;

    private string $naam;

    public function __construct(int $id, string $postcode, string $naam)
    {
        $this->id = $id;
        $this->postcode = $postcode;
        $this->naam = $naam;
    }

    public function getId(): int 
    {
        return $this->id;
    }

    public function getPostcode(): string 
    {
        return $this->postcode;
    }

    public function getNaam(): string 
    {
        return $this->naam;
    }
}