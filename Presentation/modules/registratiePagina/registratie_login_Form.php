<section id="registratie" class="form">
    <h2>Uw gegevens</h2>

    <a href="index.php">Terug naar menu</a>
    <div>
        <section class="subCards">
            <h3 class="toggler">Ik heb een account</h3>
            <form action="login.php?alAccount" method="post" class="<?php echo $alAccountHide ?>">
                <div>
                    <label for="emailadresInput">Emailadres</label>
                    <input type="email" name="emailadres" id="emailadresInput" value="<?php echo $klantService->getEmailCookie() ?>">
                    <label for="wachtwoordInput">Wachtwoord</label>
                    <input type="password" name="wachtwoord" id="wachtwoordInput">
                    <input type="hidden" name="header" value="<?php echo $headerService->getLocation() ?>">
                    <input type="submit" value="Aanmelden" name="action" class="btn">
                </div>
            </form>
        </section>
        <section class="subCards">
            <h3 class="toggler">Ik heb geen account</h3>
            <form action="registratie.php" method="post" class="<?php echo $geenAccountHide ?>">
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
                    <input type="checkbox" name="ookAccount" id="ookAccount" <?php echo $ookAccountChecked ?>>
                    <label for="ookAccount">Ik wil ook een acccount aanmaken</label>
                </div>
                <div id="registratieForm" class="<?php echo $ookAccountHide ?>">
                    <label>Emailadres</label>
                    <input type="text" name="emailadres" placeholder="Emailadres*">
                    <label>Wachtwoord</label>
                    <input type="password" name="wachtwoord" placeholder="Wachtwoord*">
                    <label>Wachtwoord opnieuw</label>
                    <input type="password" name="wachtwoordOpnieuw" placeholder="Herhaal wachtwoord*">
                    <input type="checkbox" name="promo" id="promo">
                    <label for="promo">Ja, stuur me aanbiedingen via email!</label>
                </div>
                <div>
                    <div>
                        <p>*De velden met een * zijn verplicht</p>
                        <input type="hidden" name="header" value="<?php echo $headerService->getLocation() ?>">
                        <input type="hidden" name="action" value="registreren">
                        <input type="submit" value="Ok" class="btn">
                    </div>
                </div>
            </form>
        </section>
    </div>
</section>