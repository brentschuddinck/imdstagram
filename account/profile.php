<?php
include_once('../inc/sessiecontrole.inc.php');
include_once('../classes/Post.class.php');
include_once('../classes/User.class.php');


    $post = new Post();
    $username = $_GET['user'];
    $post->setMSUsernamePosts($username);
    $userPosts = $post->getPostsForEachUser();

    $user = new User();
    $user->setMSUsername($username);

//welk profiel opvragen?
//als querystring user bestaat en de waarde hiervan verschillende is van de gebruikersnaam van de ingelogde gebruiker (sessie), dan wordt een ander profiel bekeken
if(isset($_GET['user']) && $_GET['user'] != $_SESSION['login']['username']){
    $pageTitle = "Profiel " . htmlspecialchars($_GET['user']);}
//in het andere geval wanneer profile.php bezocht wordt zonder user in de querystring, stuur bezoeker door (link zo deelbaar voor anderen)
else if(!isset($_GET['user'])){
    $_GET['user'] = $_SESSION['login']['username'];
    header('location: profile.php?user=' . $_GET['user']);
//in het andere geval wil de ingelogde gebruiker zijn eigen profiel bekijken. Toon gepaste titel.
}else{
    $pageTitle = "Mijn profiel";
}

?><!doctype html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>IMDstagram tijdlijn</title>
    <meta name="description" class="tijdlijn">
    <?php include_once('../inc/style.inc.php'); ?>
</head>
<body class="template">

<?php include_once('../inc/header.inc.php'); ?>
<div class="container">
    <div class="col-md-10 col-md-offset-1">
    <div class="card hovercard">
        <div class="card-background">
        </div>
        <div class="useravatar">
            <img alt="" src="../img/uploads/profile-pictures/<?php echo !empty($user->profilePictureOnProfile()) ? $user->profilePictureOnProfile() : 'default.png' ?>">
        </div>
        <div class="card-info"> <span class="card-title"><?php echo htmlspecialchars($pageTitle); ?></span>

        </div>
    </div>
    <div class="btn-pref btn-group btn-group-justified btn-group-lg" role="group" aria-label="...">
        <div class="btn-group" role="group">
            <button type="button" id="stars" class="btn btn-primary" href="#tab1" data-toggle="tab"><span class="fa fa-camera" aria-hidden="true"></span>
                <div class="hidden-xs"><strong><?php echo $post->countPostsForEachuser(); ?></strong> <?php echo $post->countPostsForEachuser() == 1 ? 'foto' : 'foto\'s' ?></div>
            </button>
        </div>
        <div class="btn-group" role="group">
            <button type="button" id="favorites" class="btn btn-default" href="#tab2" data-toggle="tab"><span class="fa fa-users" aria-hidden="true"></span>
                <div class="hidden-xs"><strong>35</strong> volgers</div>
            </button>
        </div>
        <div class="btn-group" role="group">
            <button type="button" id="following" class="btn btn-default" href="#tab3" data-toggle="tab"><span class="fa fa-user" aria-hidden="true"></span>
                <div class="hidden-xs"><strong>20</strong> volgend</div>
            </button>
        </div>
    </div>

    <div class="well">
        <div class="tab-content">
            <div class="tab-pane fade in active" id="tab1">
                <div class="row img-list">
                    <?php foreach($userPosts as $userPost): ?>
                        <div class="col-xs-12 col-sm-4 col-md-4">
                            <a class="thumbnail picturelist">
                                <img src="../img/uploads/post-pictures/<?php echo $userPost['post_photo']; ?>" alt="">
                            </a>
                        </div>
                    <?php endforeach ?>
                </div>
            </div>
            <div class="tab-pane fade in" id="tab2">
                <h3>volgers</h3>
            </div>
            <div class="tab-pane fade in" id="tab3">
                <h3>volgend</h3>
            </div>
        </div>
    </div>

    </div>
    </div>
</div>

<?php include_once('../inc/footer.inc.php'); ?>
<script>
    $(document).ready(function() {
        $(".btn-pref .btn").click(function () {
            $(".btn-pref .btn").removeClass("btn-primary").addClass("btn-default");
            $(this).removeClass("btn-default").addClass("btn-primary");
        });
    });
</script>
</body>
</html>