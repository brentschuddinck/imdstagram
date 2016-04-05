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

            <!-- toon extra elementen wanneer ingelogd -->
            <?php if (isset($_SESSION['login']) && $_SESSION['login']['loggedin'] == 1): ?>
                <div class="navbar-collapse collapse">

                    <ul class="nav navbar-nav">

                        <li><a href="#"><span class="visible-xs">Foto toevoegen<span
                                        class="fui-photo"></span></span><span class="visible-sm visible-md visible-lg"><span
                                        class="fui-photo" title="foto toevoegen"></span></span></a></li>
                        <li><a href="#"><span class="navbar-unread"></span><span class="visible-xs"
                                                                                 title="meldingen">Meldingen<span
                                        class="fui-chat"></span></span><span class="visible-sm visible-md visible-lg"><span
                                        class="fui-chat" title="meldingen"></span></span></a></li>

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
                            <a href="/imdstagram/account/profile.php" class="dropdown-toggle"
                               data-toggle="dropdown">

                                <img class="profielfoto mini"
                                     src="/imdstagram/img/uploads/profile-pictures/<?php print htmlspecialchars($_SESSION['login']['profielfoto']); ?>"
                                     alt="Profielfoto van <?php print htmlspecialchars($_SESSION['login']['gebruikersnaam']); ?>">
                                    <span class="header profielnaam"><?php echo htmlspecialchars($_SESSION['login']['gebruikersnaam']); ?></span>

                                <b class="caret"></b></a>
                            <ul class="dropdown-menu">
                                <li><a href="/imdstagram/index.php">Photo wall</a></li>
                                <li>
                                    <a href="/imdstagram/account/profile.php?user=<?php print htmlspecialchars($_SESSION['login']['gebruikersnaam']); ?>">Mijn
                                        profiel</a></li>
                                <li><a href="#">Volgers</a></li>
                                <li><a href="#">Volgend</a></li>
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