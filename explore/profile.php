<?php
include_once('../inc/sessiecontrole.inc.php');
include_once('../classes/Post.class.php');
include_once('../classes/User.class.php');
include_once('../classes/Comment.class.php');
include_once('../inc/feedbackbox.inc.php');
include_once('../classes/Search.class.php');
include_once('../classes/Validation.class.php');

if (isset($_GET['user']) && !empty($_GET['user'])) {

    $username = htmlspecialchars($_GET['user']);
    $amountOfSearchResults = 0;

//controleer geldige locatie
    $search = new Search();
    $user = new User();
    $validation = new Validation();
    $post = new Post();

    if ($validation->isValidSearchTerm($username)) {


        $username = str_replace('#', '', $username);
        $username = str_replace('%23', '', $username);

        //kijk of deze gebruiker bestaat
        $user->setMSUsername($username);
        if($user->UsernameAvailable()){
            //hergebruik functie. rows = 0 keert true terug. In deze situatie moet dit 1 zijn.
            header('Location: /imdstagram/error/error404.php');
        }
    } else {
        header('Location: /imdstagram/error/404.php');
    }

    $post->setMSUsernamePosts($username);
    $userPosts = $post->getPostsForEachUser();

    $user = new User();
    $user->setMSUsername($username);
    //print_r($user->isAccepted());
    if(!empty($_GET['id'])){
        $user->setMIUserId($_GET['id']);
        $user->followUser();
        header('location: profile.php?user=' . $_GET['user']);

    }

    $requests = $user->getFriendRequests();

    //accepteer friend requests
    if(isset($_GET['acceptId']) && !empty($_GET['acceptId'])){
        $user->setMIAcceptId($_GET['acceptId']);
        $user->acceptFriendRequest();
        header('location: profile.php?user=' . $_GET['user']);
    }

    $followers = $user->getFollowers();
    $followings = $user->getFollowing();

    // feedback voor als een profiel nog geen foto's, volgers of nog niemand volgt
    if(empty($userPosts) && $post->countPostsForEachuser() > 0){
        $feedback = 'Dit account is privé, stuur een volg verzoek om de foto\'s van deze gebruiker te zien.';
    }elseif(empty($userPosts) && $post->countPostsForEachuser() == 0 && $_GET['user'] == $_SESSION['login']['username']){
        $feedback = 'Je hebt nog geen foto\'s geplaatst.';

    }elseif(empty($userPosts) && $post->countPostsForEachuser() == 0 ){
        $feedback = 'Deze gebruiker heeft nog geen foto\'s geplaatst.';
    }

    if(empty($followers) && $user->countFollowers() == 0 && $_GET['user'] != $_SESSION['login']['username']){
        $followerfb = 'Deze gebruiker heeft nog geen volgers.';
    }elseif( empty($followers) && $user->countFollowers() == 0){
        $followerfb = 'Je hebt nog geen volgers.';
    }

    if(empty($followings) && $user->countFollowing() == 0 && $_GET['user'] != $_SESSION['login']['username']){
        $followingfb = 'Deze gebruiker volgt nog niemand.';
    }elseif( empty($followings) && $user->countFollowing() == 0){
        $followingfb = 'Je volgt nog niemand.';
    }

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

    if (isset($_POST['btnLikePicture'])) {
        $post->likePost();
    }

    if (isset($_GET['click']) && !empty($_GET['click'])) {
        $getclick = $_GET['click'];
        $post->setMSPostId($getclick);
        $post->likePost();
    }



    if (isset($_GET['delete']) && !empty($_GET['delete'])) {
        try {
            $deletePostId = $_GET['delete'];
            $post->setMSPostId($deletePostId);
            $postToDelete = $post->getSinglePost();
            if($postToDelete[0]['user_id'] == $_SESSION['login']['userid']){
                if($post->deletePost()){
                    $post->deletePostImage($postToDelete[0]['post_photo']);
                    $feedback = buildFeedbackBox("success", "De post is verwijderd.");
                    header("Location: profile.php?user=". $username);
                }
            }else{
                $feedback = buildFeedbackBox("danger", "je kan enkel posts wissen die je zelf geplaatst hebt.");
            }

        } catch (Exception $e) {
            $feedback = buildFeedbackBox("danger", $e->getMessage());
        }
    }


    if (isset($_GET['flag']) && !empty($_GET['flag'])) {
        try {
            $flagPostId = $_GET['flag'];
            $post->setMSPostId($flagPostId);
            $postToFlag = $post->getSinglePost();
            if($post->checkIfUserAlreadyFlagged()){
                if($post->flagPost()){
                    $post->addUsersWhoFlagged();
                    $feedback = buildFeedbackBox("success", "Bedankt! De post is gerapporteerd.");
                    //header('Location: index.php');
                }
            }
        } catch (Exception $e) {
            $feedback = buildFeedbackBox("danger", $e->getMessage());
        }
    }


    if(!empty($_POST['commentPostId']) && !empty($_POST['commentDescription']))    {
        $comment = new Comment();
        $comment->setMSComment($_POST['commentDescription']);
        $comment->setMIUserId($_SESSION['login']['userid']);
        $comment->setMIPostId($_POST['commentPostId']);
        $comment->postComment();
    }



}
else{
    header('Location: /imdstagram/error/404.php');
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
            <img alt="" src="../img/uploads/profile-pictures/<?php echo htmlspecialchars($user->profilePictureOnProfile()); ?>">
        </div>
        <div class="card-info"> <span class="card-title"><?php echo htmlspecialchars($pageTitle); ?></span>

        </div>
        <?php if(isset($_GET['user']) && $_GET['user'] != $_SESSION['login']['username']): ?>
        <div>
            <form action="" method="post">
                <a  style="margin-top: 20px" href="?user=<?php echo $_GET['user'];?>&id=<?php echo $user->getIdFromProfile() ?>" class="likeBtn btn btn-primary"><i class="<?php if($user->isFollowing() == 0){echo 'fa fa-user-plus';}elseif($user->isFollowing() == 1 && $user->isAccepted() == true){echo 'fa fa-user-times';}elseif($user->isFollowing() == 1 && $user->isAccepted() == false){echo 'fa fa-envelope-o';} ?> fa-lg"></i><?php if($user->isFollowing() == 0){echo ' volg deze gebruiker';}elseif($user->isFollowing() == 1 && $user->isAccepted() == true){echo ' niet meer volgen';}elseif($user->isFollowing() == 1 && $user->isAccepted() == false){echo ' verzoek verstuurd';} ?></a>
            </form>
        </div>

        <?php endif ?>
    </div>
    <div class="btn-pref btn-group btn-group-justified btn-group-lg" role="group" aria-label="...">
        <div class="btn-group" role="group">
            <button type="button" id="stars" class="btn btn-primary" href="#tab1" data-toggle="tab"><span class="fa fa-camera" aria-hidden="true"></span>
                <div class="hidden-xs"><strong><?php echo $post->countPostsForEachuser(); ?></strong> <?php echo $post->countPostsForEachuser() == 1 ? 'foto' : 'foto\'s' ?></div>
            </button>
        </div>
        <div class="btn-group" role="group">
            <button type="button" id="favorites" class="btn btn-default" href="#tab2" data-toggle="tab"><span class="fa fa-users" aria-hidden="true"></span>
                <div class="hidden-xs"><strong><?php echo $user->countFollowers(); ?></strong><?php echo $user->countFollowers() == 1 ? ' volger' : ' volgers' ?></div>
            </button>
        </div>
        <div class="btn-group" role="group">
            <button type="button" id="following" class="btn btn-default" href="#tab3" data-toggle="tab"><span class="fa fa-smile-o fa-lg" aria-hidden="true"></span>
                <div class="hidden-xs"><strong><?php echo $user->countFollowing(); ?></strong> volgend</div>
            </button>
        </div>
    </div>

    <div class="well">
        <div class="tab-content">
            <div class="tab-pane fade in active" id="tab1">
                <p class="fb"><?php echo !empty($feedback) ? $feedback : ''?></p>
                <div class="row img-list">
                    <?php foreach($userPosts as $userPost): ?>
                        <?php $userPost['post_description'] = htmlspecialchars($userPost['post_description']); ?>
                        <div class="col-xs-12 col-sm-4 col-md-4">

                            <a href="/imdstagram/detail.php?profile=<?php echo htmlspecialchars($username) ?>&postid=<?php echo $userPost['post_id'] ?>"
                               data-toggle="lightbox"
                               data-gallery="multiimages"
                               class="thumbnail picturelist">
                                <img src="/imdstagram/img/uploads/post-pictures/<?php echo $userPost['post_photo']; ?>" class="img-responsive <?php echo $userPost['photo_effect']; ?>">
                            </a>
                            </div>
                    <?php endforeach ?>
                </div>
            </div>
            <div class="tab-pane fade in" id="tab2">
                <?php if(isset($_GET['user']) && $_GET['user'] == $_SESSION['login']['username']): ?>
                    <h2>Verzoeken</h2>
                    <p><?php echo empty($requests) ? 'Je hebt geen vriendschapsverzoeken':'' ?></p>
                    <?php foreach($requests as $request): ?>
                <div class="friendRequests">
                <div class="user-block"">
                    <a href="/imdstagram/explore/profile.php?user=<?php echo $request['username'];?>"><img class="img-circle" src="../img/uploads/profile-pictures/<?php echo $request['profile_picture'] ?>" alt="<">
                        <span class="username"><?php echo $request['username']; ?></span></a>
                    <span class="description acceptBtn"><a href="?user=<?php echo $_SESSION['login']['username'];?>&acceptId=<?php echo $request['user_id']; ?>"><li class="fa fa-user-plus"></li> Accepteer</a></span>
                    </div>
                </div>
            <?php endforeach ?>
                <h2>Volgers</h2>
                <?php endif ?>
                <?php foreach($followers as $follower): ?>
                    <div class="user-block"">
                        <a href="/imdstagram/explore/profile.php?user=<?php echo $follower['username'];?>"><img class="img-circle" src="../img/uploads/profile-pictures/<?php echo $follower['profile_picture'] ?>" alt="<">
                        <span class="username"><?php echo $follower['username']; ?></span></a>
                    </div>
                <?php endforeach ?>
                <p class="fb"><?php echo !empty($followerfb) ? $followerfb : ''?></p>
            </div>
            <div class="tab-pane fade in" id="tab3">
                <?php foreach($followings as $following): ?>
                    <div class="user-block profile-block"">
                    <a href="/imdstagram/explore/profile.php?user=<?php echo $following['username'];?>"><img class="img-circle" src="../img/uploads/profile-pictures/<?php echo $following['profile_picture'] ?>" alt="<">
                        <span class="username"><?php echo $following['username']; ?></span></a>

                    </div>
                <?php endforeach ?>
                <p class="fb"><?php echo !empty($followingfb) ? $followingfb : ''?></p>
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
<!-- lightbox required components -->
<script src="/imdstagram/js/lightbox-style.js"></script>
<script src="/imdstagram/js/lightbox-call.js"></script>
</body>
</html>