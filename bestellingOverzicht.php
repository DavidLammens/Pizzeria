<?php

declare(strict_types=1);

use Business\BestellingService;
use Exceptions\DBException;

require_once __DIR__ . "/controllerBootstrap/algemeneBootstrap.php";


$bestellingService = new BestellingService;

if (!$klantService->klantIsSaved()){
    header("Location: index.php");
    exit(0);
}

try {
    $bestellingen = $bestellingService->getBestellingenVanKlant($klantService->getKlant()->getId());
    if (count($bestellingen)===0){
        $meldingService->setMelding(
            "U hebt nog geen bestellingen gedaan"
        );
    }
} catch (DBException $e) {
    $meldingService->setMelding(
        "Oeps, er ging iets mis, probeer het later opnieuw"
    );
}

include_once __DIR__ . "/Presentation/bestellingOverzichtPagina.php";