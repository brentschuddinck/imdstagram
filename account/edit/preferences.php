<?php
include_once('../../inc/sessiecontrole.inc.php');
include_once('../../inc/feedbackbox.inc.php');
include_once('../../classes/Validation.class.php');
include_once('../../classes/User.class.php');


if (isset($_POST['wijzigProfielinstellingen'])) {

    //er is op de knop wijzigProfielinstellingen geklikt


    //invoervariabelen
    $voornaamfamilienaam = $_POST['inputName'];
    $emailadres = $_POST['inputEmail'];
    $gebruikersnaam = strtolower($_POST['inputUsername']);


    //onnodige query voorkomen als er niets gewijzigd werd. controleer of huidige invoer verschilt van sessiewaarde
    if ($voornaamfamilienaam != $_SESSION['login']['naam']
        || $emailadres != $_SESSION['login']['email']
        || $gebruikersnaam != $_SESSION['login']['gebruikersnaam']
    ) {

        //de invoer mag gevalideerd worden

        $validation = new Validation();

        $nietgeldig["voornaamfamilienaam"] = $validation->isGeldigVoornaamFamilienaam($voornaamfamilienaam);
        $nietgeldig["emailadres"] = $validation->isGeldigEmailadres($emailadres);
        $nietgeldig["gebruikersnaam"] = $validation->isGeldigGebruikersnaam($gebruikersnaam);

        //verwijdert juist ingevulde elemeneten (NULL) uit array
        $errors = array_filter($nietgeldig);

        //probeer gegevens te updaten als er geen errors in array $errors zitten
        if (count($errors) == 0) {
            //geen fouten, de gegevens mogen weggeschreven worden naar de database
            try {

                $updatePreferences = new User();

                $updatePreferences->setMSEmailadres($emailadres);
                $updatePreferences->setMSVoornaamFamilienaam($voornaamfamilienaam);
                $updatePreferences->setMSGebruikersnaam($gebruikersnaam);

                if($updatePreferences->canUpdatePreferences()) {
                    //voltooid, sessiewaarden bijwerken

                    $_SESSION['login']['naam'] = $voornaamfamilienaam;
                    $_SESSION['login']['email'] = $emailadres;
                    $_SESSION['login']['gebruikersnaam'] = $gebruikersnaam;
                    $feedback = bouwFeedbackBox("success", "De instellingen zijn met succes bijgewerkt.");
                    //header('location: preferences.php');
                    //$feedback = bouwFeedbackBox("success", "De instellingen zijn met succes bijgewerkt.");
                }

            } catch (Exception $e) {
                $errorException = $e->getMessage();
                $feedback = bouwFeedbackBox("danger", $errorException);
            }
        } else {
            //er zijn fouten. Toon ze
            $feedbacktekst = "controleer volgende velden:";
            //li met fouten ophalen
            foreach ($errors as $error) {
                $feedbacktekst .= "<li>$error</li>";
            }
            $feedback = bouwFeedbackBox("danger", $feedbacktekst);
        }

    } else {
        //niet echt bijgewerkt, maar melding tonen voor bezoeker (in achtergrond werd geen query uitgevoerd
        $feedback = bouwFeedbackBox("success", "De instellingen zijn met succes bijgewerkt.");
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
                           echo htmlspecialchars($voornaamfamilienaam);
                       } else {
                           echo htmlspecialchars($_SESSION['login']['naam']);
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
                           echo htmlspecialchars($emailadres);
                       } else {
                           echo htmlspecialchars($_SESSION['login']['email']);
                       }
                       ?>">
            </div>

            <!-- Username -->
            <label for="inputUsername" class="col-lg-3 control-label">Gebruikersnaam:</label>
            <div class="col-lg-9 lg-together">
                <input type="text"
                       class="form-control"
                       id="inputUsername"
                       name="inputUsername"
                       title="Kies een gewenste gebruikbersnaam. Enkel letters, cijfers, _ en - zijn toegestaan."
                       value="<?php
                       if (!empty($_POST)) {
                           echo htmlspecialchars($gebruikersnaam);
                       } else {
                           echo htmlspecialchars($_SESSION['login']['gebruikersnaam']);
                       }
                       ?>"
                       required>
            </div>

        </div>
        <!-- einde formuliergroep accountinstellingen -->

        <input type="submit"
               name="wijzigProfielinstellingen"
               id="wijzigProfielinstellingen"
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
</body>
</html>