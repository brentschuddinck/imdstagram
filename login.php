<?php
include_once('inc/sessiecontrole.inc.php');

if (isset($_POST['registreer']) && !empty($_POST['registreer'])) {
    //invoervariabelen
    $gebruikeremail = $_POST['gebruikeremail'];
    $wachtwoord = $_POST['wachtwoord'];
}


?><!doctype html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>IMDstagram photowall</title>
    <meta name="description" content="Login met je IMDstagram account.">
    <?php include_once('inc/style.inc.php'); ?>
</head>
<body class="registration">

<div class="container">
    <?php include_once('inc/header.inc.php'); ?>
    <div class="col-md-7 intro"></div>
    <section class="col-md-5">


        <h1>Inloggen</h1>
        <p>Log in met je IMDstagram account om inspirerende foto's van je IMD collega's te bekijken en je creativiteit een boost te geven.</p>


        <form class="login-form" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST">


            <!-- start login met facebook -->

            <!--<div class="form-group">
                <input name="fblogin" id="fblogin" value="Login met Facebook" class="btn btn-primary btn-lg btn-block">
                <label class="login-field-icon fui-facebook" for="fblogin"></label>

            </div>-->

            <!-- einde login met facebook -->



            <div class="form-group">
                <input type="text" name="naam" id="naam" class="form-control login-field"
                       value="<?php if (!empty($errorMessage)) {
                           echo htmlspecialchars($gebruikeremail);
                       } ?>" placeholder="Gebruikersnaam of e-mailadres" required
                       title="Vul je gebruikersnaam of e-mailadres in." autofocus>
                <label class="login-field-icon fui-user" for="gebruikeremail"><span class="labeltext">Gebruikersnaam of e-mailadres</span></label>
            </div>


            <div class="form-group">
                <input type="password" name="wachtwoord" id="wachtwoord" class="form-control login-field"
                       placeholder="Wachtwoord" required title="Kies een wachtwoord van minimaal 6 tekens.">
                <label class="login-field-icon fui-eye" for="wachtwoord"><span
                        class="labeltext">Wachwoord</span></label>
            </div>

            <input type="submit" name="login" value="Inloggen" class="btn btn-primary btn-lg btn-block">

            <a class="login-link" href="register.php">Heb je nog geen account? Registreer je nu.</a>


        </form>
    </section>

</div>

<?php include_once('inc/footer.inc.php'); ?>

</body>
</html>