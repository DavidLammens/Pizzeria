<?php

declare(strict_types=1);

namespace Business;

use Entities\Winkelmand;
use Business\PizzaService;

class WinkelmandService
{
    public function __construct(bool $promo = false)
    {
        if (isset($_SESSION['winkelmand'])) {
            $this->setPromo($promo);
        } else {
            $_SESSION['winkelmand'] = serialize(new Winkelmand($promo));
        }
    }


    private function getWinkelmand(): Winkelmand
    {
        return unserialize($_SESSION['winkelmand'], ['Winkelmand']);
    }

    public function setWinkelmand(Winkelmand $winkelmand): Winkelmand
    {
        $_SESSION['winkelmand'] = serialize($winkelmand);

        return $winkelmand;
    }


    public function voegToe(int $pizzaId, int $aantal): Winkelmand
    {
        $pizzaService = new PizzaService;
        $pizza = $pizzaService->getPizzaById($pizzaId);

        return $this->setWinkelmand($this->getWinkelmand()->voegToe($pizza, $aantal));
    }

    public function getInhoud(): array
    {
        return $this->getWinkelmand()->getWinkelmandLijnen();
    }

    public function heeftInhoud(): bool
    {
        return (count($this->getInhoud()) > 0 ? true : false);
    }

    public function getPrintTotaal(): string
    {
        return $this->getWinkelmand()->getPrintTotaal();
    }

    public function updateLijn(int $pizzaId, int $aantal): Winkelmand
    {
        return $this->setWinkelmand($this->getWinkelmand()->updateWinkelmandLijn($pizzaId, $aantal));
    }

    public function verwijderLijn(int $pizzaId): Winkelmand
    {
        return $this->setWinkelmand($this->getWinkelmand()->deleteWinkelmandLijn($pizzaId));
    }

    public function ledigWinkelmand(): Winkelmand
    {
        return $this->setWinkelmand($this->getWinkelmand()->ledig());
    }

    public function setPromo(bool $promo): Winkelmand
    {
        return $this->setWinkelmand($this->getWinkelmand()->setPromo($promo));
    }
}
