<?php
include_once('../inc/sessiecontrole.inc.php');
include_once('../classes/Post.class.php');

    $post = new Post();
    $username = $_GET['user'];
    $post->setMSUsernamePosts($username);
    $userPosts = $post->getPostsForEachUser();

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



<!-- start photowall -->
<div class="container">
    <div class="col-sm-6 col-sm-offset-3 col-md-8 col-md-offset-2">

    <h1><?php echo htmlspecialchars($pageTitle); ?></h1>

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
    </div>
<!-- einde photowall -->


<?php include_once('../inc/footer.inc.php'); ?>

</body>
</html>