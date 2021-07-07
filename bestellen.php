<?php

declare(strict_types=1);

use Business\BestellingService;
use Business\HeaderService;
use Exceptions\DBException;
use Exceptions\OngeldigeInputException;
use Exceptions\TeVeelInputException;

require_once __DIR__ . "/controllerBootstrap/algemeneBootstrap.php";


$bestellingService = new BestellingService;
$headerService = new HeaderService("bestellen.php", true);

if (!$klantService->klantIsSaved()) {
    $headerService->header('registratie.php');
}

if (isset($_POST['action'])) {

    if ($_POST['action'] === "updateLijn") {
        $pizzaId = $_POST['pizzaId'];
        $winkelmandService->updateLijn((int) $pizzaId, (int) $_POST['aantal' . $pizzaId]);
    }

    if ($_POST['action'] === "verwijderLijn") {
        $winkelmandService->verwijderLijn((int) $_POST['pizzaId']);
    }

    if ($_POST['action'] === "Bestel") {
        try {
            $bestellingService->bestel(
                $klantService->getKlant(),
                $_POST['opmerkingen'] ?? "",
                $winkelmandService->getInhoud()
            );
            $klantService->saveOpmerkingen($_POST['opmerkingen'] ?? "");
            $winkelmandService->ledigWinkelmand();
            $meldingService->setMelding(
                "Uw bestelling is goed ontvangen."
            );
            $headerService->header('bestellingOverzicht.php');
        } catch (DBException $e) {
            $meldingService->setMelding(
                "Oeps, er ging iets mis, probeer het later opnieuw."
            );
        } catch (OngeldigeInputException $e) {
            if ($e->getVeld() === "plaats") {
                $meldingService->setMelding(
                    "Wij leveren niet meer in " . $klantService->getKlantPlaatsNaam()
                );
            }
        } catch (TeVeelInputException $e) {
            if ($e->getVeld() === "aantal") {
                $meldingService->setMelding(
                    "Maximum " . $e->getMaxInput() . " pizzas per bestelling"
                );
            }
        }
    }

    if ($_POST['action'] === "Pizzamenu") {
        $headerService->header('index.php');
    }
}

if (!$winkelmandService->heeftInhoud()) {
    $headerService->header('index.php');
}

include_once __DIR__ . "/Presentation/bestelPagina.php";
