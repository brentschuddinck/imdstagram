<?php
include_once('inc/sessiecontrole.inc.php');

//autoload classes
spl_autoload_register(function ($class_name) {
    include 'classes/' . $class_name . '.class.php';
});


if (isset($_POST['registreer']) && !empty($_POST['registreer'])) {

    //invoervariabelen
    $voornaamfamilienaam = $_POST['naam'];
    $emailadres = $_POST['email'];
    $gebruikersnaam = strtolower($_POST['gebruikersnaam']);
    $wachtwoord = $_POST['wachtwoord'];


    //invoer valideren via Validation classe en resultaat toevoegen aan array errors
    $validation = new Validation();
    $errors["emailadres"] = $validation->isGeldigEmailadres($emailadres);
    $errors["voornaamfamilienaam"] = $validation->isGeldigVoornaamFamilienaam($voornaamfamilienaam);
    $errors["gebruikersnaam"] = $validation->isGeldigGebruikersnaam($gebruikersnaam);
    $errors["wachtwoord"] = $validation->isGeldigWachtwoord($wachtwoord);

    //verwijder juist ingevulde elemeneten (NULL) uit array
    $errors = array_filter($errors);


    //probeer account te registreren als er geen errors in array $errors zitten
    if(count($errors) == 0){

        //wachtwoord hashen. Hier plaatsen, anders telkens hashen als invoer niet correct is.
        $hashOpties = ['cost' => 12];
        $wachtwoordHash = password_hash($wachtwoord, PASSWORD_DEFAULT, $hashOpties);

        try {
            $user = new User();

            $user->setMSVoornaamFamilienaam($voornaamfamilienaam);
            $user->setMSEmailadres($emailadres);
            $user->setMSGebruikersnaam($gebruikersnaam);
            $user->setMSWachtwoord($wachtwoordHash);
            $user->Registreer();
            $successMessage = "<div class='feedback success'>Yeah! Je account is aangemaakt. <a href='login.php'>Log hier in</a>.</div>";

        } catch(Exception $e) {
            $errorMessage = $e->getMessage();
        }
    }else{
        //niet alle velden zijn juist ingevuld
        $errorMessage = "<div class=\"feedback error\">Controleer volgende velden:";
        foreach($errors as $error){
            $errorMessage .= "<li>$error</li>";
        }
        $errorMessage .= "</div>";
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
        <label for="email">E-mailadres</label>
        <input type="email" name="email" id="email" placeholder="r@student.thomasmore.be" required autofocus
               title="Vul je Thomas More e-mailadres in." value="<?php if(!empty($errorMessage)){echo htmlspecialchars($emailadres);} //vult gebruikersinvoer opnieuw in zodat gebruiker bij fout niet het hele formulier opnieuw moet invullen?>">

        <label for="naam">Voor- en familienaam</label>
        <input type="text" name="naam" id="naam" placeholder="Voor- en familienaam" required
               title="Vul je voor- en familienaam in." value="<?php if(!empty($errorMessage)){echo htmlspecialchars($voornaamfamilienaam);} ?>">

        <label for="gebruikersnaam">Gebruikersnaam</label>
        <input type="text" name="gebruikersnaam" id="gebruikersnaam" placeholder="Gebruikersnaam" required
               title="Vul een gebruikersnaam in." value="<?php if(!empty($errorMessage)){echo htmlspecialchars($gebruikersnaam);} ?>">

        <label for="wachtwoord">Wachtwoord</label>
        <input type="password" name="wachtwoord" id="wachtwoord" placeholder="Wachtwoord" required
               title="Kies een wachtwoord van minimaal 6 tekens.">

        <input type="submit" name="registreer" value="Registreren">

        <?php
            //toon succesboodschap of errorboodschap
            if(!empty($successMessage)){
                echo $successMessage;
            }else if(!empty($errorMessage)){
                echo $errorMessage;
            }
        ?>

        <div>Bij het aanmaken van een IMDstagram account ga je akkoord met de <a href="#">voorwaarden</a>.</div>
    </form>
</section>
<?php include_once('inc/footer.inc.php'); ?>
</body>
</html>