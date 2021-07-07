<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mama Mia!</title>
    <link rel="stylesheet" href="css/mamaMia.css">
    <link rel="shortcut icon" href="mamaMia.ico" type="image/x-icon">
    <script src="js/gegevensForm.js" defer></script>
    <script src="js/melding.js" defer></script>
</head>

<body>
    <header>
        <?php include_once __DIR__ . "/modules/hoofding.php" ?>
    </header>

    <?php include __DIR__ . "/modules/melding.php" ?>

    <main>
        <!-- <section id="side">
            
        </section> -->

        <section id="main">
            <?php include_once __DIR__ . "/modules/gegevensWijzigenPagina/gegevensWijzigenForm.php" ?>
        </section>
    </main>

    <footer>
        <?php include_once __DIR__ . "/modules/footer.php" ?>
    </footer>
</body>

</html>