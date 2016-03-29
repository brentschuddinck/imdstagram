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
                        <li class="nav-header">Je account</li>
                        <li><a href="#profiel">Profielinstellingen</a></li>
                        <li><a href="#wachtwoord">Wachtwoord wijzigen</a></li>
                        <li><a href="#sluiten">Account sluiten</a></li>
                    </ul>
                </div><!--/.well -->
            </aside>
            <!-- end aside left -->

            <div class="col-xs-12 col-sm-8 col-md-offset-1 col-md-8">


                <!-- start profiel content sectie -->
                <div class="hero-unit">
                    <h1>Profiel bewerken</h1>

                    <!-- Start sectie algemene accountinstellingen -->
                    <section class="profile-section" id="profiel">

                        <!-- start form profielinstellingen -->
                        <form action="" method="POST">
                            <h2>Profielinstellingen</h2>
                            <!-- start kolom profielfoto -->
                            <div class="profile-section indent col-xs-12 col-sm-4 col-md-3">

                                <div class="form-group">

                                    <div class="box">
                                        <img class="profielfoto groot"
                                             src="/imdstagram/img/uploads/profile-pictures/<?php echo htmlspecialchars($_SESSION['login']['profielfoto']); ?>"
                                             alt="Profielfoto van <?php echo htmlspecialchars($_SESSION['login']['naam']); ?>">
                                    </div>
                                </div>
                            </div>

                            <div class="profile-section indent col-xs-12 col-sm-7 col-sm-offset-1 col-md-8 col-md-offset-1">
                                <div class="form-group">
                                    <div class="name-label"><?php echo htmlspecialchars($_SESSION['login']['naam']);?></div>



                                    <div class="form-group">
                                        <label for="inputEmail1" class="col-lg-2 control-label">Email</label>
                                        <div class="col-lg-10">
                                            <input type="email" class="form-control" id="inputEmail1" placeholder="Email">
                                        </div>
                                        <label for="inputEmail1" class="col-lg-2 control-label">Email</label>
                                        <div class="col-lg-10">
                                            <input type="email" class="form-control" id="inputEmail1" placeholder="Email">
                                        </div>
                                        <label for="inputEmail1" class="col-lg-2 control-label">Email</label>
                                        <div class="col-lg-10">
                                            <input type="email" class="form-control" id="inputEmail1" placeholder="Email">
                                        </div>
                                        <label for="inputEmail1" class="col-lg-2 control-label">Email</label>
                                        <div class="col-lg-10">
                                            <input type="email" class="form-control" id="inputEmail1" placeholder="Email">
                                        </div>
                                    </div>



                                </div>

                            </div>

                            <div class="col-xs-12">
                                <input type="submit" name="bewaarProfielinstellingen" value="Profielinstellingen opslaan" class="btn btn-primary btn-large">
                            </div>

                        </form>
                        <!-- einde form profielinstellingen-->

                    </section>
                    <!-- Einde sectie algemene accountinstellingen  -->

                </div>
                <!-- einde profiel content sectie -->


            </div><!--/span-->
        </div><!--/row-->
    </div>
</div>


<!-- einde pagina profiel bewerken -->


<?php include_once('../../inc/footer.inc.php'); ?>
</body>
</html>