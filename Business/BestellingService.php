<?php

declare(strict_types=1);

namespace Business;

use Data\BestellingDAO;
use Data\LeverPlaatsDAO;
use Entities\Bestellijn;
use Entities\Bestelling;
use Entities\Klant;
use Exceptions\OngeldigeInputException;
use Exceptions\TeVeelInputException;

class BestellingService
{
    public function bestel(
        Klant $klant,
        string $opmerkingen,
        array $winkelmandLijnen
    ): Bestelling {

        $totaalAantal = 0;
        $bestellijnen = [];
        $bestellingDAO = new BestellingDAO;
        $leverPlaatsDAO = new LeverPlaatsDAO;
        $plaatsId = ($klant->getPlaats()->getId());
        $leverplaats = $leverPlaatsDAO->getByPlaatsId($plaatsId);

        if (is_null($leverplaats)) {
            throw new OngeldigeInputException("", 0, null, "Plaats");
        }
        foreach ($winkelmandLijnen as $winkelmandLijn) {
            $totaalAantal += $winkelmandLijn->getAantal();
            if ($totaalAantal > 25) {
                throw new TeVeelInputException("", 0, null, "aantal", 25);
            }
            if ($klant->getPromo()) {
                $prijs = $winkelmandLijn->getPizza()->getPromotiePrijs();
            } else {
                $prijs = $winkelmandLijn->getPizza()->getPrijs();
            }
            $bestellijn = new Bestellijn(
                null,
                $winkelmandLijn->getPizza(),
                $winkelmandLijn->getAantal(),
                $prijs
            );
            $bestellijnen[] = $bestellijn;
        }
        
        return $bestellingDAO->create($klant->getId(), $opmerkingen, $bestellijnen);
    }

    public function getBestellingenVanKlant(int $klantId): array
    {
        $bestellingDAO = new BestellingDAO;
        
        return $bestellingDAO->getByKlantId_TijdDesc($klantId);
    }
}
