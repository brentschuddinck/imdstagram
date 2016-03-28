<?php
include('inc/sessiecontrole.inc.php');
include_once('classes/User.class.php');

    if(!empty($_POST['gebruikeremail']) && !empty($_POST['wachtwoord'])){

        $userLogin = new User();
        $userLogin->setMSEmailadres($_POST['gebruikeremail']);
        $userLogin->setMSWachtwoord($_POST['wachtwoord']);

        try {
            if ($userLogin->canLogin()) {
                $_SESSION['login']['loggedin'] = 1;
                include('inc/sessiecontrole.inc.php');
            }
        }catch (Exception $e){
            $errorException = $e->getMessage();
            $errorMessage = "<div class=\"text-danger message\"><p>$errorException</p></div>";
        }
    }else if(isset($_POST['gebruikeremail']) && empty($_POST['gebruikeremail'])){
        $errorMessage = "<div class=\"text-danger message\"><p>Vul je e-mailadres in.</p></div>";
    }else if(isset($_POST['wachtwoord']) && empty($_POST['wachtwoord'])){
        $errorMessage = "<div class=<\"text-danger message\"><p>Vul je wachtwoord in.</p></div>";
    }


?><!doctype html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>IMDstagram login</title>
    <meta name="description" content="Login met je IMDstagram account.">
    <?php include_once('inc/style.inc.php'); ?>
</head>
<body class="template welcome">

<div class="container">
    <?php include_once('inc/header.inc.php'); ?>
    <div class="col-md-7 intro"></div>
    <section class="col-md-5">


        <h1>Inloggen</h1>
        <p>Log in met je IMDstagram account om inspirerende foto's van je IMD collega's te bekijken en je creativiteit een boost te geven.</p>


        <form class="login-form" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST">


            <?php
            //toon errorboodschap
            if (!empty($errorMessage)) {
                echo $errorMessage;
            }
            ?>

            <!-- start login met facebook -->

            <!--<div class="form-group">
                <input name="fblogin" id="fblogin" value="Login met Facebook" class="btn btn-primary btn-lg btn-block">
                <label class="login-field-icon fui-facebook" for="fblogin"></label>

            </div>-->

            <!-- einde login met facebook -->



            <div class="form-group">
                <input type="email" name="gebruikeremail" id="gebruikeremail" class="form-control login-field"
                       value="<?php echo isset($_POST['gebruikeremail']) ? htmlspecialchars($_POST['gebruikeremail']) : '' ?>"
                       placeholder="E-mailadres" required
                       title="Vul je e-mailadres in." autofocus>
                <label class="login-field-icon fui-user" for="gebruikeremail"><span class="labeltext">Gebruikersnaam of e-mailadres</span></label>
            </div>


            <div class="form-group">
                <input type="password" name="wachtwoord" id="wachtwoord" class="form-control login-field" placeholder="Wachtwoord" required title="Vul je wachtwoord in.">
                <label class="login-field-icon fui-lock" for="wachtwoord"><span class="labeltext">Wachtwoord</span></label>
            </div>



            <input type="submit" name="login" value="Inloggen" class="btn btn-primary btn-lg btn-block">

            <a class="login-link" href="register.php">Heb je nog geen account? Registreer je nu.</a>


        </form>
    </section>

</div>

<?php include_once('inc/footer.inc.php'); ?>

</body>
</html>