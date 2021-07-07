<?php

declare(strict_types=1);

use Business\HeaderService;
use Exceptions\DBException;
use Exceptions\LegeInputException;
use Exceptions\OngeldigeInputException;

require_once __DIR__ . "/controllerBootstrap/algemeneBootstrap.php";

$headerService = new HeaderService("registratie.php");

if (isset($_POST['action'])) {

    if ($_POST['action'] === "Aanmelden") {

        try {
            $klantService->login(
                $_POST['emailadres'] ?? "",
                $_POST['wachtwoord'] ?? ""
            );
            $meldingService->setMelding(
                "U bent succesvol aangemeld"
            );
            $headerService->header();
        } catch (DBException $e) {
            $meldingService->setMelding(
                "Oeps, er ging iets mis, probeer het later opnieuw"
            );
        } catch (OngeldigeInputException $e) {
            $meldingService->setMelding(
                "Emailadres en wachtwoord komen niet overeen"
            );
        } catch (LegeInputException $e) {
            $meldingService->setMelding(
                "Het veld '" . $e->getVeld() . "' is verplicht"
            );
        }
    }

    if ($_POST['action'] === "Afmelden") {
        $klantService->logout();
        $meldingService->setMelding(
            "U bent afgemeld."
        );
        $headerService->header("index.php");
    }
}

if (isset($_GET['alAccount'])){
    $headerService->header("registratie.php?alAccount");
}else{
    $headerService->header();
}
