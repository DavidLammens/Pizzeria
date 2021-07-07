<?php

declare(strict_types=1);

namespace Entities;


//SINGLETON
class Ingredient
{
    private static $idMap = array();


    private int $id;

    private string $naam;

    private bool $opVoorraad;

    public function __construct(int $id, string $naam, bool $opVoorraad = true)
    {
        $this->id = $id;
        $this->naam = $naam;
        $this->opVoorraad = $opVoorraad;
    }

    //Deze STATIC functie vervangt "new Ingredient"
    public static function create(int $id, string $naam, bool $opVoorraad = true) {
        if (!isset(self::$idMap[$id])) {
            self::$idMap[$id] = new Ingredient($id, $naam, $opVoorraad);
        }
        return self::$idMap[$id];
    }


    public function getId(): int
    {
        return $this->id;
    }

    public function getNaam(): string
    {
        return $this->naam;
    }

    public function getOpVoorraad(): bool
    {
        return $this->opVoorraad;
    }
}
