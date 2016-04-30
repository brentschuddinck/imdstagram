<?php
include_once('inc/sessiecontrole.inc.php');
include_once('classes/Post.class.php');
include_once('inc/feedbackbox.inc.php');

$post = new Post();
$showPosts = $post->getAllPosts();

if(empty($showPosts)){
    $feedback = buildFeedbackBox("leeg", "vul je tijdlijn door <a href='upload.php'>foto's toe te voegen</a> en vrienden te volgen. Je kan vrienden, locaties en tags zoeken via het zoekveld bovenaan de pagina.");
}

if(isset($_POST['btnLikePicture'])) {
    $post->likePost();
}

if(isset($_GET['click']) && !empty($_GET['click']) ){
    $getclick = $_GET['click'];
    $post->setMSPostId($getclick);
    $post->likePost();
}

if(isset($_GET['delete']) && !empty($_GET['delete'])){
    try{
        $deletePostId = $_GET['delete'];
        $post->setMSPostId($deletePostId);
        $postToDelete = $post->getSinglePost();
        $post->deletePostImage($postToDelete);
        $post->deletePost();
    }catch(Exception $e){
        $feedback = buildFeedbackBox("danger", $e->getMessage());
    }
    header('Location: index.php');
}

?><!doctype html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>IMDstagram tijdlijn</title>
    <meta name="description"
          content="IMDstagram is dé creative place to be voor IMD studenten. IMDstagram geeft je inspiratie een boost door creatieve en inspirerende afbeeldingen te delen met andere studenten.">
    <?php include_once('inc/style.inc.php'); ?>
    <style>
        body {
            background-color: #ecf0f5;
        }
    </style>

</head>
<body class="template">

<?php include_once('inc/header.inc.php'); ?>


<!-- start photowall -->

<div class="container bootstrap">
    <div class="col-sm-12 col-md-8 col-md-offset-2">
        <?php if(isset($feedback) && !empty($feedback)){ echo $feedback; } ?>
        <?php foreach($showPosts as $showPost): ?>
            <?php $post->setMSPostId($showPost['post_id']);?>


            <div class="box box-widget">
                <div class="box-header with-border">
                    <div class="user-block">
                        <a href="/imdstagram/explore/profile.php?user=<?php echo htmlspecialchars($post->usernameFromPost());?>"><img class="img-circle" src="img/uploads/profile-pictures/<?php echo htmlspecialchars($post->userImgFromPost()); ?>" alt="User Image"></a>
                        <span class="username"><a href="/imdstagram/explore/profile.php?user=<?php echo htmlspecialchars($post->usernameFromPost());?>"><?php echo htmlspecialchars($post->usernameFromPost()); ?></a></span>
                        <span class="description"><?php echo $post->timePosted($showPost['post_date']);?> <?php echo !empty($showPost['post_location']) ? '-' : '' ?> <span class="<?php echo !empty($showPost['post_location']) ? 'fa fa-map-marker' : '' ?>"><?php echo " <a href='explore/location.php?location=". htmlspecialchars($showPost['post_location']) ."'>". htmlspecialchars($showPost['post_location']) ."</a>"; ?></span></span>
                    </div>
                    <div class="box-tools">
                        <div class="<?php echo $showPost['user_id'] != $_SESSION['login']['userid'] ? 'show' :'hidden' ?>">
                            <a href="?delete=<?php echo $showPost['post_id'];?>" type="button" class="btn btn-box-tool" title="post verwijderen" data-target="#confirm-delete"><i class="fa fa-trash-o fa-lg"></i></a>
                        </div>

                    </div>

                    <div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-body">
                                    <p>Ben je zeker dat je de geselecteerde foto wil verwijderen?</p>
                                </div>
                                <div class="modal-footer">
                                    <button class="btn btn-danger btn-ok confirmDelete" name="confirmDelete">verwijder foto</button>
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Annuleren</button>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
                <div class="box-body">
                    <img class="img-responsive pad <?php echo htmlspecialchars($showPost['photo_effect']); ?>" src="img/uploads/post-pictures/<?php echo $showPost['post_photo'] ?>" alt="Photo">
                    <p><?php echo htmlspecialchars($showPost['post_description']) ?></p>
                    <a href="?click=<?php echo $showPost['post_id'];?>" data-id="<?php echo $showPost['post_id'] ?>" class="likeBtn btn btn-sm <?php echo $post->isLiked() == true ? 'liked ' : 'btn-default '?>"><i class="fa fa-heart-o fa-lg"></i> vind ik leuk</a>
                    <span class="pull-right text-muted showLikes"><?php echo $post->showLikes();?> <?php echo $post->showLikes() == 1 ? 'like' : 'likes' ?> </span>
                </div>
                <div class="box-footer box-comments">
                    <div class="box-comment">
                        <img class="img-circle img-sm" src="img/uploads/profile-pictures/<?php echo $_SESSION['login']['profilepicture']; ?>" alt="User Image">
                        <div class="comment-text">
          <span class="username">anyone
          <span class="text-muted pull-right">8:03</span>
          </span>comment 1</div>
                    </div>

                    <div class="box-comment">
                        <img class="img-circle img-sm" src="img/uploads/profile-pictures/<?php echo $_SESSION['login']['profilepicture']; ?>" alt="User Image">
                        <div class="comment-text">
          <span class="username">Someone
          <span class="text-muted pull-right">8:03</span>
          </span>comment 2
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <form action="#" method="post">
                        <img class="img-responsive img-circle img-sm" src="img/uploads/profile-pictures/<?php echo $_SESSION['login']['profilepicture']; ?>" alt="Alt Text">
                        <div class="img-push">
                            <input type="text" class="form-control input-sm" placeholder="Schrijf een reactie...">
                            <button type="submit" name="submit" class="btn btn-success green comment"><i class="reply"></i>Reageer</button>
                            <div class="clearfix"></div>
                        </div>
                    </form>
                </div>
            </div>
        <?php endforeach ?>
        <?php if(count($showPosts) >20) : ?>
        <a href="#" class="btn btn-primary btn-more-results btn-md">Toon meer resultaten</a>
        <?php endif; ?>
    </div>
</div>
<!-- einde photowall -->


<?php include_once('inc/footer.inc.php'); ?>

<script src="js/ajax/liking-a-post.js"></script>
</body>
</html>