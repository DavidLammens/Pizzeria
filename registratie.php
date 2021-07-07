<?php

declare(strict_types=1);

use Business\HeaderService;
use Business\PlaatsService;
use Exceptions\DBException;
use Exceptions\EmailadresBestaatException;
use Exceptions\LegeInputException;
use Exceptions\OngeldigeInputException;
use Exceptions\TeVeelInputException;

require_once __DIR__ . "/controllerBootstrap/algemeneBootstrap.php";

$headerService = new HeaderService("bestellen.php");
$plaatsService = new PlaatsService;

try {
    $leverPlaatsen = $plaatsService->getLeverPlaatsen();
} catch (DBException $e) {
    $meldingService->setMelding("Oeps, er ging iets mis, probeer het later opnieuw");
}

if (isset($_POST['ookAccount'])) {
    $ookAccount = true;
    $ookAccountChecked = "checked";
    $ookAccountHide = null;
    $geenAccountHide = null;
} else {
    $ookAccount = false;
    $ookAccountChecked = null;
    $ookAccountHide = "hide";
    $geenAccountHide = "hide";
}


if (isset($_GET['alAccount'])) {
    $alAccountHide = null;
} else {
    $alAccountHide = "hide";
}

if (isset($_POST['action'])) {

    if ($_POST['action'] === "registreren") {
        $promo = (isset($_POST['promo']) ? true : false);
        $geenAccountHide = null;

        if (
            !$klantService->klantIsSaved() ||
            ($ookAccount && !$klantService->getKlant()->getGebruikersAccount())
        ) {
            try {
                $klant = $klantService->gegevensInvoeren(
                    $_POST['familienaam'] ?? "",
                    $_POST['voornaam'] ?? "",
                    $_POST['straat'] ?? "",
                    $_POST['huisnummer'] ?? "",
                    $_POST['bus'] ?? "",
                    (int) $_POST['plaatsId'] ?? 0,
                    $_POST['telefoonnummer'] ?? "",
                    "",
                    $promo,
                    $ookAccount,
                    $_POST['emailadres'] ?? "",
                    $_POST['wachtwoord'] ?? "",
                    $_POST['wachtwoordOpnieuw'] ?? ""
                );
                $meldingService->setMelding("Uw gegevens zijn geregistreerd");
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
                if ($e->getVeld() === "wachtwoordOpnieuw") {
                    $meldingService->setMelding(
                        "Gelieve 2x hetzelfde wachtwoord in te geven"
                    );
                }
            } catch (TeVeelInputException $e) {
                $meldingService->setMelding(
                    "De maximum lengte voor '" . $e->getVeld() . "' is " . $e->getMaxInput() . " tekens."
                );
            } catch (LegeInputException $e) {
                $meldingService->setMelding(
                    "Het veld '" . $e->getVeld() . "' is verplicht."
                );
            } catch (EmailadresBestaatException $e) {
                $meldingService->setMelding(
                    "Dit emailadres is al in gebruik."
                );
            }
        }
    }
}

include_once __DIR__ . "/Presentation/registratiePagina.php";
