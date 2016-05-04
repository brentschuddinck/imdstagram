<?php
include_once("../classes/Comment.class.php");
include_once('../inc/sessiecontrole.inc.php');



if(!empty($_POST)){
    try{
        $comment = new Comment();
        $currentPostId = $_POST['id'];
        $comment->setMIPostId($_POST['id']);
        $comment->setMIUserId($_SESSION['login']['userid']);
        $comment->setMSComment($_POST['comment']);
        $comment->postComment();
        $username = $_SESSION['login']['username'];
        $profilepicture = $_SESSION['login']['profilepicture'];

        $response['name'] = $username;
        $response['profileImg'] = $profilepicture;

        $response['status'] = "working";
    }catch (Exception $e){
        $response['status'] = "not working";
        $response['message'] = $e->getMessage();
    }
    header('Content-Type: application/json');
    echo json_encode($response);
}