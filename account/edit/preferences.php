<?php
include_once('../../inc/sessiecontrole.inc.php');

//wijzig profielfoto
if (isset($_POST['uploadProfilePicture'])) {

    $userUpdateProfilePicture = new User();
    $userUpdateProfilePicture->setMSProfielfoto("uid1_brentschuddinck_timestamp");

    try {
        if ($userUpdateProfilePicture->updateProfilePicture()) {
            //$_SESSION['login']['loggedin'] = 1;
            //include_once('inc/sessiecontrole.inc.php');
            //header('location: index.php');
        }
    } catch (Exception $e) {
        $errorException = $e->getMessage();
        $errorMessage = "<div class=\"text-danger message\"><p>$errorException</p></div>";
    }
} else if (isset($_POST['gebruikeremail']) && empty($_POST['gebruikeremail'])) {
    $errorMessage = "<div class=\"text-danger message\"><p>Vul je e-mailadres in.</p></div>";
} else if (isset($_POST['wachtwoord']) && empty($_POST['wachtwoord'])) {
    $errorMessage = "<div class=<\"text-danger message\"><p>Vul je wachtwoord in.</p></div>";
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


<div class="container">
    <div class="container-fluid">
        <div class="row-fluid">
            <!-- start aside left -->
            <aside class="col-xs-12 col-sm-4 col-md-3">
                <div class="well sidebar-nav">
                    <ul class="nav nav-list">
                        <div class="nav-header">Je account</div>
                        <li><a href="#profiel">Profiel</a></li>
                        <li><a href="#wachtwoord">Wachtwoord</a></li>
                        <li><a href="#privacy">Privacy</a></li>
                        <li><a href="#sluiten">Account sluiten</a></li>
                    </ul>
                </div><!--/.well -->
            </aside>
            <!-- end aside left -->


            <!-- start profiel content blok rechts -->
            <div class="col-xs-12 col-sm-8 col-md-offset-1 col-md-8 block-settings">

                <!-- start profiel content sectie -->

                <h1>Profiel bewerken</h1>


                <!-- Start sectie algemene accountinstellingen -->
                <section class="settings profile" id="profiel">

                    <!-- start form profielinstellingen -->
                    <form action="" method="POST">
                        <h2>Profielinstellingen</h2>

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
                                       value="<?php echo htmlspecialchars($_SESSION['login']['naam']); ?>" disabled>
                            </div>

                            <!-- E-mail -->
                            <label for="inputEmail" class="col-lg-3 control-label">E-mailadres:</label>
                            <div class="col-lg-9 lg-together">
                                <input type="email" class="form-control" id="inputEmail" placeholder="E-mailadres"
                                       value="<?php echo htmlspecialchars($_SESSION['login']['email']); ?>">
                            </div>

                            <!-- Username -->
                            <label for="inputUsername" class="col-lg-3 control-label">Gebruikersnaam:</label>
                            <div class="col-lg-9 lg-together">
                                <input type="email" class="form-control" id="inputUsername"
                                       placeholder="Gebruikersnaam"
                                       value="<?php echo htmlspecialchars($_SESSION['login']['gebruikersnaam']); ?>">
                            </div>

                        </div>
                        <!-- einde formuliergroep accountinstellingen -->


                        <input type="submit" name="wijzigProfielinstellingen" value="Profielinstellingen opslaan"
                               class="btn btn-primary btn-large">


                    </form>
                    <!-- einde form profielinstellingen-->

                </section>
                <!-- Einde sectie algemene accountinstellingen  -->







                <!-- Start sectie algemene accountinstellingen -->
                <section class="settings password" id="wachtwoord">

                    <!-- start form profielinstellingen -->
                    <form action="" method="POST">
                        <h2>Wachtwoord wijzigen</h2>

                        <!-- start formuliergroep oud wachtwoord -->
                        <!-- Oud wachtwoord  -->
                        <label for="inputOudWachtwoord" class="col-lg-3 control-label">Oud wachtwoord:</label>
                        <div class="col-lg-9 lg-together">
                            <input type="password" class="form-control col-lg-9" id="inputOudWachtwoord"
                                   placeholder="Oud wachtwoord">
                        </div>
                        <!-- einde formuliergroep profielfoto -->

                        <!-- start formuliergroep nieuw wachtwoord -->
                        <div class="form-group">



                            <!-- Nieuw wachtworod -->
                            <label for="inputNieuwWachtwoord" class="col-lg-3 control-label">Nieuw wachtwoord:</label>
                            <div class="col-lg-9 lg-together">
                                <input type="password" class="form-control col-lg-9" id="inputNieuwWachtwoord"
                                       placeholder="Nieuw wachtwoord">
                            </div>

                            <!-- Nieuw wachtwoord herhalen -->
                            <label for="inputHerhaalNieuwWachtwoord" class="col-lg-3 control-label">Herhaal wachtwoord:</label>
                            <div class="col-lg-9 lg-together">
                                <input type="password" class="form-control col-lg-9" id="inputHerhaalNieuwWachtwoord"
                                       placeholder="Herhaal nieuw wachtwoord">
                            </div>

                        </div>
                        <!-- einde formuliergroep accountinstellingen -->


                        <input type="submit" name="wijzigWachtwoord" value="Wachtwoord wijzigen" class="btn btn-primary btn-large">


                    </form>
                    <!-- einde form profielinstellingen-->

                </section>
                <!-- Einde sectie algemene accountinstellingen  -->







                <!-- Start sectie account sluiten -->
                <section class="settings privacy" id="privacy">

                    <!-- start form account sluiten -->
                    <form action="" method="POST">
                        <h2>Privacy</h2>


                        <div class="form-group">
                            <!-- Openbaar profiel  -->
                            <label class="checkbox" for="checkPrivateAccount">
                                <input type="checkbox" data-toggle="checkbox" value="" id="checkPrivateAccount">
                                Maak mijn account priv&eacute;. Alleen geaccepteerde personen kunnen mijn foto's en profiel bekijken.
                            </label>
                        </div>



                        <input type="submit" name="wijzigPrivacy" id="wijzigPrivacy" value="Wijzig privacyvoorkeuren" class="btn btn-primary btn-large">



                    </form>
                    <!-- einde form account sluiten-->

                </section>
                <!-- Einde sectie account sluiten  -->










                <!-- Start sectie account sluiten -->
                <section class="settings close-account" id="sluiten">

                    <!-- start form account sluiten -->
                    <form action="" method="POST">
                        <h2>Account sluiten</h2>

                        <p>IMDstagram niets voor jou? Dan kan je hier je account sluiten. Alle opgeslagen foto's, connecties, commentaren, likes, ... gaan verloren. <span class="vet">Let op: Deze actie kan niet ongedaan gemaakt worden.</span></p>


                        <div class="form-group">
                            <!-- Oud wachtwoord  -->
                            <label for="inputWachtwoordDelete" class="col-lg-3 control-label">Wachtwoord:</label>
                            <div class="col-lg-9 lg-together">
                                <input type="password" class="form-control col-lg-9" id="inputWachtwoordDelete"
                                       placeholder="Wachtwoord">

                            </div>
                        </div>




                        <input type="submit" name="deleteAccount" id="deleteAccount" value="Verwijder mijn account" class="btn btn-danger btn-large">



                    </form>
                    <!-- einde form account sluiten-->

                </section>
                <!-- Einde sectie account sluiten  -->







            </div>
            <!-- einde profiel content sectie -->


        </div>
    </div>
</div>
<!-- einde pagina profiel bewerken -->

<?php include_once('../../inc/footer.inc.php'); ?>
</body>
</html>