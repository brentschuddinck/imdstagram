<?php
include_once('inc/sessiecontrole.inc.php');
include_once('inc/feedbackbox.inc.php');
include_once('classes/Validation.class.php');
include_once('classes/Search.class.php');


if (isset($_GET['search']) && !empty($_GET['search'])) {

    $searchTerm = $_GET['search'];

    //search bestaat en is niet leeg

    //controleer geldige searchbox invoer
    $validation = new Validation();


    if($searchTerm[0] === '#'){
        $isValidSearchTerm = $validation->isValidHashtag($searchTerm);
    }else{
        $isValidSearchTerm = $validation->isValidSearchTerm($searchTerm);
    }


    if ($isValidSearchTerm) {
        //zoekwoord is geldig (tekst, cijfers, #, no-white-space)
        //zoeken naar resultaten
        try {
            $search = new Search();
            $search->setMSSearchTerm($searchTerm);
            //$isResultaatGevonden = $search->zoekResultaten();
            $arrResult = $search->zoekResultaten();

            if ($arrResult) {
                //er zijn resultaten gevonden
                //toon resultaten (gesorteerd op meeste likes)
                $pageTitle = htmlspecialchars($searchTerm);

                $arrUsers = $arrResult['user'];
                $arrTags = $arrResult['tag'];
                $arrLocations = $arrResult['location'];

                $userCount = count($arrUsers);
                $tagCount = count($arrTags);
                $locationCount = count($arrLocations);
                $totalCount = $userCount + $tagCount + $locationCount;

                $amountOfSearchResults = $totalCount; //tel gevonden resultaten (enkel eerste 20 getoond!)
                $amountOfSearchResults = number_format($amountOfSearchResults, 0, '.', '.'); //duizendtallen scheiden met punt

                //query toont enkel de eerste 20 resultaten per onderdeel. Indien een van deze de maxima bereikt, toon melding, maar voer toch uit
                $maxRecords = 20;
                if ($userCount == $maxRecords || $tagCount == $maxRecords || $locationCount == $maxRecords) {
                    $feedback = buildFeedbackBox("warning", "alleen de eerste " . $maxRecords . " resultaten per onderdeel worden getoond. Mogelijk zijn er meer resultaten beschikbaar. Verfijn je zoekopdracht om gedetailleerde resultaten te bekomen.");
                }

            } else {
                //er zijn geen resultaten gevonden
                //$pageTitle = htmlspecialchars($searchTerm);
                $pageTitle = "Niets gevonden :(";
                $feedback = buildFeedbackBox("danger", "er zijn geen resultaten gevonden voor " . htmlspecialchars($searchTerm) . ".");
            }

        } catch (Exception $e) {
            $feedbacktext = $e->getMessage();
            $feedback = buildFeedbackBox("danger", $feedbacktext);
        }
    } else {
        $pageTitle = "Niets gevonden :(";
        if (strlen($searchTerm) < 2) {
            $feedback = buildFeedbackBox("danger", "het zoekwoord moet minstens 2 tekens lang zijn.");
        } else {
            $feedback = buildFeedbackBox("danger", "het zoekwoord is ongeldig. Alleen #, letters en cijfers zonder spaties zijn toegestaan.");
        }
    }

} else {
    //search bestaat niet en/of is leeg
    $pageTitle = "Niets gevonden :(";
    $feedback = buildFeedbackBox("warning", "je hebt nog geen zoekterm ingegeven. Er zijn geen zoekresultaten gevonden.");
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
<div class="container search">
    <div class="row">
        <div class="col-sm-3 col-md-2"></div>
        <div class="col-sm-6 col-md-8">
            <h1 class="centreer tekst"><?php echo htmlspecialchars($pageTitle); ?></h1>
            <?php if (isset($amountOfSearchResults) && $amountOfSearchResults > 0) {
                echo "<div class='centreer tekst search nmessages vet'>" . htmlspecialchars($amountOfSearchResults) . " resultaten</div>";
            } ?>

            <div class="centreer tekst"><?php if (isset($feedback) && !empty($feedback)) {
                    echo $feedback;
                } ?></div>
        </div>
    </div>


    <!-- start lijst met zoekresultaten -->


    <!-- start resultaten tags -->
    <?php if (isset($arrTags) && !empty($arrTags)) {

        echo "<h2>Tags</h2>";
        echo "<div class=\"row img-list\">";

        foreach ($arrTags as $arrItem) {
            $showTagName = $arrItem['tag_name'];

            $showTags = "<article class=\"col-xs-6 col-sm-4 col-md-3\">";
            $showTags .= "<a class='thumbnail picturelist' href='/imdstagram/explore/index.php?tag=" . $showTagName . "'>";
            $showTags .= "<div class='vet'>" . $showTagName . "</div>";
            //$showLocations .= "<img src='img/uploads/profile-pictures/". $showLocationsProfilePicture ."' alt='Profielfoto van ". $showLocationsFullName ."'>";
            $showTags .= "</a></article>";
            echo $showTags;
        }
        echo "</div>";

    } ?>
    <!-- einde resultaten tags -->



    <!-- start resultaten plaatsen -->
    <?php if (isset($arrLocations) && !empty($arrLocations)) {

        echo "<h2>Locaties</h2>";
        echo "<div class=\"row img-list\">";

        foreach ($arrLocations as $arrItem) {
            $showLocationsName = $arrItem['post_location'];
            //$showLocationPicture = $arrItem['post_photo'];

            $showLocations = "<article class=\"col-xs-12 col-sm-4 col-md-3\">";
            $showLocations .= "<a class='thumbnail picturelist' href='/imdstagram/explore/index.php?location=" . htmlspecialchars($showLocationsName) . "'>";
            $showLocations .= "<div class='vet'>" . htmlspecialchars($showLocationsName) . "</div>";
            //$showLocations .= "<img src='img/uploads/post-pictures/" . $showLocationPicture . "' alt='Locatie " . htmlspecialchars($showLocationsName) . "'>";
            $showLocations .= "</a></article>";
            echo $showLocations;
        }
        echo "</div>";

    } ?>
    <!-- einde resultaten plaatsen -->


    <!-- start resultaten gebruikers -->
    <?php if (isset($arrUsers) && !empty($arrUsers)) {

        echo "<h2>Personen</h2>";
        echo "<div class=\"row img-list\">";

        foreach ($arrUsers as $arrItem) {
            $showUsersUsername = $arrItem['username'];
            $showUsersFullName = $arrItem['full_name'];
            $showUsersProfilePicture = $arrItem['profile_picture'];

            $showUsers = "<article class=\"col-xs-12 col-sm-4 col-md-3\">";
            $showUsers .= "<a class=\"thumbnail picturelist\" href='/imdstagram/explore/profile.php?user=" . htmlspecialchars($showUsersUsername) . "'>";
            $showUsers .= "<div class='vet'>" . htmlspecialchars($showUsersFullName) . "</div>";
            $showUsers .= "<div>" . htmlspecialchars($showUsersUsername) . "</div>";
            $showUsers .= "<img src='img/uploads/profile-pictures/" . $showUsersProfilePicture . "' alt='Profielfoto van " . htmlspecialchars($showUsersFullName) . "'>";
            $showUsers .= "</a></article>";
            echo $showUsers;
        }
        echo "</div>";

    } ?>
    <!-- einde resultaten gebruikers -->


</div>
<!-- einde photowall -->


<?php include_once('inc/footer.inc.php'); ?>

</body>
</html>