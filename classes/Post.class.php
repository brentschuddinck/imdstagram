<?php

include_once('Db.class.php');

class Post{

    // member variabelen
    private $m_sDescription;
    private $m_sImageName;
    private $m_sPostId;

    // setters & getters

    public function getMSPostId()
    {
        return $this->m_sPostId;
    }

    public function setMSPostId($m_sPostId)
    {
        $this->m_sPostId = $m_sPostId;
    }


    public function getMSDescription(){
        return $this->m_sDescription;
    }


    public function setMSDescription($m_sDescription){
        $this->m_sDescription = $m_sDescription;
    }


    public function getMSImageName(){
        return $this->m_sImageName;
    }

    public function setMSImageName($m_sImageName){
        $this->m_sImageName = $m_sImageName;
    }


    // functies

    // uploaden van foto met beschrijving
    public function postPhoto(){
        // database connectie
        $conn = Db::getInstance();
        $statement = $conn->prepare("INSERT INTO post (post_description, post_photo, post_date, user_id) VALUES(:description, :uploadPhoto, :postDate, :userId)");
        // bind values to parameters
        $statement->bindValue(":description", $this->m_sDescription);
        $statement->bindValue(":uploadPhoto", $this->m_sImageName);
        $statement->bindValue(":postDate", date(DATE_ATOM));
        $statement->bindValue(":userId", $_SESSION['login']['userid']);
        //execute statement
        $statement->execute();


    }

    // posts (van vrienden) die op timeline van gebruiker komen
    public function getAllPosts(){
        $conn = Db::getInstance();
        $statement = $conn->prepare("SELECT * FROM post ORDER BY post_date DESC");
        $statement->execute();
        $result = $statement->fetchAll();
        return $result;
    }
    // get username from poster
    public function usernameFromPost(){
        $conn = Db::getInstance();
        $statement = $conn->prepare("SELECT username FROM user u, post p WHERE u.user_id = p.user_id AND p.post_id = :currentPost");
        $statement->bindValue(':currentPost', $this->m_sPostId);
        $statement->execute();
        $result = $statement->fetchColumn();
        return $result;


    }
    // get user profiel picture from poster
    public function userImgFromPost(){
        $conn = Db::getInstance();
        $statement = $conn->prepare("SELECT profile_picture FROM user u, post p WHERE u.user_id = p.user_id AND p.post_id = :currentPost");
        $statement->bindValue(':currentPost', $this->m_sPostId);
        $statement->execute();
        $result = $statement->fetchColumn();
        return $result;

    }


}