<?php

declare(strict_types=1);

use Exceptions\DBException;
use Exceptions\EmailadresBestaatException;
use Exceptions\LegeInputException;
use Exceptions\OngeldigeInputException;
use Exceptions\TeVeelInputException;

require_once __DIR__ . "/controllerBootstrap/algemeneBootstrap.php";

if (!$klantService->ingelogd()) {
    header("Location: gegevensWijzigen.php");
    exit(0);
}

$promoCheckbox = $klantService->getKlantPromo() ? "checked" : null;


if (isset($_POST['action'])) {
    if ($_POST['action'] === 'Wijzigen') {
        try {
            $klantService->accountGegevensWijzigen(
                $_POST['emailadres'] ?? "",
                $_POST['oudWachtwoord'] ?? "",
                $_POST['wachtwoordWijzigen'] ?? null,
                $_POST['nieuwWachtwoord'] ?? "",
                $_POST['nieuwWachtwoordOpnieuw'] ?? ""
            );
            if (isset($_POST['promo'])) {
                $promo = true;
            } else {
                $promo = false;
            }
            $klantService->saveKlantPromo($promo);
            $meldingService->setMelding("Uw account gegevens zijn succesvol gewijzigd.");
            header("Location: gegevensWijzigen.php");
            exit(0);
        } catch (DBException $e) {
            $meldingService->setMelding(
                "Oeps er ging iets mis, probeer het later opnieuw."
            );
        } catch (OngeldigeInputException $e) {
            $melding = "Dit is geen geldig " . $e->getVeld() . ".";
            $meldingService->setMelding($melding);
            if ($e->getVeld() === "nieuwWachtwoordOpnieuw") {
                $meldingService->setMelding(
                    "Gelieve 2x hetzelfde wachtwoord in te geven"
                );
            }
            if ($e->getVeld() === "oudWachtwoord") {
                $meldingService->setMelding(
                    "Gelieve in het veld 'wachtwoord' uw huidige wachtwoord op te geven."
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

    if ($_POST['action'] === 'Annuleren') {
        header("Location: gegevensWijzigen.php");
        exit(0);
    }
}

include_once __DIR__ . "/Presentation/accountGegevensWijzigenPagina.php";
