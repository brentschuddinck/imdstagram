<?php

include_once('Db.class.php');

class Search{

    private $m_sSearchTerm;


    public function getMSSearchTerm()
    {
        return $this->m_sSearchTerm;
    }

    public function setMSSearchTerm($m_sSearchTerm)
    {
        $this->m_sSearchTerm = $m_sSearchTerm;
    }



    public function zoekResultaten(){
        //database connectie
        $conn = Db::getInstance();

        // gebruiker zoeken die wil inloggen adhv e-mailadres
        // aparte statements, joins niet altijd mogelijk aangezien geen overeenkomstige key
        $statementSearchInUser = $conn->prepare("SELECT DISTINCT username, full_name, profile_picture FROM user WHERE username LIKE concat(:searchterm, '%') OR full_name LIKE concat('%', :searchterm, '%') limit 20");
        $statementSearchInTag = $conn->prepare("SELECT DISTINCT tag_name FROM tag WHERE tag_name LIKE concat('%', :searchterm, '%') limit 20");
        $statementSearchInLocation = $conn->prepare("SELECT DISTINCT post_location FROM post WHERE post_location LIKE concat(:searchterm, '%') limit 20");


        // bind value
        $statementSearchInUser->bindValue(":searchterm", $this->m_sSearchTerm, PDO::PARAM_STR);
        $statementSearchInTag->bindValue(":searchterm", $this->m_sSearchTerm, PDO::PARAM_STR);
        $statementSearchInLocation->bindValue(":searchterm", $this->m_sSearchTerm, PDO::PARAM_STR);

        //execute statement
        if ($statementSearchInUser->execute() && $statementSearchInLocation->execute() && $statementSearchInTag->execute()) {
            //query went ok
            if ($statementSearchInUser->rowCount() > 0 || $statementSearchInLocation->rowCount() > 0 || $statementSearchInTag->rowCount() > 0) {
                $arrResult['tag'] = $statementSearchInTag->fetchAll(PDO::FETCH_ASSOC);
                $arrResult['location'] = $statementSearchInLocation->fetchAll(PDO::FETCH_ASSOC);
                $arrResult['user'] = $statementSearchInUser->fetchAll(PDO::FETCH_ASSOC);
                return $arrResult;
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