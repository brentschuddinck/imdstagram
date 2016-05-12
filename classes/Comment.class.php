<?php

include_once('Db.class.php');

class Comment{

    // membervariabelen
    private $m_sComment;
    private $m_iPostId;
    private $m_iUserId;




    // getters & setters
    public function getMIUserId()
    {
        return $this->m_iUserId;
    }

    public function setMIUserId($m_iUserId)
    {
        $this->m_iUserId = $m_iUserId;
    }

    public function getMIPostId()
    {
        return $this->m_iPostId;
    }

    public function setMIPostId($m_iPostId)
    {
        $this->m_iPostId = $m_iPostId;
    }

    public function getMSComment()
    {
        return $this->m_sComment;
    }


    public function setMSComment($m_sComment)
    {
        $this->m_sComment = $m_sComment;
    }



    //insert comment
    public function postComment(){
        $conn = Db::getInstance();
        $statement = $conn->prepare("INSERT INTO comment (comment_description, comment_date, post_id, user_id) VALUES(:commentDescription, :commentDate, :postId, :userId)");
        $statement->bindValue(':commentDescription', $this->m_sComment);
        $statement->bindValue(':commentDate', date(DATE_ATOM));
        $statement->bindValue(':postId', $this->m_iPostId);
        $statement->bindValue(':userId', $this->m_iUserId);
        $statement->execute();

    }

    public function getAllComments(){
        $conn = Db::getInstance();
        $statement = $conn->prepare("SELECT * FROM comment c, user u WHERE c.user_id = u.user_id AND c.post_id = :postId ORDER BY comment_date");
        $statement->bindValue(':postId', $this->m_iPostId);
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

}