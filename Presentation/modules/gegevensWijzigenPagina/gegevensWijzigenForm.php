<section class="form">
    <h2>Uw gegevens</h2>
    <form action="gegevensWijzigen.php" method="post">

        <div>
            <label>Voornaam</label>
            <input type="text" name="voornaam" placeholder="Voornaam*" value="<?php echo $klantService->getKlantVoornaam() ?>">
            <label>Familienaam</label>
            <input type="text" name="familienaam" placeholder="Familienaam*" value="<?php echo $klantService->getKlantFamilienaam() ?>">
            <label>Straat</label>
            <input type="text" name="straat" placeholder="Straatnaam*" value="<?php echo $klantService->getKlantStraat() ?>">
            <label>Huisnummer</label>
            <input type="text" name="huisnummer" placeholder="Nummer*" value="<?php echo $klantService->getKlantHuisnummer() ?>">
            <label>Bus</label>
            <input type="text" name="bus" placeholder="Bus" value="<?php echo $klantService->getKlantBus(); ?>">
            <label>Plaats</label>
            <input list="Plaatsen" id="PlaatsInput" placeholder="Plaats*" value="<?php echo $klantService->getKlantPostcodePlaats() ?>">
            <datalist id="Plaatsen">
                <?php foreach ($leverPlaatsen as $plaats) { ?>
                    <option data-value="<?php echo $plaats->getId() ?>"><?php echo $plaats->getPostcode() . " " . $plaats->getNaam() ?></option>
                <?php } ?>
            </datalist>
            <input type="hidden" name="plaatsId" id="PlaatsInput-hidden" value="<?php echo $klantService->getKlantPlaatsId() ?>">
            <label>Telefoonnummer</label>
            <input type="text" name="telefoonnummer" placeholder="Telefoonnummer*" value="<?php echo $klantService->getKlantTelefoonnummer() ?>">
            <div>
                <p>*De velden met een * zijn verplicht</p>
                <input type="hidden" name="header" value="<?php echo $headerService->getLocation() ?>">
                <input type="submit" value="Wijzigen" class="btn" name="action">
                <input type="<?php echo $accountBtnType ?>" value="Mijn account" name="action" class="btn">
                <input type="submit" value="Annuleren" name="action" class="btn">
            </div>
        </div>
    </form>
</section>