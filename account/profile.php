<?php
include_once('../inc/sessiecontrole.inc.php');

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
            <h1><?php if(isset($_GET['user']) && $_GET['user'] != $sessieGebruikersnaam){echo "Profiel " . htmlspecialchars($_GET['user']);}else{echo "Mijn profiel";} ?></h1>
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