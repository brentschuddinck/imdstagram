<?php
include_once('inc/sessiecontrole.inc.php');
include_once('inc/feedbackbox.inc.php');
include_once('classes/Validation.class.php');
include_once('classes/Search.class.php');

if (isset($_GET['search']) && !empty($_GET['search'])) {

    $zoekterm = $_GET['search'];

    //search bestaat en is niet leeg

    //controleer geldige searchbox invoer
    $validation = new Validation();
    $isGeldigeZoekterm = $validation->isGeldigZoekwoord($zoekterm);
    if ($isGeldigeZoekterm) {
        //zoekwoord is geldig (tekst, cijfers, #, no-white-space)
        //zoeken naar resultaten
        try {
            $search = new Search();
            $search->setMSZoekterm($zoekterm);
            //$isResultaatGevonden = $search->zoekResultaten();
            $search->zoekResultaten();

            if ($isResultaatGevonden) {
                //er zijn resultaten gevonden
                //toon resultaten (gesorteerd op meeste likes)
                $pageTitle = htmlspecialchars($zoekterm);

            } else {
                //er zijn geen resultaten gevonden
                $pageTitle = htmlspecialchars($zoekterm);
                $feedback = bouwFeedbackBox("danger", "er zijn geen resultaten gevonden voor " . htmlspecialchars($zoekterm) . ".");
            }

        } catch (Exception $e) {
            $feedbackTekst = $e->getMessage();
            $feedback = bouwFeedbackBox("danger", $feedbackTekst);
        }
    } else {
        $pageTitle = "Niets gevonden :(";
        $feedback = bouwFeedbackBox("danger", "het zoekwoord is ongeldig. Alleen #, letters en cijfers zonder spaties zijn toegestaan.");
    }

} else {
    //search bestaat niet en/of is leeg
    $pageTitle = "Niets gevonden :(";
    $feedback = bouwFeedbackBox("warning", "je hebt nog geen zoekterm ingegeven. Er zijn geen zoekresultaten gevonden.");
}



?><!doctype html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Zoeken</title>
    <meta name="description">
    <?php include_once('inc/style.inc.php'); ?>
</head>
<body class="template">

<?php include_once('inc/header.inc.php'); ?>


<!-- start photowall -->
<div class="container">
    <div class="row">
        <div class="col-sm-3 col-md-2"></div>
        <div class="col-sm-6 col-md-8">
            <h1 class="centreer tekst"><?php echo htmlspecialchars($pageTitle); ?></h1>
            <div class="centreer tekst"><?php if (isset($feedback) && !empty($feedback)) {
                    echo $feedback;
                } ?></div>
        </div>
    </div>


    <div class="row">
        <article class="col-xs-12 col-sm-6 col-md-4">
            <a class="thumbnail picturelist" data-content="awesome" href="#">
                <img src="img/assets/bg-home/berg.jpg" alt="">
            </a>
        </article>
        <article class="col-xs-12 col-sm-6 col-md-4">
            <a class="thumbnail picturelist" href="#">
                <img src="img/uploads/profile-pictures/default.png" alt="">

            </a>
        </article>
        <article class="col-xs-12 col-sm-6 col-md-4">
            <a class="thumbnail picturelist" href="#">
                <img src="img/uploads/profile-pictures/profile-picture_userid-8.png" alt="">

            </a>
        </article>
        <article class="col-xs-12 col-sm-6 col-md-4">
            <a class="thumbnail picturelist" data-content="awesome" href="#">
                <img src="img/assets/bg-home/berg.jpg" alt="">
            </a>
        </article>
        <article class="col-xs-12 col-sm-6 col-md-4">
            <a class="thumbnail picturelist" href="#">
                <img src="img/uploads/profile-pictures/default.png" alt="">

            </a>
        </article>
        <article class="col-xs-12 col-sm-6 col-md-4">
            <a class="thumbnail picturelist" href="#">
                <img src="img/uploads/profile-pictures/profile-picture_userid-8.png" alt="">

            </a>
        </article>
    </div>

</div>
<!-- einde photowall -->


<?php include_once('inc/footer.inc.php'); ?>

</body>
</html>