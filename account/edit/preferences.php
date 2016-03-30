<?php
include_once('../../inc/sessiecontrole.inc.php');


if(isset($_POST['wijzigProfielinstellingen'])){
    //er is op de knop wijzigProfielinstellingen geklikt

    //als e-mailadres verandert is:
        //valideer e-mailadres
        //update records db
        //toon feedback
            //success: update eveneens sessievar

    //als gebruikersnaam verandert is:
        //valideer gebruikersnaam
        //controleer geldigheid
        //update records
        //toon feedback
            //success: update eveneens sessievar

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

        <!-- start formuliergroep profielfoto -->
        <div class="form-group">
            <img class="profielfoto groot"
                 src="/imdstagram/img/uploads/profile-pictures/<?php echo htmlspecialchars($_SESSION['login']['profielfoto']); ?>"
                 alt="Profielfoto van <?php echo htmlspecialchars($_SESSION['login']['naam']); ?>">
        </div>
        <!-- einde formuliergroep profielfoto -->

        <!-- start formuliergroep accountinstellingen -->
        <div class="form-group">

            <!-- Naam  -->
            <label for="inputName" class="col-lg-3 control-label">Naam:</label>
            <div class="col-lg-9 lg-together">
                <input type="text" class="form-control col-lg-9" id="inputName"
                       placeholder="Volledige naam"
                       value="<?php echo htmlspecialchars($_SESSION['login']['naam']); ?>"
                       name="inputName"
                       required
                       disabled>
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
                       value="<?php echo htmlspecialchars($_SESSION['login']['email']); ?>">
            </div>

            <!-- Username -->
            <label for="inputUsername" class="col-lg-3 control-label">Gebruikersnaam:</label>
            <div class="col-lg-9 lg-together">
                <input type="text"
                       class="form-control"
                       id="inputUsername"
                       name="inputUsername"
                       title="Kies een gewenste gebruikbersnaam. Enkel letters, cijfers, _ en - zijn toegestaan."
                       value="<?php echo htmlspecialchars($_SESSION['login']['gebruikersnaam']); ?>"
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