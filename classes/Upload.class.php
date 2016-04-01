<?php
include_once('Db.class.php');

class Upload
{

    //membervariabelen
    private $m_sProfilePicture;
    private $m_sUserId;


    //getters en setters
    public function getMSProfilePicture()
    {
        return $this->m_sProfilePicture;
    }

    public function setMSProfilePicture($m_sProfilePicture)
    {
        $this->m_sProfilePicture = $m_sProfilePicture;
    }


    public function getMSUserId()
    {
        return $this->m_sUserId;
    }

    public function setMSUserId($m_sUserId)
    {
        $this->m_sUserId = $m_sUserId;
    }


    //functies

    //oude foto deleten
    public function deleteFileFromServer($p_sPath, $p_sOldFileName, $p_sNewFileName)
    {
        //automatisch worden bij saven foto's overschreven (indien zelfde extentie).
        //als de nieuwe fotonaam verschilt van de oude fotonaam (er zijn dus 2 foto's opgeslagen), dan mag de oude gewist worden
        if ($p_sOldFileName != $p_sNewFileName) {
            $imageToRemove = $p_sPath . $p_sOldFileName;
            unlink($imageToRemove);
        }
    }


    //profielfoto wijzigen
    public
    function saveProfilePicture()
    {
        // database connectie
        $conn = Db::getInstance();
        $statement = $conn->prepare("UPDATE user SET profile_picture = :picture WHERE user_id = :userid");
        // bind values to parameters
        $statement->bindValue(":picture", $this->m_sProfilePicture);
        $statement->bindValue(":userid", $this->m_sUserId);
        //execute statement
        if (!$statement->execute()) {
            throw new Exception("Door een technisch probleem kan je profielfoto nu niet bijgewerkt worden. Probeer het later opnieuw. Onze excuses voor dit ongemak.");

        }
    }


    //minify afbeeldingen
    public function minifyImage($p_sSource, $p_sDestination, $p_iQualityPct)
    {
        $info = getimagesize($p_sSource);
        $image = imagecreatefromjpeg($p_sSource);

        if ($info['mime'] == 'image/jpeg'){
            $image = imagecreatefromjpeg($p_sSource);

        }elseif ($info['mime'] == 'image/gif'){
            $image = imagecreatefromgif($p_sSource);

        }elseif ($info['mime'] == 'image/png'){
            $image = imagecreatefrompng($p_sSource);

        }else{
            return false;
        }

        imagejpeg($image, $p_sDestination, $p_iQualityPct);

        return true;
    }


    //controleer bestandsgrootte
    public function isBestandNietTeGroot($p_HuiidgeGroteInBytes, $p_sMaxBytes){
        if($p_HuiidgeGroteInBytes <= $p_sMaxBytes){
            return true;
        }else{
            return false;
        }
    }
}

