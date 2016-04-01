<?php
include_once('inc/sessiecontrole.inc.php');

echo $_SESSION['login']['profielfoto'];
echo $_SESSION['login']['userid'];

?><!doctype html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>IMDstagram tijdlijn</title>
    <meta name="description"
          content="IMDstagram is dé creative place to be voor IMD studenten. IMDstagram geeft je inspiratie een boost door creatieve en inspirerende afbeeldingen te delen met andere studenten.">
    <?php include_once('inc/style.inc.php'); ?>
</head>
<body class="template">

<?php include_once('inc/header.inc.php'); ?>


<!-- start photowall -->
<div class="container">
    <div class="row">
        <!-- col-xx-offet-x zorgt voor de centrering middening -->
        <div class="col-sm-6 col-sm-offset-3 col-md-8 col-md-offset-2">
            <h1>Photo wall</h1>
            <article class="thumbnail">
                <img data-src="holder.js/100%x600" alt="...">
                <div class="caption">
                    <h2>Titel artikel</h2>
                    <p>Dit is een voorbeeld van een <a href="#">&#35;awesome</a> boodschap die de plaatser bij de foto geschreven heeft.</p>
                </div>
            </article>
        </div>
    </div>
</div>
<!-- einde photowall -->


<?php include_once('inc/footer.inc.php'); ?>

</body>
</html>