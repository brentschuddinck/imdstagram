<!-- start header -->
<header>
    <!-- Static navbar -->
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Menu</span>
                </button>
                <a class="navbar-brand" href="/imdstagram/index.php">IMDSTAGRAM</a>
            </div>

            <!-- Gebruiker is niet ingelogd  -->
            <?php if (!isset($_SESSION['login']) || $_SESSION['login']['loggedin'] != 1): $huidigepagina = basename($_SERVER['PHP_SELF']); ?>
            <div class="navbar-collapse collapse">
                <ul class="nav navbar-nav navbar-right">

                    <!-- is huidige pagina verschillend van login -->
                    <?php if($huidigepagina != "login.php"): ?>
                    <a class="btn btn-nouser" href="/imdstagram/login.php">Inloggen</a>
                    <?php endif; ?>

                    <!-- is huidige pagina verschillende van registreer -->
                    <?php if($huidigepagina != "register.php"): ?>
                        <a class="btn btn-nouser" href="/imdstagram/register.php">Registreer</a>
                    <?php endif; ?>
                </ul>
            </div>
            <?php endif; ?>
            <!-- Einde gebruiker is niet ingelogd -->


            <!-- toon extra elementen wanneer ingelogd -->
            <?php if (isset($_SESSION['login']) && $_SESSION['login']['loggedin'] == 1): ?>
                <div class="navbar-collapse collapse">

                    <ul class="nav navbar-nav">

                        <li><a href="/imdstagram/upload.php"><span class="visible-xs">Foto toevoegen<span
                                        class="fui-photo"></span></span><span class="visible-sm visible-md visible-lg"><span
                                        class="fui-photo" title="foto toevoegen"></span></span></a></li>

                    </ul>


                    <ul class="nav navbar-nav navbar-right">


                        <!-- start search form -->
                        <form class="navbar-form navbar-left" action="/imdstagram/search.php" role="search" method="GET">
                            <div class="form-group">
                                <div class="input-group">

                                    <input
                                        class="form-control"
                                        type="search"
                                        placeholder="Zoeken"
                                        <?php if(isset($_GET['search']) && !empty($_GET['search'])){ echo "value=". htmlspecialchars($_GET['search']) ;} ?>
                                        name="search"
                                        id="search"
                                        required
                                        title="Zoek naar #tags, inhoud en personen.">

                                    <span class="input-group-btn">
                                        <button type="submit" class="btn">
                                            <span class="fui-search"></span>
                                        </button>
                                    </span>
                                </div>
                            </div>
                        </form>

                        <!-- einde search form -->


                        <li class="dropdown">
                            <a href="/imdstagram/explore/profile.php" class="dropdown-toggle"
                               data-toggle="dropdown">

                                <img class="profielfoto mini"
                                     src="/imdstagram/img/uploads/profile-pictures/<?php print htmlspecialchars($_SESSION['login']['profilepicture']); ?>"
                                     alt="Profielfoto van <?php print htmlspecialchars($_SESSION['login']['username']); ?>">
                                    <span class="header profielnaam"><?php echo htmlspecialchars($_SESSION['login']['username']); ?></span>

                                <b class="caret"></b></a>
                            <ul class="dropdown-menu">
                                <li><a href="/imdstagram/index.php">Photo wall</a></li>
                                <li>
                                    <a href="/imdstagram/explore/profile.php?user=<?php print htmlspecialchars($_SESSION['login']['username']); ?>">Mijn
                                        profiel</a></li>
                                <li class="divider"></li>
                                <li><a href="/imdstagram/account/edit/preferences.php"><span class="fui-gear"> </span>Profiel
                                        bewerken</a></li>
                                <li><a href="/imdstagram/logout.php"><span class="fui-power"> </span>Uitloggen</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            <?php endif; ?>
            <!-- einde extra elementen wanneer ingelogd -->
        </div>
    </nav>
</header>
<!-- einde header -->