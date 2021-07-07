<section id="winkelmandMain">

    <h2>Winkelmand</h2>

    <div id="winkelmandLijnen" class="subCards">

    <h3>Pizzas</h3>

        <?php foreach ($winkelmandService->getInhoud() as $winkelmandLijn) { ?>

            <div class="winkelmandLijn innerCard">

                <h4><?php echo $winkelmandLijn->getPizzaNaam() ?></h4>

                <div>
                    <div>
                        <form action="bestellen.php" method="post">
                            <label for="aantal<?php echo $winkelmandLijn->getPizza()->getId() ?>">Aantal: </label>
                            <input class="aantal" type="number" name="aantal<?php echo $winkelmandLijn->getPizza()->getId() ?>" id="aantal<?php echo $winkelmandLijn->getPizza()->getId() ?>" value="<?php echo $winkelmandLijn->getAantal() ?>" min="1" max="25">
                            <input type="hidden" name="pizzaId" value="<?php echo $winkelmandLijn->getPizza()->getId() ?>">
                            <input type="hidden" value="updateLijn" name="action">
                            <input type="submit" value="Update" title="Update aantal" class="btn">
                        </form>
                    </div>
                    <div> Totaal: € <?php echo $winkelmandLijn->getPrintTotaal() ?></div>
                    <div>
                        <form action="bestellen.php" method="post">
                            <input type="hidden" name="pizzaId" value="<?php echo $winkelmandLijn->getPizza()->getId() ?>">
                            <input type="hidden" value="verwijderLijn" name="action">
                            <input type="submit" value="Verwijder" title="Verwijder pizza" class="btn delete">
                        </form>
                    </div>
                </div>

            </div>

        <?php } ?>

    </div>

    <div class="subCards">
        <h3>Bestellen</h3>

            <form action="bestellen.php" method="post">
                <div class="opmerkingen">
                    <label for="opmerkingen">Opmerkingen </label>
                    <textarea name="opmerkingen" id="opmerkingen" cols="25" rows="4" maxlength="160"><?php echo $klantService->getKlant()->getOpmerkingen() ?></textarea>
                </div>
                <div id="totaal">Totaal: € <?php echo $winkelmandService->getPrintTotaal() ?></div>
                <div>
                    <input type="submit" value="Bestel" class="btn" title="Bestelling doorvoeren" name="action">
                    <input type="submit" value="Pizzamenu" class="btn delete" title="Terug naar menu" name="action">
                </div>
            </form>

    </div>

</section>