<?php

include_once('Db.class.php');

class Search{

    private $m_sZoekterm;


    public function getMSZoekterm()
    {
        return $this->m_sZoekterm;
    }

    public function setMSZoekterm($m_sZoekterm)
    {
        $this->m_sZoekterm = $m_sZoekterm;
    }



    public function zoekResultaten(){
        //database connectie
        $conn = Db::getInstance();

        // gebruiker zoeken die wil inloggen adhv e-mailadres
        // aparte statements, joins niet altijd mogelijk aangezien geen overeenkomstige key
        $statementSearchInUser = $conn->prepare("SELECT DISTINCT username, full_name, profile_picture FROM user WHERE username LIKE concat(:zoekterm, '%') OR full_name LIKE concat('%', :zoekterm, '%') limit 20");
        $statementSearchInTag = $conn->prepare("SELECT DISTINCT tag_name FROM tag WHERE tag_name LIKE concat('%', :zoekterm, '%') limit 20");
        $statementSearchInLocation = $conn->prepare("SELECT DISTINCT post_location FROM post WHERE post_location LIKE concat(:zoekterm, '%') limit 20");


        // bind value
        $statementSearchInUser->bindValue(":zoekterm", $this->m_sZoekterm, PDO::PARAM_STR);
        $statementSearchInTag->bindValue(":zoekterm", $this->m_sZoekterm, PDO::PARAM_STR);
        $statementSearchInLocation->bindValue(":zoekterm", $this->m_sZoekterm, PDO::PARAM_STR);

        //execute statement
        if ($statementSearchInUser->execute() && $statementSearchInLocation->execute() && $statementSearchInTag->execute()) {
            //query went ok
            if ($statementSearchInUser->rowCount() > 0 || $statementSearchInLocation->rowCount() > 0 || $statementSearchInTag->rowCount() > 0) {
                $arrResultaat['tag'] = $statementSearchInTag->fetchAll(PDO::FETCH_ASSOC);
                $arrResultaat['location'] = $statementSearchInLocation->fetchAll(PDO::FETCH_ASSOC);
                $arrResultaat['user'] = $statementSearchInUser->fetchAll(PDO::FETCH_ASSOC);
                return $arrResultaat;
            }

            else{
                //er zijn geen zoekresultaten gevonden
                return false;
            }

        }else{
            throw new Exception("door een technisch probleem kunnen er geen zoekopdrachten uitgevoerd worden. Onze excuses voor het ongemak.");
        }
    }


}