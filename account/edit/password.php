<?php
include_once('../../inc/sessiecontrole.inc.php');
include_once('../../inc/feedbackbox.inc.php');
include_once('../../classes/Validation.class.php');
include_once('../../classes/User.class.php');

if (isset($_POST['wijzigWachtwoord']) &&
    !empty($_POST['inputOudWachtwoord']) &&
    !empty('inputNieuwWachtwoord') &&
    !empty('inputHerhaalNieuwWachtwoord')
) {

    //invoervelden zijn netjes ingevuld

    //variabelen
    $sOudWachtwoord = $_POST['inputOudWachtwoord'];
    $sNieuwWachtwoord = $_POST['inputNieuwWachtwoord'];
    $sHerhaalNieuwWachtwoord = $_POST['inputHerhaalNieuwWachtwoord'];


    //eerst kijken of beide passwoorden matchen
    $validation = new Validation();
    $validation2 = new Validation();
    if ($validation->matchtNieuwWachtwoord($sNieuwWachtwoord, $sHerhaalNieuwWachtwoord)) {
        //nieuwe wachtwoorden komen overeen


        //controleer of nieuw wachtwoord veilig genoeg is
        if(empty($validation2->isGeldigWachtwoord($sNieuwWachtwoord))){
            //daarna kijken of oude wachtwoord klopt
            $passwordMatch = new User();
            $passwordMatch->setMSWachtwoord($sOudWachtwoord);

            //versleutel nieuw wachtwoord
            $hashOpties = ['cost' => 12];
            $wachtwoordHash = password_hash($sNieuwWachtwoord, PASSWORD_DEFAULT, $hashOpties);

            $passwordMatch->setMSNieuwWachtwoord($wachtwoordHash);
            $passwordMatch->setMIUserId($_SESSION['login']['userid']);

            try {
                if ($passwordMatch->updatePassword()) {
                    $feedback = bouwFeedbackBox("success", "Je wachtwoord is gewijzigd.");
                }
            }catch (Exception $e) {
                $errorException = $e->getMessage();
                $feedback = bouwFeedbackBox("danger", $errorException);
            }
        }else{
            $feedback = bouwFeedbackBox("danger", "het nieuwe wachtwoord moet minstens 6 tekens lang zijn.");
        }

    } else {
        $feedback = bouwFeedbackBox("danger", "de nieuwe wachtwoorden komen niet overeen.");
    }
}else if(isset($_POST['wijzigWachtwoord'])){
    $feedback = bouwFeedbackBox("danger", "vul een wachtwoord in.");
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
        <label for="inputOudWachtwoord" class="col-lg-3 control-label">Oud wachtwoord:</label>
        <div class="col-lg-9 lg-together">
            <input
                type="password"
                class="form-control col-lg-9"
                id="inputOudWachtwoord"
                placeholder="Oud wachtwoord"
                title="Vul je oude wachtwoord in."
                name="inputOudWachtwoord"
                autofocus
                required>
        </div>
        <!-- einde formuliergroep profielfoto -->

        <!-- start formuliergroep nieuw wachtwoord -->
        <div class="form-group">


            <!-- Nieuw wachtworod -->
            <label for="inputNieuwWachtwoord" class="col-lg-3 control-label">Nieuw wachtwoord:</label>
            <div
                class="col-lg-9 lg-together">
                <input
                    type="password"
                    class="form-control col-lg-9"
                    id="inputNieuwWachtwoord"
                    placeholder="Nieuw wachtwoord"
                    name="inputNieuwWachtwoord"
                    title="Vul je nieuwe wachtwoord in."
                    required>
            </div>

            <!-- Nieuw wachtwoord herhalen -->
            <label for="inputHerhaalNieuwWachtwoord" class="col-lg-3 control-label">Herhaal wachtwoord:</label>
            <div class="col-lg-9 lg-together">
                <input
                    type="password"
                    class="form-control col-lg-9"
                    id="inputHerhaalNieuwWachtwoord"
                    placeholder="Herhaal nieuw wachtwoord"
                    name="inputHerhaalNieuwWachtwoord"
                    title="Herhaal je nieuwe achtwoord ter bevestiging."
                    required>
            </div>

        </div>
        <!-- einde formuliergroep accountinstellingen -->



        <input type="submit"
               name="wijzigWachtwoord"
               value="Wachtwoord wijzigen"
               id="wijzigWachtwoord"
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