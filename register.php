<?php
include_once('inc/sessiecontrole.inc.php');

//autoload classes
spl_autoload_register(function ($class_name) {
    include 'classes/' .$class_name . '.class.php';
});


if(isset($_POST['registreer']) && !empty($_POST['registreer'])){

    //invoervariabelen
    $voornaamfamilienaam = $_POST['naam'];
    $emailadres = $_POST['email'];
    $gebruikersnaam = $_POST['gebruikersnaam'];


    //account valideren en registreren
    $opties = ['cost' => 12];
    $wachtwoord = password_hash($_POST['wachtwoord'], PASSWORD_DEFAULT, $opties);


    //probeer account te valideren, registreren en toon feedback
    try {
        $user = new User();

        $user->setMSVoornaamFamilienaam($voornaamfamilienaam);
        $user->setMSGebruikersnaam($gebruikersnaam);
        $user->setMSEmailadres($emailadres);
        $user->setMSWachtwoord($wachtwoord);
        $user->Registreer();
        $success = "Yeah! Je account is aangemaakt. <a href='login.php'>Log hier in</a>.";

    } catch(Exception $e) {
        $error = $e->getMessage();
    }

}
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
    <a href="#">Log in met Facebook</a>
    <form action="<?php $_SERVER['PHP_SELF'] ?>" method="POST">
        <label for="naam">Voor- en familienaam</label>
        <input type="text" name="naam" id="naam" placeholder="Voor- en familienaam" required autofocus title="Vul je voor- en familienaam in.">

        <label for="email">E-mailadres</label>
        <input type="email" name="email" id="email" placeholder="r@student.thomasmore.be" required title="Vul je Thomas More e-mailadres in.">

        <label for="gebruikersnaam">Gebruikersnaam</label>
        <input type="text" name="gebruikersnaam" id="gebruikersnaam" placeholder="Gebruikersnaam" required title="Vul een gebruikersnaam in.">

        <label for="wachtwoord">Wachtwoord</label>
        <input type="password" name="wachtwoord" id="wachtwoord" placeholder="Wachtwoord" required title="Kies een wachtwoord (minimaal 6 tekens).">

        <input type="submit" name="registreer" value="Registreren">

        <?php
            if(!empty($success))
            {
                echo "<div class='feedback success'>$success</div>";
            }else if(!empty($error)){
                echo "<div class='feedback error'>$error</div>";
            }
        ?>
        <div>Bij het aanmaken van een IMDstagram account ga je akkoord met de <a href="#">voorwaarden</a>.</div>
    </form>
</section>
<?php include_once('inc/footer.inc.php'); ?>
</body>
</html>