<?php
include_once('inc/sessiecontrole.inc.php');
include_once('inc/feedbackbox.inc.php');

//autoload classes
spl_autoload_register(function ($class_name) {
    include 'classes/' . $class_name . '.class.php';
});

//hulpvariabele om in html input niet opnieuw te tonen bij voltooide registratie
$madeAccount = false;

if (isset($_POST['Register']) && !empty($_POST['Register'])) {

    //invoervariabelen
    $fullname = ucwords($_POST['naam']); //ucwords zet de eerste letter van elk woord in een hoofdletter
    $email = $_POST['email'];
    $username = strtolower($_POST['gebruikersnaam']);
    $password = $_POST['wachtwoord'];


    //invoer valideren via Validation classe en resultaat toevoegen aan array errors
    $validation = new Validation();
    $errors["emailadres"] = $validation->isValidEmail($email);
    $errors["voornaamfamilienaam"] = $validation->isValidFullname($fullname);
    $errors["gebruikersnaam"] = $validation->isValidUsername($username);
    $errors["wachtwoord"] = $validation->isValidPassword($password);

    //verwijdert juist ingevulde elemeneten (NULL) uit array
    $errors = array_filter($errors);


    //probeer account te registreren als er geen errors in array $errors zitten
    if (count($errors) == 0) {

        //wachtwoord hashen. Hier plaatsen, anders telkens hashen als invoer niet correct is.
        $hashOpties = ['cost' => 12];
        $passwordHash = password_hash($password, PASSWORD_DEFAULT, $hashOpties);

        try {
            $user = new User();

            $user->setMSFullname($fullname);
            $user->setMSEmail($email);
            $user->setMSUsername($username);
            $user->setMSWachtwoord($passwordHash);
            $user->Register();

            $madeAccount = true;

            $feedback = buildFeedbackBox("success", "Je account is aangemaakt. <a href='login.php'>Log hier in</a>.");

        } catch (Exception $e) {
            $errorMessage = $e->getMessage();
            $feedback = buildFeedbackBox("danger", $errorMessage);
        }
    } else {
        //niet alle velden zijn juist ingevuld
        $feedbacktext = "controleer volgende velden:";
        //li met fouten ophalen
        foreach ($errors as $error) {
            $feedbacktext .= "<li>$error</li>";
        }

        $feedback = buildFeedbackBox("danger", $feedbacktext);
    }

}
?><!doctype html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <meta name="description" content="Maak een nieuw IMDstagram account aan.">
    <?php include_once('inc/style.inc.php'); ?>
</head>
<body class="template welcome">

<div class="container">
    <?php include_once('inc/header.inc.php'); ?>
    <div class="col-md-7"></div>
    <section class="col-md-5">


        <h1>Registreren</h1>
        <p>Maak een account aan om inspirerende foto's van je IMD collega's te bekijken en je creativiteit een boost te
            geven.</p>




        <form class="login-form" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST">

            <?php if(!empty($feedback)){echo $feedback;} ?>


            <!-- start facebook registratie -->

            <!--<div class="form-group">
                <input name="fblogin" id="fblogin" value="Login met Facebook" class="btn btn-primary btn-lg btn-block">
                <label class="login-field-icon fui-facebook" for="fblogin"></label>

            </div>-->

            <!-- einde facebook registratie -->


            <!-- veld email -->
            <div class="form-group">
                <input type="email" name="email" class="form-control login-field"
                       value="<?php if (!empty($feedback) && $madeAccount === false) {
                           echo htmlspecialchars($email);
                       } ?>" placeholder="r0123456@student.thomasmore.be" id="email" required autofocus
                       title="Vul je Thomas More e-mailadres in.">
                <label class="login-field-icon fui-mail" for="email"><span class="labeltext">E-mailadres</span></label>
            </div>

            <!-- veld naam -->
            <div class="form-group">
                <input type="text" name="naam" id="naam" class="form-control login-field"
                       value="<?php if (!empty($feedback) && $madeAccount === false) {
                           echo htmlspecialchars($fullname);
                       } ?>" placeholder="Volledige naam" required title="Vul je volledige naam in.">
                <label class="login-field-icon fui-user" for="naam"><span class="labeltext">Volledige naam</span></label>
            </div>

            <!-- veld gebruikersnaam -->
            <div class="form-group">
                <input type="text" name="gebruikersnaam" id="gebruikersnaam" class="form-control login-field"
                       value="<?php if (!empty($feedback) && $madeAccount === false) {
                           echo htmlspecialchars($username);
                       } ?>" placeholder="Gebruikersnaam" required title="Vul een gebruikersnaam in.">
                <label class="login-field-icon fui-user" for="gebruikersnaam"><span class="labeltext">Gebruikersnaam</span></label>
            </div>

            <!-- veld wachtwoord -->
            <div class="form-group">
                <input type="password" name="wachtwoord" id="wachtwoord" class="form-control login-field"
                       placeholder="Wachtwoord" required title="Kies een wachtwoord van minimaal 6 tekens.">
                <label class="login-field-icon fui-lock" for="wachtwoord"><span class="labeltext">Wachwoord</span></label>
            </div>

            <!-- formulier verzenden -->
            <input type="submit" name="Register" value="Registreren" class="btn btn-primary btn-lg btn-block">

            <!-- link loginpagina -->
            <a class="login-link" href="login.php">Heb je al een account? Naar de loginpagina.</a>


        </form>
    </section>

</div>

<?php include_once('inc/footer.inc.php'); ?>
</body>
</html>