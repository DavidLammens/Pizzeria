<section id="winkelmandSide">
    <h2>Winkelmand</h2>
    <?php if ($winkelmandService->heeftInhoud()) { ?>
        <table>
            <thead>
                <tr>
                    <th>Pizza</th>
                    <th>Aantal</th>
                    <th>Totaal</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($winkelmandService->getInhoud() as $winkelmandLijn) {
                ?>
                    <tr>
                        <td><?php echo $winkelmandLijn->getPizzaNaam() ?></td>
                        <td><?php echo $winkelmandLijn->getAantal() ?></td>
                        <td>€ <?php echo $winkelmandLijn->getPrintTotaal() ?></td>
                        <td>
                            <form action="index.php" method="post">
                                <input type="hidden" name="pizzaId" value="<?php echo $winkelmandLijn->getPizza()->getId() ?>">
                                <input type="hidden" name="action" value="verwijderLijn">
                                <input type="submit" title="Verwijder lijn" class="btn delete X" value="X">
                            </form>
                        </td>
                    </tr>
                <?php
                }
                ?>
            </tbody>
            <tfoot>
                <tr>
                    <td></td>
                    <th>Totaal: </th>
                    <th colspan="2"> € <?php echo $winkelmandService->getPrintTotaal() ?></th>
                </tr>
            </tfoot>
        </table>
        <form action="bestellen.php" method="post">
            <input type="submit" value="Afrekenen" class="btn">
        </form>
        <form action="index.php" method="post">
            <input type="submit" value="Leeg maken" name="action" class="btn delete ">
        </form>
    <?php } else { ?>
        <p>Uw winkelmand is leeg</p>
    <?php } ?>
</section>