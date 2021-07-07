<?php

declare(strict_types=1);

use Business\BestellingService;
use Business\HeaderService;
use Exceptions\DBException;

require_once __DIR__ . "/controllerBootstrap/algemeneBootstrap.php";


$bestellingService = new BestellingService;
$headerService = new HeaderService('index.php');

if (!$klantService->klantIsSaved()) {
    $headerService->header();
}

if (isset($_GET['laatsteBestelling'])){
    $titel = "Uw bestelling";
    $toonEerdereBestellingen = false;
}else{
    $titel = "Uw bestelgeschiedenis";
    $toonEerdereBestellingen = true;
}

try {
    $bestellingen = $bestellingService->getBestellingenVanKlant($klantService->getKlant()->getId());
    if (count($bestellingen) === 0) {
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
