<?php
include_once('inc/sessiecontrole.inc.php');
include_once('inc/feedbackbox.inc.php');
include_once('classes/Validation.class.php');
include_once('classes/Search.class.php');

if(isset($_GET['search']) && !empty($_GET['search'])){

    $zoekterm = $_GET['search'];

    //search bestaat en is niet leeg

    //controleer geldige searchbox invoer
    $validation = new Validation();
    $isGeldigeZoekterm = $validation->isGeldigZoekwoord($zoekterm);
    if($isGeldigeZoekterm){
        //zoekwoord is geldig (tekst, cijfers, #, no-white-space)
        //zoeken naar resultaten
            try{
                $search = new Search();
                $isResultaatGevonden = $search->setMSZoekterm($zoekterm);

                if($isResultaatGevonden){
                    //er zijn resultaten gevonden
                    
                }else{
                    //er zijn geen resultaten gevonden
                    $pageTitle = "Niets gevonden voor " . htmlspecialchars($zoekterm);
                    $feedback = bouwFeedbackBox("danger", "er zijn geen resultaten gevonden voor " . htmlspecialchars($zoekterm) . ".");
                }

            }catch(Exception $e){
                $feedbackTekst = $e->getMessage();
                $feedback = bouwFeedbackBox("danger", $feedbackTekst);
            }
    }else {
        $pageTitle = "niets gevonden :(";
        $feedback = bouwFeedbackBox("danger", "het zoekwoord is ongeldig. Alleen #, letters en cijfers zonder spaties zijn toegestaan.");
    }

}else{
    //search bestaat niet en/of is leeg
    $pageTitle = "niets gevonden :(";
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
            <div class="centreer tekst"><?php echo $feedback; ?></div>

            <!--<article class="thumbnail">
                <img data-src="holder.js/100%x600" alt="...">
                <div class="caption">
                    <h2>Titel artikel</h2>
                    <p>Dit is een voorbeeld van een <a href="#">&#35;awesome</a> boodschap die de plaatser bij de foto geschreven heeft.</p>
                    <p><a role="button" class="btn btn-primary" href="#">Button</a> <a role="button" class="btn btn-default" href="#">Button</a>
                    </p>
                </div>
            </article>-->
        </div>
    </div>
</div>
<!-- einde photowall -->


<?php include_once('inc/footer.inc.php'); ?>

</body>
</html>