<section id="gebruikerSide">

    <h2>Account</h2>
    <?php if ($klantService->ingelogd()) { ?>

        <h4>Welkom <?php echo $klantService->getKlantVoornaam() ?>!</h4>

        <?php include_once __DIR__ . "/../klantGegevensSide.php" ?>

        <form action="login.php" method="post">
            <input type="submit" value="Afmelden" name="action" class="btn delete">
        </form>

    <?php } else { ?>

        <form action="login.php" method="post">

            <label for="emailadresInput">Emailadres</label>
            <input type="email" name="emailadres" id="emailadresInput" value="<?php echo $klantService->getEmailCookie() ?>">

            <label for="wachtwoordInput">Wachtwoord</label>
            <input type="password" name="wachtwoord" id="wachtwoordInput">

            <input type="hidden" name="header" value="<?php echo $headerService->getLocation() ?>">
            <input type="submit" value="Aanmelden" name="action" class="btn">

        </form>

        <form action="registratie.php" method="post">

            <input type="hidden" name="header" value="<?php echo $headerService->getLocation() ?>">
            <input type="hidden" name="ookAccount" value="ok">
            <input type="submit" value="Registreren" class="btn">

        </form>

    <?php } ?>

</section>