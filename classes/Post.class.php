<?php

include_once('Db.class.php');

class Post
{

    // member variabelen
    private $m_sDescription;
    private $m_sImageName;
    private $m_sPostId;
    private $m_sUsernamePosts;
    private $m_sLocation;
    private $m_sTag;
    private $m_sEffect;
    private $m_sUserId;


    // setters & getters

    public function getMSUserId()
    {
        return $this->m_sUserId;
    }

    public function setMSUserId($m_sUserId)
    {
        $this->m_sUserId = $m_sUserId;
    }


    public function getMSTag()
    {
        return $this->m_sTag;
    }

    public function setMSTag($m_sTag)
    {
        $this->m_sTag = $m_sTag;
    }


    public function getMSEffect()
    {
        return $this->m_sEffect;
    }

    public function setMSEffect($m_sEffect)
    {
        $this->m_sEffect = $m_sEffect;
    }

    public function getMSLocation()
    {
        return $this->m_sLocation;
    }

    public function setMSLocation($m_sLocation)
    {
        $this->m_sLocation = $m_sLocation;
    }


    public function getMSUsernamePosts()
    {
        return $this->m_sUsernamePosts;
    }


    public function setMSUsernamePosts($m_sUsernamePosts)
    {
        $this->m_sUsernamePosts = $m_sUsernamePosts;
    }


    public function getMSPostId()
    {
        return $this->m_sPostId;
    }

    public function setMSPostId($m_sPostId)
    {
        $this->m_sPostId = $m_sPostId;
    }


    public function getMSDescription()
    {
        return $this->m_sDescription;
    }


    public function setMSDescription($m_sDescription)
    {
        $this->m_sDescription = $m_sDescription;
    }


    public function getMSImageName()
    {
        return $this->m_sImageName;
    }

    public function setMSImageName($m_sImageName)
    {
        $this->m_sImageName = $m_sImageName;
    }


    // functies

    // uploaden van foto met beschrijving
    public function postPhoto()
    {
        // database connectie
        $conn = Db::getInstance();
        $statement = $conn->prepare("INSERT INTO post (post_description, post_photo, post_date, post_location, user_id, photo_effect) VALUES(:description, :uploadPhoto, :postDate, :location, :userId, :effect)");
        // bind values to parameters
        $statement->bindValue(":description", $this->m_sDescription);
        $statement->bindValue(":uploadPhoto", $this->m_sImageName);
        $statement->bindValue(":postDate", date(DATE_ATOM));
        $statement->bindValue(":location", $this->m_sLocation);
        $statement->bindValue(":userId", $_SESSION['login']['userid']);
        $statement->bindValue(":effect", $this->m_sEffect);

        //execute statement
        $statement->execute();


    }

    // posts (van vrienden) die op timeline van gebruiker komen
    public function getAllPosts()
    {
        $conn = Db::getInstance();
        $statement = $conn->prepare("SELECT * FROM post p LEFT JOIN following f ON p.user_id = f.follows WHERE p.user_id = :userId
                                    OR f.user_id = :userId AND accepted = true ORDER BY post_date DESC LIMIT 20");
        $statement->bindValue(':userId', $_SESSION['login']['userid']);
        $statement->execute();
        $result = $statement->fetchAll();
        return $result;
    }

    // get all location posts
    public function getAllLocationPosts()
    {
        $conn = Db::getInstance();
        $statement = $conn->prepare("SELECT post_id, photo_effect, post_photo FROM post p LEFT JOIN following f ON p.user_id = f.follows WHERE (post_location = :location AND (p.user_id = :userId OR f.user_id = :userId)) ORDER BY post_date DESC LIMIT 200");
        $statement->bindValue(':userId', $_SESSION['login']['userid']);
        $statement->bindValue(':location', $this->getMSLocation());
        if ($statement->execute()) {
            $result = $statement->fetchAll();
            return $result;
        } else {
            throw new Exception("er kunnen momenteel geen posts opgevraagd woren. Onze exuses voor dit ongemak.");
        }
    }


    // get username from poster
    public function usernameFromPost()
    {
        $conn = Db::getInstance();
        $statement = $conn->prepare("SELECT username FROM user u, post p WHERE u.user_id = p.user_id AND p.post_id = :currentPost");
        $statement->bindValue(':currentPost', $this->m_sPostId);
        $statement->execute();
        $result = $statement->fetchColumn();
        return $result;


    }

    // get user profiel picture from poster
    public function userImgFromPost()
    {
        $conn = Db::getInstance();
        $statement = $conn->prepare("SELECT profile_picture FROM user u, post p WHERE u.user_id = p.user_id AND p.post_id = :currentPost");
        $statement->bindValue(':currentPost', $this->m_sPostId);
        $statement->execute();
        $result = $statement->fetchColumn();
        return $result;

    }

    // like function
    public function likePost()
    {
        $userid = $_SESSION['login']['userid'];
        $conn = Db::getInstance();
        $statementCheckIfLiked = $conn->prepare("SELECT * FROM likes WHERE post_id = :postId AND user_id = :userId");
        $statementCheckIfLiked->bindValue(':postId', $this->m_sPostId);
        $statementCheckIfLiked->bindValue(':userId', $userid);
        $statementCheckIfLiked->execute();


        // nog geen rijen, user heeft post nog niet geliked
        if ($statementCheckIfLiked->rowCount() == 0) {
            $statememtInsertLike = $conn->prepare("INSERT INTO likes (liked, post_id, user_id) VALUES (:liked, :postId, :userId)");
            $statememtInsertLike->bindValue(':liked', true);
            $statememtInsertLike->bindValue(':postId', $this->m_sPostId);
            $statememtInsertLike->bindValue(':userId', $userid);
            $statememtInsertLike->execute();
            $result = $statememtInsertLike->fetchAll();
            return $result;
            // meer als 1 rij: user heeft de pos al geliked en wil nu disliken
        } else {
            $statementUpdateLike = $conn->prepare("DELETE FROM likes WHERE post_id = :postId AND user_id = :userId");
            $statementUpdateLike->bindValue(':postId', $this->m_sPostId);
            $statementUpdateLike->bindValue(':userId', $userid);
            $statementUpdateLike->execute();
            $result = $statementUpdateLike->fetchAll();
            return $result;
        }

    }

    public function showLikes()
    {
        $conn = Db::getInstance();
        $statement = $conn->prepare("SELECT COUNT(*) FROM likes WHERE liked = true AND post_id = :currentPostId ");
        $statement->bindValue(':currentPostId', $this->m_sPostId);
        $statement->execute();
        $result = $statement->fetchColumn();
        return $result;

    }

    public function isLiked()
    {
        $conn = Db::getInstance();
        $statement = $conn->prepare("SELECT liked FROM likes WHERE post_id = :currentPostId AND user_id = :userId ");
        $statement->bindValue(':currentPostId', $this->m_sPostId);
        $statement->bindValue(':userId', $_SESSION['login']['userid']);
        $statement->execute();
        $result = $statement->fetchColumn();
        return $result;
    }

    // reformat timestamp
    public function timePosted($p_postedTime)
    {

        $postedTime = strtotime($p_postedTime); //parse textual datetime description into UNIX timestamp
        $currentTime = time(); //Returns the current time measured in the number of seconds since the Unix Epoch

        // bereken seconds tussen time atm en posted time
        $timeDifference = $currentTime - $postedTime;

        $seconds = $timeDifference;
        //floor rond naar beneden af
        $minutes = floor($timeDifference / 60);
        $hours = floor($timeDifference / 3600);
        $days = floor($timeDifference / 86400);
        $weeks = floor($timeDifference / 604800);
        $months = floor($timeDifference / 2628000);
        $years = floor($timeDifference / 31536000);


        if ($seconds <= 60) {
            return "zojuist";

        } else if ($minutes <= 59) {
            if ($minutes == 1) {
                return "1 minuut geleden";
            } else {
                return "$minutes minuten geleden";
            }

        } else if ($hours <= 23) {
            if ($hours == 1) {
                return "1 uur geleden";
            } else {
                return "$hours uur geleden";
            }

        } else if ($days <= 6) {
            if ($days == 1) {
                return "gisteren";
            } else {
                return "$days dagen geleden";
            }

        } else if ($weeks <= 3) {
            if ($weeks == 1) {
                return "een week geleden";
            } else {
                return "$weeks weken geleden";
            }

        } else if ($months <= 11) {
            if ($months == 1) {
                return "een maand geleden";
            } else {
                return "$months maanden geleden";
            }

        } else {
            if ($years == 1) {
                return "een jaar geleden";
            } else {
                return "$years jaar geleden";
            }
        }
    }

    // toon de foto's van een bepaalde persoon op profielpagina
    public function getPostsForEachuser()
    {
        $conn = Db::getInstance();
        $statement = $conn->prepare("SELECT * FROM post p WHERE user_id = (SELECT user_id FROM user WHERE username = :username AND private = false)
                                      OR user_id = (SELECT u.user_id FROM user u, following f WHERE u.username = :username AND private = true AND u.user_id = f.follows AND f.user_id = :userid AND f.accepted = true)
                                      OR user_id = (SELECT user_id FROM user WHERE username = :username AND private = true AND user_id = :userid)
                                      ORDER BY post_date DESC");
        $statement->bindValue(':username', $this->m_sUsernamePosts);
        $statement->bindValue(':userid', $_SESSION['login']['userid']);
        $statement->execute();
        $result = $statement->fetchAll();
        return $result;
    }

    // tellen van het aantal posts dat een bepaalde persoon heeft
    public function countPostsForEachuser()
    {
        $conn = Db::getInstance();
        $statement = $conn->prepare("SELECT COUNT(*) FROM post p WHERE user_id = (SELECT user_id FROM user WHERE username = :username )");
        $statement->bindValue(':username', $this->m_sUsernamePosts);
        $statement->execute();
        $result = $statement->fetchColumn();
        return $result;
    }

    // post deleten, enkel als de post van de ingelogde gebruiker is
    public function deletePost()
    {
        $conn = Db::getInstance();
        $statement = $conn->prepare("DELETE FROM post WHERE post_id = :postId AND user_id = :userId");
        $statement->bindValue(':postId', $this->m_sPostId);
        $statement->bindValue(':userId', $_SESSION['login']['userid']);
        $statement->execute();
    }


    //check of locatie bestaat
    public function locationAvailable()
    {
        $conn = Db::getInstance();
        $statement = $conn->prepare("SELECT DISTINCT post_location FROM post WHERE post_location = :location");
        $statement->bindValue(':location', $this->getMSLocation());
        if ($statement->execute()) {
            //query went ok
            if ($statement->rowCount() != 0) {
                return true;
            } else {
                return false;
            }
        } else {
            throw new Exception("Door een technisch probleem kan de geldigheid van de locatie niet gecontroleerd worden. Onze excuses voor dit ongemakt.");
        }
    }


    public function getAllPostsFromUser()
    {
        $conn = Db::getInstance();
        $statement = $conn->prepare("SELECT post_photo FROM post WHERE user_id = :userid");
        $statement->bindValue(':userid', $this->getMSUserId());
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_COLUMN);
        return $result;
    }



    //delete all post picture
    public function deleteProfilePosts($p_sPicture)
    {
        $path = "/imdstagram/img/uploads/post-pictures/";
        if (unlink($_SERVER['DOCUMENT_ROOT'] . "" . $path .$p_sPicture)) {
            return true;
        } else {
            throw new Exception("Er blijven nog files achter. Gelieve contact op te nemen met de beheerder van IMDstagram om je file definitief te wissen.");
        }
    }



    //get single post picture
    public function getSinglePost(){
        $conn = Db::getInstance();
        $statement = $conn->prepare("SELECT post_photo FROM post WHERE post_id = :postid");
        $statement->bindValue(':postid', $this->m_sPostId);
        if($statement->execute()){
            $result = $statement->fetch(PDO::FETCH_COLUMN);
            return $result;
        }else{
            throw new Exception("je bestand is kon niet van de server gewist worden.");
        }

    }


    //delete single post picture
    public function deletePostImage($p_sPicture)
    {
        $path = "/imdstagram/img/uploads/post-pictures/";
        $fullpath = $_SERVER['DOCUMENT_ROOT'] . $path . $p_sPicture;

        if(file_exists($fullpath)){
            if (unlink($fullpath)) {
                return true;
            } else {
                throw new Exception("je bestand is gewist uit de database, maar blijft nog op onze server staan. Gelieve contact op te nemen met de beheerder van IMDstagram om je file definitief te wissen.");
            }
        }else{
            return true;
        }
    }

}