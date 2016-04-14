<?php
$pagePreference = "preferences.php";
$pagePassword = "password.php";
$pagePrivacy = "privacy.php";
$pageCloseAccount = "close-account.php";
$pageProfilePicture = "profile-picture.php";

$current_page = basename($_SERVER['PHP_SELF']);
?>

<div class="container preferences">
    <div class="container-fluid">
        <div class="row-fluid">
            <!-- start aside left -->
            <aside class="col-xs-12 col-sm-4 col-md-3">
                <div class="well sidebar-nav fixed">
                    <div class="nav-header">Instellingen</div>
                    <ul class="nav nav-list">
                        <li <?php if ($current_page === $pagePreference) {
                            print "class='active'";
                        } ?>><a href="<?php print $pagePreference; ?>">Algemeen</a></li>
                        <li <?php if ($current_page === $pageProfilePicture) {
                            print "class='active'";
                        } ?>><a href="<?php print $pageProfilePicture; ?>">Profielfoto</a></li>
                        <li <?php if ($current_page === $pagePassword) {
                            print "class='active'";
                        } ?>><a href="<?php print $pagePassword; ?>">Wachtwoord</a></li>
                        <li <?php if ($current_page === $pagePrivacy) {
                            print "class='active'";
                        } ?>><a href="<?php print $pagePrivacy; ?>">Privacy</a></li>
                        <li <?php if ($current_page === $pageCloseAccount) {
                            print "class='active'";
                        } ?>><a href="<?php print $pageCloseAccount; ?>">Account sluiten</a></li>
                    </ul>
                </div><!--/.well -->
            </aside>
            <!-- end aside left -->


            <!-- start profiel content blok rechts -->
            <div class="col-xs-12 col-sm-8 col-md-offset-1 col-md-8 block-settings scrollit">