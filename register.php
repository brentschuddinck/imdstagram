<?php
include_once('inc/sessiecontrole.inc.php');
?><!doctype html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Registreer</title>
    <meta name="description" content="Maak een nieuw IMDstagram account aan.">
    <?php include_once('inc/style.inc.php'); ?>
</head>
<body>

<section>
    <form action="index.php" method="POST">

        <label for="naam">Voor- en familienaam</label>
        <input type="text" name="naam" placeholder="Voor- en familienaam" required>

        <label for="email">E-mailadres</label>
        <input type="email" name="email" placeholder="@student.thomasmore.be" required>

        <label for="gebruikersnaam">Gebruikersnaam</label>
        <input type="text" name="gebruikersnaam" placeholder="Gebruikersnaam" required>

        <label for="wachtwoord">Wachtwoord</label>
        <input type="password" name="wachtwoord" required>

        <input type="submit" name="registreer" value="Registreren">

    </form>
</section>

</body>
</html>