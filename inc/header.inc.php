<!-- start header -->
<header>
    <!-- Static navbar -->
    <nav class="navbar navbar-inverse navbar-fixed-top navbar-lg navbar-embossed" role="navigation">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Menu</span>
                </button>
                <a class="navbar-brand" href="/imdstagram/index.php">IMDstagram</a>
            </div>

            <!-- toon extra elementen wanneer ingelogd -->
            <?php if(isset($sessie) && $sessieLoggedin==1):?>
            <div class="navbar-collapse collapse">
                <ul class="nav navbar-nav">
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="#">Foto uploaden</a></li>
                    <li><a href="#">Meldingen<span class="navbar-unread"></span></a></li>


                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><img class="profielfoto mini" src="/imdstagram/img/uploads/profile-pictures/<?php print htmlspecialchars($sessieProfielfoto); ?>" alt="Profielfoto van <?php print htmlspecialchars($sessieVoornaamFamilienaam); ?>"><?php print htmlspecialchars($sessieGebruikersnaam); ?> <b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li><a href="/imdstagram/index.php">Photo wall</a></li>
                            <li><a href="/imdstagram/account/profile.php?user=<?php print htmlspecialchars($sessieGebruikersnaam); ?>">Mijn profiel</a></li>
                            <li><a href="#">Volgers</a></li>
                            <li><a href="#">Volgend</a></li>
                            <div class="divider"></div>
                            <li><a href="/imdstagram/account/edit/preferences.php">Profiel bewerken</a></li>
                            <li><a href="/imdstagram/logout.php">Uitloggen</a></li>
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