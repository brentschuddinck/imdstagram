<?php
include_once('../../inc/sessiecontrole.inc.php');








    if(isset($_POST['deleteAccount'])){
        //er is op de knop deleteAccount geklikt




        //verifieer of passwoord matcht met database
        //delete records
        //update sessie variabele
        //toon feedback
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

        <p>IMDstagram niets voor jou? Dan kan je hier je account sluiten. Alle opgeslagen foto's,
            connecties, commentaren, likes, ... gaan verloren. <span class="vet">Let op: Deze actie kan niet ongedaan gemaakt worden.</span>
        </p>


        <div class="form-group">
            <!-- Oud wachtwoord  -->
            <label for="inputWachtwoordDelete" class="col-lg-3 control-label">Wachtwoord:</label>
            <div class="col-md-9 lg-together">
                <input
                    type="password"
                    class="form-control col-lg-9"
                    id="inputWachtwoordDelete"
                    title="Vul je wachtwoord in."
                    name="inputWachtwoordDelete"
                    placeholder="Wachtwoord"
                    required
                    autofocus>
            </div>
        </div>


        <input type="submit"
               name="deleteAccount"
               id="deleteAccount"
               value="Verwijder mijn account"
               class="btn btn-danger btn-large">


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