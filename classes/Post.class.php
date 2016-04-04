<?php

include_once('Db.class.php');

class Post{

    // member variabelen
    private $m_sBeschrijving;
    private $m_sAfbeelding;


    // setters & getters
    public function getMSBeschrijving(){
        return $this->m_sBeschrijving;
    }


    public function setMSBeschrijving($m_sBeschrijving){
        $this->m_sBeschrijving = $m_sBeschrijving;
    }


    public function getMSAfbeelding(){
        return $this->m_sAfbeelding;
    }

    public function setMSAfbeelding($m_sAfbeelding){
        $this->m_sAfbeelding = $m_sAfbeelding;
    }


    // functies

    // uploaden van foto met beschrijving
    public function postPhoto(){
        // database connectie
        $conn = Db::getInstance();
        $statement = $conn->prepare("INSERT INTO post (post_description, post_photo, post_date, user_id) VALUES(:description, :uploadPhoto, :postDate, :userId)");
        // bind values to parameters
        $statement->bindValue(":description", $this->m_sBeschrijving);
        $statement->bindValue(":uploadPhoto", $this->m_sAfbeelding);
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