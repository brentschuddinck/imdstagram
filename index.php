<?php
include_once('inc/sessiecontrole.inc.php');
include_once('classes/Post.class.php');
include_once('inc/feedbackbox.inc.php');

    $post = new Post();
    $showPosts = $post->getAllPosts();
    if(isset($_POST['btnLikePicture'])) {
        $post->likePost();

    }

    if(!empty($_GET['click']) ){
        $getclick = $_GET['click'];
        $post->setMSPostId($getclick);
        $post->likePost();
    }

    if(!empty($_GET['delete'])){
        $deletePostId = $_GET['delete'];
        $post->setMSPostId($deletePostId);
        $post->deletePost();
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
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet">

</head>
<body class="template">

<?php include_once('inc/header.inc.php'); ?>


<!-- start photowall -->

<div class="container bootstrap">
    <div class="col-sm-6 col-sm-offset-3 col-md-8 col-md-offset-2">
        <h2>Tijdlijn</h2>
        <?php foreach($showPosts as $showPost): ?>
            <?php $post->setMSPostId($showPost['post_id']);?>


            <div class="box box-widget">
                <div class="box-header with-border">
                    <div class="user-block">
                        <img class="img-circle" src="img/uploads/profile-pictures/<?php echo !empty($post->userImgFromPost()) ? $post->userImgFromPost() : 'default.png'; ?>" alt="User Image">
                        <span class="username"><a href="/imdstagram/account/profile.php?user=<?php echo $post->usernameFromPost();?>"><?php echo $post->usernameFromPost(); ?></a></span>
                        <span class="description"><?php echo $post->timePosted($showPost['post_date']);?> - <span class="fa fa-map-marker"> locatie</span></span>
                    </div>
                    <div class="box-tools">
                        <div class="<?php echo $showPost['user_id'] == $_SESSION['login']['userid'] ? 'show' :'hidden' ?>">
                        <a type="button" class="btn btn-box-tool" title="post verwijderen" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a>
                        </div>
                    </div>
                    <div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-body">
                                    <p>Ben je zeker dat je de geslecteerde foto wil verwijderen?</p>
                                </div>
                                <div class="modal-footer">
                                    <a href="?delete=<?php echo $showPost['post_id'];?>" class="btn btn-danger btn-ok">verwijder foto</a>
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Annuleren</button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="box-body">
                    <img class="img-responsive pad" src="img/uploads/post-pictures/<?php echo $showPost['post_photo'] ?>" alt="Photo">
                    <p><?php echo htmlspecialchars($showPost['post_description']) ?></p>
                    <a href="?click=<?php echo $showPost['post_id'];?>" data-id="<?php echo $showPost['post_id'] ?>" class="likeBtn btn btn-xs <?php echo $post->isLiked() == true ? 'liked ' : 'btn-default '?>"><i class="fa fa-thumbs-o-up"></i> vind ik leuk</a>
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
                            <input type="text" class="form-control input-sm" placeholder="Schrijf een reactie op deze foto...">
                            <button type="submit" name="submit" class="btn btn-success green comment"><i class="reply"></i>Reageer</button>
                            <div class="clearfix"></div>
                        </div>
                    </form>
                </div>
            </div>
        <?php endforeach ?>
    </div>
</div>
<!-- einde photowall -->


<?php include_once('inc/footer.inc.php'); ?>

    <script src="js/ajax/liking-a-post.js"></script>
</body>
</html>