<section id="pizzaMenu">

    <h2>Menu</h2>

    <div>
        <?php foreach ($pizzaSoorten as $pizzaSoort) { ?>
            <article class="subCards">
                <h3>Pizza <?php echo $pizzaSoort->getSoort()->getNaam() ?></h3>
                <div>
                    <img src="img/pizza<?php echo $pizzaSoort->getSoort()->getId() ?>.png" alt="Pizza <?php echo $pizzaSoort->getSoort()->getNaam() ?>">

                    <div>
                        <div class="innerCard">
                            <h4>Ingrediënten</h4>
                            <p>
                                <?php echo $pizzaSoort->getPrintIngrediëntenLijst() ?>
                            </p>
                        </div>
                        <form action="index.php" method="post">
                        <div class="selectInput">
                            <select name="pizzaId" id="pizzaKeuze<?php echo $pizzaSoort->getSoort()->getId() ?>">
                                <?php 
                                foreach ($pizzaSoort->getPizzas() as $pizza) {
                                    if ($klantService->getKlantPromo()) {
                                        $prijs = $pizza->getPrintPromotiePrijs();
                                    } else {
                                        $prijs = $pizza->getPrintPrijs();
                                    }
                                    if ($pizza->getFormaat() === "medium") {
                                        $selected = "selected";
                                    } else {
                                        $selected = "";
                                    }
                                ?>
                                    <option value="<?php echo $pizza->getId() ?>" <?php echo $selected ?>>
                                        <?php echo $pizza->getFormaat() ?> - € <?php echo $prijs ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                        <div>
                            <label for="pizzaMenuAantal">Aantal</label>
                            <input type="number" name="aantal" id="pizzaMenuAantal<?php echo $pizza->getId() ?>" min="1" max="10" value="1">
                        </div>
                        <input type="submit" name="action" value="Toevoegen" class="btn">
                    </form>
                    </div>
                </div>
            </article>
        <?php } ?>
    </div>
</section>