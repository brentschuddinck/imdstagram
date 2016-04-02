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
    public function deleteFileFromServer($p_teWissenFile)
    {
        unlink($p_teWissenFile);
    }


    //profielfoto wijzigen
    public function saveProfilePicture()
    {
        // database connectie
        $conn = Db::getInstance();
        $statement = $conn->prepare("UPDATE user SET profile_picture = :picture WHERE user_id = :userid");
        // bind values to parameters
        $statement->bindValue(":picture", $this->m_sProfilePicture);
        $statement->bindValue(":userid", $this->m_sUserId);
        //execute statement
        if ($statement->execute()) {
            return true;
        } else {
            throw new Exception("Door een technisch probleem kan je profielfoto nu niet bijgewerkt worden. Probeer het later opnieuw. Onze excuses voor dit ongemak.");
        }
    }


    //minify afbeeldingen
    public function minifyImage($p_sSource, $p_sDestination, $p_iQualityPct)
    {
        $info = getimagesize($p_sSource);
        $image = imagecreatefromjpeg($p_sSource);

        if ($info['mime'] == 'image/jpeg') {
            $image = imagecreatefromjpeg($p_sSource);

        } elseif ($info['mime'] == 'image/gif') {
            $image = imagecreatefromgif($p_sSource);

        } elseif ($info['mime'] == 'image/png') {
            $image = imagecreatefrompng($p_sSource);

        } else {
            return false;
            //$feedback = "het opgegeven afbeeldingsformaat is niet geldig. Enkel jpg, png en gif-bestanden worden geaccepteerd.";
            //return $feedback;
        }

        imagejpeg($image, $p_sDestination, $p_iQualityPct);
        return true;
    }


    //controleer bestandsgrootte
    public function isBestandNietTeGroot($p_HuidgeGroteInBytes, $p_sMaxBytes)
    {
        if ($p_HuidgeGroteInBytes <= $p_sMaxBytes) {
            return true;
        } else {
            return false;
        }
    }

    //is extentie geldig
    public function isExtentieGeldig($p_arrGeldigeExtenties, $p_sExtentieBestand)
    {

        $arrExtenties = $p_arrGeldigeExtenties;
        $extentieBestand = $p_sExtentieBestand;

        foreach($arrExtenties as $arrExtentie){
            if($arrExtentie == $extentieBestand){
                return true;
            }
        }

    }

    //is bestandstype geldig. Deze functie dient om te voorkomen dat bijvoorbeeld een script hernoemd wordt naar .jpg om zo toch geupload te kunnen worden
    public function isTypeGeldig($p_arrGeldigeTypes, $p_sUploadType){

        $arrGeldigeTypes = $p_arrGeldigeTypes;
        $fileType = $p_sUploadType;

        foreach($arrGeldigeTypes as $arrGeldigeType){
            if($fileType == $arrGeldigeType){
                return true;
            }
        }
    }


    public function uploadfile($p_sFileTmpName, $p_sFileNewName){

        if(move_uploaded_file($p_sFileTmpName, $p_sFileNewName)){
            return true;
        }else{
            return false;
        }

    }
}

