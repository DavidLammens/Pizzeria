<?php

declare(strict_types=1);

use Business\HeaderService;
use Business\PizzaService;
use Exceptions\DBException;
use Exceptions\TeVeelInputException;

require_once __DIR__ . "/controllerBootstrap/algemeneBootstrap.php";

$pizzaService = new PizzaService;
$headerService = new HeaderService("index.php", true);

try {
    $pizzaSoorten = $pizzaService->getBeschikbarePizzasInSoorten();
} catch (DBException $e) {
    $meldingService->setMelding("Oeps, er ging iets mis, probeer het later opnieuw");
    $pizzaSoorten = [];
}

if (isset($_POST['action'])) {

    if ($_POST['action'] === "Toevoegen") {

        try {
            $winkelmandService->voegToe(
                (int) $_POST['pizzaId'] ?? 0,
                (int) $_POST['aantal'] ?? 0
            );
        } catch (TeVeelInputException $e) {
            if ($e->getVeld() === "aantal totaal") {
                $meldingService->setMelding("Maximum " . $e->getMaxInput() . " pizzas per bestelling!");
            }
        }
    }

    if ($_POST['action'] === "verwijderLijn") {
        $winkelmandService->verwijderLijn((int) $_POST['pizzaId']);
    }

    if ($_POST['action'] === "Leeg maken") {
        $winkelmandService->ledigWinkelmand();
    }
}

include_once  __DIR__ . "/Presentation/landingspagina.php";
