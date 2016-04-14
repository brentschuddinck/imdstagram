<?php

include_once('Db.class.php');

class Post{

    // member variabelen
    private $m_sDescription;
    private $m_sImageName;


    // setters & getters
    public function getMSDescription(){
        return $this->m_sDescription;
    }


    public function setMSDescription($m_sDescription){
        $this->m_sDescription = $m_sDescription;
    }


    public function getMSImage(){
        return $this->m_sImageName;
    }

    public function setMSImage($m_sImageName){
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


}