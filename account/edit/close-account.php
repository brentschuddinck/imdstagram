<?php
include_once('../../inc/sessiecontrole.inc.php');
include_once('../../inc/feedbackbox.inc.php');
include_once('../../classes/User.class.php');
include_once('../../classes/Validation.class.php');


if (isset($_POST['deleteAccount']) &&
    !empty($_POST['inputPasswordDelete'])
) {

    //variabelen
    $sPassword = $_POST['inputPasswordDelete'];
    $iUserId = $_SESSION['login']['userid'];

    //eerst kijken of beide passwoorden matchen
    $validation = new Validation();

    //daarna kijken of oude wachtwoord klopt
    $deleteUserAccount = new User();
    $deleteUserAccount->setMIUserId($iUserId);
    $deleteUserAccount->setMSWachtwoord($sPassword);

    try {
        if ($deleteUserAccount->deleteAccount()) {
            $feedback = buildFeedbackBox("success", "Je account en de daarbij horende gegevens zijn gewist. <a href='/imdstagram/logout.php'>Log uit</a>.");
            header('location: /imdstagram/logout.php');
        }
    } catch (Exception $e) {
        $errorException = $e->getMessage();
        $feedback = buildFeedbackBox("danger", $errorException);
    }

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

<h1>Account sluiten</h1>


<!-- Start sectie account sluiten -->
<section class="settings">

    <!-- start form account sluiten -->
    <form action="" method="POST">


        <?php
        //toon feedback
        if (!empty($feedback)) {
            echo $feedback;
        }
        ?>


        <p>IMDstagram niets voor jou? Dan kan je hier je account sluiten. Wanneer je dat doet, zullen alle opgeslagen foto's, connecties, likes, ... gewist worden. Je zal niet meer kunnen inloggen op IMDstagram. <span class="vet">Let op: deze actie kan niet ongedaan gemaakt worden.</span>
        </p>


        <div class="form-group">
            <!-- Oud wachtwoord  -->
            <label for="inputPasswordDelete" class="col-lg-12 control-label">Wachtwoord:</label>
            <div class="col-md-8 lg-together">
                <input
                    type="password"
                    class="form-control col-lg-9"
                    id="inputPasswordDelete"
                    title="Vul je wachtwoord in."
                    name="inputPasswordDelete"
                    placeholder="Wachtwoord"
                    required
                    autofocus>
            </div>
        </div>

<div class="col-md-12 lg-together">
    <input type="submit"
           name="deleteAccount"
           id="deleteAccount"
           value="Verwijder mijn account"
           class="btn btn-danger btn-large">
</div>



    </form>
    <!-- einde form account sluiten-->

</section>
<!-- Einde sectie account sluiten  -->


</div>
<div class="clearfix"></div>
</div>
</div>
</div>

<?php include_once('../../inc/footer.inc.php'); ?>
</body>
</html>