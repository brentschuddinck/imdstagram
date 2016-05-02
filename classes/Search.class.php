<?php
include_once('Db.class.php');

class Search
{

    private $m_sSearchTerm;
    private $m_sTag;
    private $m_sLocation;
    private $m_sUserid;

    /**
     * @return mixed
     */
    public function getMSUserid()
    {
        return $this->m_sUserid;
    }

    /**
     * @param mixed $m_sUserid
     */
    public function setMSUserid($m_sUserid)
    {
        $this->m_sUserid = $m_sUserid;
    }


    /**
     * @return mixed
     */
    public function getMSLocation()
    {
        return $this->m_sLocation;
    }

    /**
     * @param mixed $m_sLocation
     */
    public function setMSLocation($m_sLocation)
    {
        $this->m_sLocation = $m_sLocation;
    }


    /**
     * @return mixed
     */
    public function getMSTag()
    {
        return $this->m_sTag;
    }

    /**
     * @param mixed $m_sTag
     */
    public function setMSTag($m_sTag)
    {
        $this->m_sTag = $m_sTag;
    }


    public function getMSSearchTerm()
    {
        return $this->m_sSearchTerm;
    }

    public function setMSSearchTerm($m_sSearchTerm)
    {
        $this->m_sSearchTerm = $m_sSearchTerm;
    }


    public function searchResults()
    {
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
            } else {
                //er zijn geen zoekresultaten gevonden
                return false;
            }

        } else {
            throw new Exception("door een technisch probleem kunnen er geen zoekopdrachten uitgevoerd worden. Onze excuses voor het ongemak.");
        }
    }


    // get all location posts
    public function getAllTagPosts()
    {
        $conn = Db::getInstance();
        $statement = $conn->prepare("SELECT post_id, photo_effect, post_photo, post_description FROM post p, user u WHERE p.user_id = u.user_id AND inappropriate < 3 AND (post_description LIKE '%' :tagname '%')
                                     AND (u.private = false OR p.user_id = :userId OR p.user_id = (SELECT u.user_id FROM user u, following f WHERE private = true AND u.user_id = f.follows AND f.user_id = :userId AND f.accepted = true)) ORDER BY post_date DESC LIMIT 200");
        $statement->bindValue(':userId', $_SESSION['login']['userid']);
        $statement->bindValue(':tagname', $this->getMSTag());
        if ($statement->execute()) {
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } else {
            throw new Exception("door een technisch probleem kunnen er kunnen momenteel geen posts opgevraagd woren. Onze exuses voor dit ongemak.");
        }
    }


    // get all location posts
    public function getAllLocationPosts()
    {
        $conn = Db::getInstance();
        $statement = $conn->prepare("SELECT * FROM post p, user u WHERE p.user_id = u.user_id AND p.post_location = :location
                                     AND p.inappropriate < 3 AND (u.private = false OR p.user_id = :userId
                                     OR p.user_id = (SELECT u.user_id FROM user u, following f WHERE private = true AND u.user_id = f.follows AND f.user_id = :userId AND f.accepted = true)) ORDER BY post_date LIMIT 200");
        $statement->bindValue(':userId', $_SESSION['login']['userid']);
        $statement->bindValue(':location', $this->m_sLocation);

        if ($statement->execute()) {
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } else {
            throw new Exception("door een technisch probleem kunnen er momenteel geen posts opgevraagd woren. Onze exuses voor dit ongemak.");
        }
    }


    public function splitBigNumberAmountOfResults($p_iAmountOfResults){
        $splittedAmountOfResults = number_format($p_iAmountOfResults, 0, '.', '.'); //duizendtallen scheiden met punt
        return $splittedAmountOfResults;
    }



}