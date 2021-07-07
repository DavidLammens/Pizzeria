<?php

declare(strict_types=1);

use Business\HeaderService;
use Business\PlaatsService;
use Exceptions\DBException;
use Exceptions\LegeInputException;
use Exceptions\OngeldigeInputException;
use Exceptions\TeVeelInputException;

require_once __DIR__ . "/controllerBootstrap/algemeneBootstrap.php";

$plaatsService = new PlaatsService;
$headerService = new HeaderService("index.php");

if (!$klantService->klantIsSaved()) {
    $headerService->header("index.php");
}

$btnHide = $klantService->ingelogd() ? null : "hidden";


try {
    $leverPlaatsen = $plaatsService->getLeverPlaatsen();
} catch (DBException $e) {
    $meldingService->setMelding("Oeps, er ging iets mis, probeer het later opnieuw");
}


if (isset($_POST['action'])) {
    if ($_POST['action'] === "Wijzigen") {
        try {
            $klantService->gegevensWijzigen(
                $_POST['familienaam'] ?? "",
                $_POST['voornaam'] ?? "",
                $_POST['straat'] ?? "",
                $_POST['huisnummer'] ?? "",
                $_POST['bus'] ?? "",
                (int) $_POST['plaatsId'] ?? 0,
                $_POST['telefoonnummer'] ?? "",
                "",
                $klantService->getKlantPromo()
            );
            $meldingService->setMelding(
                "Uw gegevens zijn succesvol gewijzigd."
            );

            $headerService->header();
        } catch (DBException $e) {
            $meldingService->setMelding(
                "Oeps er ging iets mis, probeer het later opnieuw."
            );
        } catch (OngeldigeInputException $e) {
            $melding = "Dit is geen geldige " . $e->getVeld() . ".";
            if ($e->getVeld() === "plaats") {
                $melding .= "<br>Wij leveren enkel in plaatsen die voorkomen in de lijst met voorgestelde plaatsen.";
            }
            if ($e->getVeld() === "telefoonnummer") {
                $melding .= "<br>Minstens 7 cijfers en enkel cijfers en spaties aub.";
            }
            $meldingService->setMelding($melding);
        } catch (TeVeelInputException $e) {
            $meldingService->setMelding(
                "De maximum lengte voor '" . $e->getVeld() . "' is " . $e->getMaxInput() . " tekens"
            );
        } catch (LegeInputException $e) {
            $meldingService->setMelding(
                "Het veld '" . $e->getVeld() . "' is verplicht"
            );
        }
    }
    if ($_POST['action'] === "Annuleren") {
        $headerService->header();
    }

    if ($_POST['action'] === "Mijn account") {
        $headerService->header("accountGegevensWijzigen.php");
    }

    if ($_POST['action'] === "Mijn bestellingen") {
        $headerService->header("bestellingOverzicht.php");
    }
}


include_once __DIR__ . "/Presentation/gegevensWijzigenPagina.php";
