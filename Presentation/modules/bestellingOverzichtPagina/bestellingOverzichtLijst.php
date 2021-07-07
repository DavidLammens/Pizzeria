<section id="bestellingOverzicht">

    <h2>Uw bestelling</h2>

    <a href="index.php">Terug naar pizzamenu</a>

    <div class="subCards">
        <h3><?php echo $bestellingen[0]->getPrintTijdstip() ?></h3>
        <div>
            <div>Status: <?php echo $bestellingen[0]->getStatus()->getNaam() ?></div>
            <ul>
                <?php foreach ($bestellingen[0]->getBestellijnen() as $bestellijn) { ?>
                    <li>
                        <div><?php echo $bestellijn->getPizza()->getSoort()->getNaam() . " " . $bestellijn->getPizza()->getFormaat() ?></div>
                        <div>Aantal: <?php echo $bestellijn->getAantal() ?></div>
                        <div>Prijs per stuk: €<?php echo $bestellijn->getPrintPrijsPerStuk() ?></div>
                        <div>Totaal: €<?php echo $bestellijn->getPrintTotaal() ?></div>
                    </li>
                <?php } ?>
            </ul>
            <div>
                <div>Opmerkingen:</div>
                <div class="opmerkingen"><?php echo $bestellingen[0]->getOpmerkingen() ?></div>
            </div>
            <div class="totaal">Totaal: €<?php echo $bestellingen[0]->getPrintTotaal() ?></div>
        </div>
    </div>

    <?php if (count($bestellingen) > 1) { ?>
        <h2>Eerdere bestellingen</h2>

        <ul>
            <?php for ($i = 1; $i < count($bestellingen); $i++) { ?>
                <li class="subCards">
                    <h3><?php echo $bestellingen[$i]->getPrintTijdstip() ?></h3>
                    <div>

                        <div>Status: <?php echo $bestellingen[$i]->getStatus()->getNaam() ?></div>

                        <ul>
                            <?php foreach ($bestellingen[$i]->getBestellijnen() as $bestellijn) { ?>
                                <li>

                                    <div><?php echo $bestellijn->getPizza()->getSoort()->getNaam() . " " . $bestellijn->getPizza()->getFormaat() ?></div>
                                    <div>Aantal: <?php echo $bestellijn->getAantal() ?></div>
                                    <div>Prijs per stuk: €<?php echo $bestellijn->getPrintPrijsPerStuk() ?></div>
                                    <div>Totaal: €<?php echo $bestellijn->getPrintTotaal() ?></div>

                                </li>
                            <?php } ?>
                        </ul>

                        <div>
                            <div>Opmerkingen:</div>
                            <div class="opmerkingen"><?php echo $bestellingen[$i]->getOpmerkingen() ?></div>
                        </div>

                        <div class="totaal">Totaal: €<?php echo $bestellingen[$i]->getPrintTotaal() ?></div>
                    </div>
                </li>
            <?php } ?>
        </ul>
    <?php } ?>
</section>