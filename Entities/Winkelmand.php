<?php

declare(strict_types=1);

namespace Entities;

use Entities\Pizza;
use Entities\WinkelmandLijn;
use Exceptions\TeVeelInputException;

class Winkelmand
{
    private array $winkelmandLijnen;

    private bool $promo;


    public function __construct(bool $promo, array $winkelmandLijnen = [])
    {
        $this->winkelmandLijnen = $winkelmandLijnen;

        $this->setPromo($promo);
    }


    public function getWinkelmandLijnen(): array
    {
        return $this->winkelmandLijnen;
    }

    public function getAantalWinkelmandLijnen(): int
    {
        $aantal = count($this->winkelmandLijnen);

        return $aantal;
    }

    public function getPrintTotaal(): string
    {
        $totaal = 0;

        foreach ($this->winkelmandLijnen as $winkelmandLijn) {
            $totaal += $winkelmandLijn->getTotaal();
        }

        $totaal = number_format($totaal, 2, ",", ".");

        return $totaal;
    }

    public function setPromo(bool $promo): Winkelmand
    {
        $this->promo = $promo;

        foreach ($this->winkelmandLijnen as $winkelmandLijn) {
            $winkelmandLijn->setPromo($promo);
        }

        return $this;
    }

    public function ledig(): Winkelmand
    {
        $this->winkelmandLijnen = [];

        return $this;
    }

    public function voegToe(Pizza $pizza, int $aantal): Winkelmand
    {
        $toegevoegd = false;
        $totaalAantal = $aantal;
        foreach ($this->winkelmandLijnen as $aanwezigeWinkelmandLijn) {
            $totaalAantal += $aanwezigeWinkelmandLijn->getAantal();
            if ($totaalAantal > 25) {
                throw new TeVeelInputException("", 0, null, "aantal totaal", 25);
            }
            if ($aanwezigeWinkelmandLijn->getPizza() == $pizza) {
                $nieuwAantal = $aanwezigeWinkelmandLijn->getAantal() + $aantal;
                $aanwezigeWinkelmandLijn->setAantal($nieuwAantal);
                $toegevoegd = true;
            }
        }
        if (!$toegevoegd) {
            $winkelmandLijn = new WinkelmandLijn($pizza, $aantal, $this->promo);
            $this->winkelmandLijnen[] = $winkelmandLijn;
        }

        return $this;
    }

    public function updateWinkelmandLijn(int $pizzaId, int $aantal): Winkelmand
    {
        $totaalAantal = $aantal;
        foreach ($this->winkelmandLijnen as $aanwezigeWinkelmandLijn) {
            $totaalAantal += $aanwezigeWinkelmandLijn->getAantal();
            if ($totaalAantal > 25) {
                throw new TeVeelInputException("", 0, null, "aantal totaal", 25);
            }
        }
        foreach ($this->winkelmandLijnen as $aanwezigeWinkelmandLijn) {
            if ($aanwezigeWinkelmandLijn->getPizza()->getId() === $pizzaId) {
                $aanwezigeWinkelmandLijn->setAantal($aantal);
                break;
            }
        }


        return $this;
    }

    public function deleteWinkelmandLijn(int $pizzaId): Winkelmand
    {
        $index = 0;
        foreach ($this->winkelmandLijnen as $aanwezigeWinkelmandLijn) {
            if ($aanwezigeWinkelmandLijn->getPizza()->getId() === $pizzaId) {
                break;
            }
            $index++;
        }
        $winkelmandLijnen = $this->winkelmandLijnen;
        array_splice($winkelmandLijnen, $index, 1);
        $this->winkelmandLijnen = $winkelmandLijnen;

        return $this;
    }
}
