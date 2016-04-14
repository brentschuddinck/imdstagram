<?php
include_once('../../inc/sessiecontrole.inc.php');
include_once('../../inc/feedbackbox.inc.php');
include_once('../../classes/Validation.class.php');
include_once('../../classes/User.class.php');

if (isset($_POST['editPassword']) &&
    !empty($_POST['inputOldPassword']) &&
    !empty($_POST['inputNewPassword']) &&
    !empty($_POST['inputRepeatNewPassword'])
) {

    //invoervelden zijn netjes ingevuld

    //variabelen
    $sOldPassword = $_POST['inputOldPassword'];
    $sNewPassword = $_POST['inputNewPassword'];
    $sRepeatNewPassword = $_POST['inputRepeatNewPassword'];


    //eerst kijken of beide passwoorden matchen
    $validation = new Validation();
    $validation2 = new Validation();
    if ($validation->matchNewpassword($sNewPassword, $sRepeatNewPassword)) {
        //nieuwe wachtwoorden komen overeen


        //controleer of nieuw wachtwoord veilig genoeg is
        if(empty($validation2->isValidPassword($sNewPassword))){
            //daarna kijken of oude wachtwoord klopt
            $passwordMatch = new User();
            $passwordMatch->setMSWachtwoord($sOldPassword);

            //versleutel nieuw wachtwoord
            $hashOpties = ['cost' => 12];
            $passwordHash = password_hash($sNewPassword, PASSWORD_DEFAULT, $hashOpties);

            $passwordMatch->setMSNewPassword($passwordHash);
            $passwordMatch->setMIUserId($_SESSION['login']['userid']);

            try {
                if ($passwordMatch->updatePassword()) {
                    $feedback = buildFeedbackBox("success", "Je wachtwoord is gewijzigd.");
                }
            }catch (Exception $e) {
                $errorException = $e->getMessage();
                $feedback = buildFeedbackBox("danger", $errorException);
            }
        }else{
            $feedback = buildFeedbackBox("danger", "het nieuwe wachtwoord moet minstens 6 tekens lang zijn.");
        }

    } else {
        $feedback = buildFeedbackBox("danger", "de nieuwe wachtwoorden komen niet overeen.");
    }
}else if(isset($_POST['editPassword'])){
    $feedback = buildFeedbackBox("danger", "vul een wachtwoord in.");
}

?><!doctype html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Profiel bewerken</title>
    <?php include_once('../../inc/style.inc.php'); ?>
</head>
<body class="template">

<?php include_once('../../inc/header.inc.php'); ?>


<!-- start pagina profiel bewerken  -->

<?php include_once('../../inc/preferences-sidebar.inc.php'); ?>


<!-- start profiel content sectie -->

<h1>Wachtwoord wijzigen</h1>


<!-- Start sectie wachtwoord wijzigen -->
<section class="settings password" id="wachtwoord">

    <!-- start form profielinstellingen -->
    <form action="" method="POST">

        <?php
        //toon errorboodschap
        if (!empty($feedback)) {
            echo $feedback;
        }
        ?>


        <!-- start formuliergroep oud wachtwoord -->
        <!-- Oud wachtwoord  -->
        <label for="inputOldPassword" class="col-lg-3 control-label">Oud wachtwoord:</label>
        <div class="col-lg-9 lg-together">
            <input
                type="password"
                class="form-control col-lg-9"
                id="inputOldPassword"
                placeholder="Oud wachtwoord"
                title="Vul je oude wachtwoord in."
                name="inputOldPassword"
                autofocus
                required>
        </div>
        <!-- einde formuliergroep profielfoto -->

        <!-- start formuliergroep nieuw wachtwoord -->
        <div class="form-group">


            <!-- Nieuw wachtworod -->
            <label for="inputNewPassword" class="col-lg-3 control-label">Nieuw wachtwoord:</label>
            <div
                class="col-lg-9 lg-together">
                <input
                    type="password"
                    class="form-control col-lg-9"
                    id="inputNewPassword"
                    placeholder="Nieuw wachtwoord"
                    name="inputNewPassword"
                    title="Vul je nieuwe wachtwoord in."
                    required>
            </div>

            <!-- Nieuw wachtwoord herhalen -->
            <label for="inputRepeatNewPassword" class="col-lg-3 control-label">Herhaal wachtwoord:</label>
            <div class="col-lg-9 lg-together">
                <input
                    type="password"
                    class="form-control col-lg-9"
                    id="inputRepeatNewPassword"
                    placeholder="Herhaal nieuw wachtwoord"
                    name="inputRepeatNewPassword"
                    title="Herhaal je nieuwe achtwoord ter bevestiging."
                    required>
            </div>

        </div>
        <!-- einde formuliergroep accountinstellingen -->



        <input type="submit"
               name="editPassword"
               value="Wachtwoord wijzigen"
               id="editPassword"
               class="btn btn-primary btn-large">


    </form>
    <!-- einde form wachtwoord wijzigen-->

</section>
<!-- Einde sectie wachtwoord wijzigen -->


</div>
<div class="clearfix"></div>
</div>
</div>
</div>

<?php include_once('../../inc/footer.inc.php'); ?>
</body>
</html>