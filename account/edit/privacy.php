<?php
include_once('../../inc/sessiecontrole.inc.php');
include_once('../../inc/feedbackbox.inc.php');
include_once('../../classes/User.class.php');

if (isset($_POST['editPrivacy'])) {

    //sessievariabele private ophalen
    $sessionvarPrivateAccount = $_SESSION['login']['private'];

    //controleer of checkbox aangevinkt is of niet
    if (isset($_POST['checkPrivateAccount'])) {
        $privateState = 1;
    } else {
        $privateState = 0;
    }

    //controleer of de checkbox status (1 = aan, 0 = uitgevinkt) verschilt van de waarde 'private' in de sessie
    if ($privateState != $sessionvarPrivateAccount) {
        //gebruiker will de privacy status veranderen
        //probeer deze te veranderen
        try{
            $accountState = new User();
            $accountState->setMiUserAccountState($privateState);

            if($accountState->canUpdateAccountState()){
                $_SESSION['login']['private'] = $privateState;
                $feedback = buildFeedbackBox("success", "Je privacyinstellingen zijn bijgewerkt.");
            }else{
                $feedback = buildFeedbackBox("danger", "door een technisch probleem kunnen je privacy instellingen niet bijgewerkt worden. Probeer het later opnieuw.");
            }

        }catch (Exception $e){
            //updaten niet gelukt. Toon fout
            $errorMessage = $e->getMessage();
            $feedback = buildFeedbackBox("danger", $errorMessage);
        }
    } else {
        //instellingen blijven hetzelfde, er moet niets aangepast worden. Toon meteen success feedback
        $feedback = buildFeedbackBox("success", "Je privacyinstellingen zijn bijgewerkt.");
    }

}

?><!doctype html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Profiel bewerken</title>
    <?php include_once ('../../inc/style.inc.php'); ?>
</head>
<body class="template">

<?php include_once ('../../inc/header.inc.php'); ?>


    <!-- start pagina profiel bewerken  -->

<?php include_once ('../../inc/preferences-sidebar.inc.php'); ?>


    <!-- start profiel content sectie -->

    <h1>Privacy</h1>



<!-- Start sectie privacy -->
<section class="settings privacy">



    <!-- start form privacy -->
    <form action="" method="POST">
        <?php if(!empty($feedback)){echo $feedback;} ?>
        <div class="form-group">
            <!-- Openbaar profiel  -->
            <label class="checkbox" for="checkPrivateAccount">
                <input
                    type="checkbox"
                    data-toggle="checkbox"
                    name="checkPrivateAccount"
                    id="checkPrivateAccount"
                    <?php if ($_SESSION['login']['private'] == "1") {print "checked";} ?>>
                <!-- Moet de checkbox default aangevinkt worden of niet? -->
                <span class="vet">Maak mijn account priv&eacute;. </span><br>Alleen geaccepteerde personen kunnen mijn foto's bekijken.

            </label>
        </div>


        <input type="submit"
               name="editPrivacy"
               value="Wijzig privacyvoorkeuren"
               id="editPrivacy"
               class="btn btn-primary btn-large">


    </form>
    <!-- einde form privacy-->

</section>
<!-- Einde sectie privacy  -->

            </div>
        <div class="clearfix"></div>
        </div>
    </div>
</div>

<?php include_once ('../../inc/footer.inc.php'); ?>
</body>
</html>