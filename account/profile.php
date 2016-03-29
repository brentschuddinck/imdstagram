<?php
include_once('../inc/sessiecontrole.inc.php');

//welk profiel opvragen?
//als querystring user bestaat en de waarde hiervan verschillende is van de gebruikersnaam van de ingelogde gebruiker (sessie), dan wordt een ander profiel bekeken
if(isset($_GET['user']) && $_GET['user'] != $_SESSION['login']['gebruikersnaam']){
    $pageTitle = "Profiel " . htmlspecialchars($_GET['user']);}
//in het andere geval wanneer profile.php bezocht wordt zonder user in de querystring, stuur bezoeker door (link zo deelbaar voor anderen)
else if(!isset($_GET['user'])){
    $_GET['user'] = $_SESSION['login']['gebruikersnaam'];
    header('location: profile.php?user=' . $_GET['user']);
//in het andere geval wil de ingelogde gebruiker zijn eigen profiel bekijken. Toon gepaste titel.
}else{
    $pageTitle = "Mijn profiel";
}

?><!doctype html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>IMDstagram tijdlijn</title>
    <meta name="description" content="Tijdlijn">
    <?php include_once('../inc/style.inc.php'); ?>
</head>
<body class="template">

<?php include_once('../inc/header.inc.php'); ?>


<!-- start photowall -->
<div class="container">
    <div class="row">
        <div class="col-sm-3 col-md-2"></div>
        <div class="col-sm-6 col-md-8">
            <h1><?php echo htmlspecialchars($pageTitle); ?></h1>
            <article class="thumbnail">
                <img data-src="holder.js/100%x600" alt="...">
                <div class="caption">
                    <h2>Titel artikel</h2>
                    <p>Dit is een voorbeeld van een <a href="#">&#35;awesome</a> boodschap die de plaatser bij de foto geschreven heeft.</p>
                    <p><a role="button" class="btn btn-primary" href="#">Button</a> <a role="button" class="btn btn-default" href="#">Button</a>
                    </p>
                </div>
            </article>
        </div>
    </div>
</div>
<!-- einde photowall -->


<?php include_once('../inc/footer.inc.php'); ?>

</body>
</html>