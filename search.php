<?php
include_once('inc/sessiecontrole.inc.php');
include_once('inc/feedbackbox.inc.php');
include_once('classes/Validation.class.php');
include_once('classes/Search.class.php');

if (isset($_GET['search']) && !empty($_GET['search'])) {

    $zoekterm = strtolower($_GET['search']);

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
            $arrResultaat = $search->zoekResultaten();

            if ($arrResultaat) {
                //er zijn resultaten gevonden
                //toon resultaten (gesorteerd op meeste likes)
                $pageTitle = htmlspecialchars($zoekterm);

                $arrUsers = $arrResultaat['user'];
                $arrTags = $arrResultaat['tag'];
                $arrLocations = $arrResultaat['location'];

                $aantalUsers = count($arrUsers);
                $aantalTags = count($arrTags);
                $aantalLocations = count($arrLocations);

                $aantalZoekresultaten = count($arrUsers + $arrTags + $arrLocations); //tel gevonden resultaten (enkel eerste 20 getoond!)
                $aantalZoekresultaten = number_format($aantalZoekresultaten, 0, '.', '.'); //duizendtallen scheiden met punt

                //query toont enkel de eerste 20 resultaten per onderdeel. Indien een van deze de maxima bereikt, toon melding, maar voer toch uit
                $maxRecords = 20;
                if($aantalUsers == $maxRecords || $aantalTags == $maxRecords || $aantalLocations == $maxRecords){
                    $feedback = bouwFeedbackBox("warning", "alleen de eerste " . $maxRecords ." resultaten per onderdeel worden getoond. Mogelijk zijn er meer resultaten beschikbaar. Verfijn je zoekopdracht om gedetailleerde resultaten te bekomen.");
                }

            } else {
                //er zijn geen resultaten gevonden
                //$pageTitle = htmlspecialchars($zoekterm);
                $pageTitle = "Niets gevonden :(";
                $feedback = bouwFeedbackBox("danger", "er zijn geen resultaten gevonden voor " . htmlspecialchars($zoekterm) . ".");
            }

        } catch (Exception $e) {
            $feedbackTekst = $e->getMessage();
            $feedback = bouwFeedbackBox("danger", $feedbackTekst);
        }
    } else {
        $pageTitle = "Niets gevonden :(";
        $feedback = bouwFeedbackBox("danger", "het zoekwoord is ongeldig. Alleen #, _, letters en cijfers zonder spaties zijn toegestaan.");
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
            <?php if (isset($aantalZoekresultaten) && $aantalZoekresultaten > 0) {
                echo "<div class='centreer tekst search nmessages vet'>" . htmlspecialchars($aantalZoekresultaten) . " resultaten</div>";
            } ?>

            <div class="centreer tekst"><?php if (isset($feedback) && !empty($feedback)) {
                    echo $feedback;
                } ?></div>
        </div>
    </div>


    <!-- start lijst met zoekresultaten -->
    <h2>Tags</h2>
    <h2>Plaatsen</h2>

    <?php if(isset($arrUsers) && !empty($arrUsers)){

        echo "<h2>Personen</h2>";
        echo "<div class=\"row img-list\">";

        foreach ($arrUsers as $arrItem) {
            $toonUsersGebruikersnaam = $arrItem['username'];
            $toonUsersFullName = $arrItem['full_name'];
            $toonUsersProfilePicture = $arrItem['profile_picture'];

            $toonUsers = "<article class=\"col-xs-12 col-sm-4 col-md-3\">";
            $toonUsers .= "<a class=\"thumbnail picturelist\" href='/imdstagram/account/profile.php?user=". $toonUsersGebruikersnaam ."'>";
            $toonUsers .= "<div class='vet'>". $toonUsersFullName ."</div>";
            $toonUsers .= "<div>" . $toonUsersGebruikersnaam . "</div>";
            $toonUsers .= "<img src='img/uploads/profile-pictures/". $toonUsersProfilePicture ."' alt='Profielfoto van ". $toonUsersFullName ."'>";
            $toonUsers .= "</a></article>";
            echo $toonUsers;
        }
        echo "</div>";

    } ?>

</div>
<!-- einde photowall -->


<?php include_once('inc/footer.inc.php'); ?>

</body>
</html>