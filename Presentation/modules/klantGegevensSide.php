<div id="klantGegevensSide">
    <h2>Uw gegevens</h2>
    <ul>
        <li><?php echo $klantService->getKlantFamilienaam() . " " . $klantService->getKlantVoornaam() ?></li>
        <li><?php echo $klantService->getKlantStraat() . " " . $klantService->getKlantHuisnummer() . " " . $klantService->getKlantBus() ?></li>
        <li><?php echo $klantService->getKlantPostcode() . " " . $klantService->getKlantPlaatsNaam() ?></li>
        <li>tel. <?php echo $klantService->getKlantTelefoonnummer() ?></li>
        </li>
    </ul>
    <form action="gegevensWijzigen.php" method="post">
        <input type="hidden" name="header" value="<?php echo $headerService->getLocation() ?>">
        <input type="submit" value="Gegevens aanpassen" name="action" class="btn">
    </form>
</div>