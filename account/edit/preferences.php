<?php
include_once('../../inc/sessiecontrole.inc.php');
include_once('../../inc/feedbackbox.inc.php');
include_once('../../classes/Validation.class.php');
include_once('../../classes/User.class.php');


if (isset($_POST['editProfilePreferences'])) {

    //er is op de knop editProfilePreferences geklikt


    //invoervariabelen
    $fullname = $_POST['inputName'];
    $email = $_POST['inputEmail'];
    $username = strtolower($_POST['inputUsername']);


    //onnodige query voorkomen als er niets gewijzigd werd. controleer of huidige invoer verschilt van sessiewaarde
    if ($fullname != $_SESSION['login']['name']
        || $email != $_SESSION['login']['email']
        || $username != $_SESSION['login']['username']
    ) {

        //de invoer mag gevalideerd worden

        $validation = new Validation();

        $notValid["voornaamfamilienaam"] = $validation->isValidFullname($fullname);
        $notValid["emailadres"] = $validation->isValidEmail($email);
        $notValid["gebruikersnaam"] = $validation->isValidUsername($username);

        //verwijdert juist ingevulde elemeneten (NULL) uit array
        $errors = array_filter($notValid);

        //probeer gegevens te updaten als er geen errors in array $errors zitten
        if (count($errors) == 0) {
            //geen fouten, de gegevens mogen weggeschreven worden naar de database
            try {

                $updatePreferences = new User();

                $updatePreferences->setMSEmail($email);
                $updatePreferences->setMSFullname($fullname);
                $updatePreferences->setMSUsername($username);

                if($updatePreferences->canUpdatePreferences()) {
                    //voltooid, sessiewaarden bijwerken

                    $_SESSION['login']['name'] = $fullname;
                    $_SESSION['login']['email'] = $email;
                    $_SESSION['login']['username'] = $username;
                    $feedback = buildFeedbackBox("success", "De instellingen zijn met succes bijgewerkt.");
                }

            } catch (Exception $e) {
                $errorException = $e->getMessage();
                $feedback = buildFeedbackBox("danger", $errorException);
            }
        } else {
            //er zijn fouten. Toon ze
            $feedbacktext = "controleer volgende velden:";
            //li met fouten ophalen
            foreach ($errors as $error) {
                $feedbacktext .= "<li>$error</li>";
            }
            $feedback = buildFeedbackBox("danger", $feedbacktext);
        }

    } else {
        //niet echt bijgewerkt, maar melding tonen voor bezoeker (in achtergrond werd geen query uitgevoerd
        $feedback = buildFeedbackBox("success", "De instellingen zijn met succes bijgewerkt.");
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

<h1>Algemene instellingen</h1>


<!-- Start sectie algemene accountinstellingen -->
<section class="settings profile" id="profiel">

    <!-- start form profielinstellingen -->
    <form action="" method="POST">

        <?php
        //toon feedback
        if (!empty($feedback)) {
            echo $feedback;
        }
        ?>

        <!-- start formuliergroep accountinstellingen -->
        <div class="form-group">

            <!-- Naam  -->
            <label for="inputName" class="col-lg-3 control-label">Naam:</label>
            <div class="col-lg-9 lg-together">
                <input type="text" class="form-control col-lg-9"
                       id="inputName"
                       placeholder="Volledige naam"
                       value="<?php
                       if (!empty($_POST)) {
                           echo htmlspecialchars($fullname);
                       } else {
                           echo htmlspecialchars($_SESSION['login']['name']);
                       }
                       ?>"
                       name="inputName"
                       title="Vul je volledige naam in."
                       required>
            </div>

            <!-- E-mail -->
            <label for="inputEmail" class="col-lg-3 control-label">E-mailadres:</label>
            <div class="col-lg-9 lg-together">
                <input type="email"
                       class="form-control"
                       id="inputEmail"
                       placeholder="E-mailadres"
                       title="Vul je e-mailadres in."
                       name="inputEmail"
                       required
                       value="<?php
                       if (!empty($_POST)) {
                           echo htmlspecialchars($email);
                       } else {
                           echo htmlspecialchars($_SESSION['login']['email']);
                       }
                       ?>">
            </div>

            <!-- Username -->
            <label for="inputUsername" class="col-lg-3 control-label">Gebruikersnaam:</label>
            <div class="col-lg-9 lg-together" id="available-username">
                <input type="text"
                       class="form-control"
                       id="inputUsername"
                       name="inputUsername"
                       title="Kies een gewenste gebruikbersnaam. Enkel letters en cijfers zijn toegestaan."
                       value="<?php
                       if (!empty($_POST)) {
                           echo htmlspecialchars($username);
                       } else {
                           echo htmlspecialchars($_SESSION['login']['username']);
                       }
                       ?>"
                       required>
                <div class="ajax-feedback msg"></div>
            </div>

        </div>
        <!-- einde formuliergroep accountinstellingen -->

        <input type="submit"
               name="editProfilePreferences"
               id="editProfilePreferences"
               value="Profielinstellingen opslaan"
               class="btn btn-primary btn-large">


    </form>
    <!-- einde form profielinstellingen-->

</section>
<!-- Einde sectie algemene accountinstellingen  -->


</div>
<div class="clearfix"></div>
</div>
</div>
</div>


<?php include_once('../../inc/footer.inc.php'); ?>

<!-- ajax script -->
<script src="../../js/ajax/username-available.js"></script>

</body>
</html>