<section class="form" id="accountGegevens">
    <form action="accountGegevensWijzigen.php" method="post">
        <div>
            <label>Emailadres</label>
            <input type="text" name="emailadres" placeholder="Emailadres*">
            <label>Wachtwoord</label>
            <input type="password" name="oudWachtwoord" placeholder="Wachtwoord*">
            <input type="checkbox" name="wachtwoordWijzigen" id="wachtwoordWijzigen">
            <label for="wachtwoordWijzigen">Wachtwoord Wijzigen</label>
        </div>
        <div id="wachtwoordWijzigenForm" class="hide">
            <label>Nieuw wachtwoord</label>
            <input type="password" name="nieuwWachtwoord" placeholder="Nieuw wachtwoord*">
            <label>Nieuw wachtwoord opnieuw</label>
            <input type="password" name="nieuwWachtwoordOpnieuw" placeholder="Herhaal nieuw wachtwoord*">
        </div>
        <div>
            <input type="checkbox" name="promo" id="promo" <?php echo $promoCheckbox ?>>
            <label for="promo">Ja, stuur me aanbiedingen via email!</label>
        </div>
        <div>
            <div>
                <p>*De velden met een * zijn verplicht</p>
                <input type="submit" name="action" value="Wijzigen" class="btn">
                <input type="submit" name="action" value="Annuleren" class="btn">
            </div>
        </div>
    </form>
</section>