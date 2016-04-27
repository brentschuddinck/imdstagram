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
    public function deleteFileFromServer($p_fileToRemove)
    {
        unlink($p_fileToRemove);
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


    //controleer bestandsgrootte
    public function isValidSize($p_CurrentSizeInBytes, $p_MaxBytes)
    {

        if ($p_CurrentSizeInBytes <= $p_MaxBytes) {
            return true;
        } else {
            return false;
        }


    }

    //is extentie geldig
    public function isValidExtension($p_arrValidExtensions, $p_sExtensionFile)
    {

        $arrExtensions = $p_arrValidExtensions;
        $extensionFile = $p_sExtensionFile;

        foreach ($arrExtensions as $arrExtension) {
            if ($arrExtension == $extensionFile) {
                return true;
            }
        }

    }

    //is bestandstype geldig. Deze functie dient om te voorkomen dat bijvoorbeeld een script hernoemd wordt naar .jpg om zo toch geupload te kunnen worden
    public function isValidType($p_arrValidTypes, $p_sUploadType)
    {

        $arrValidTypes = $p_arrValidTypes;
        $fileType = $p_sUploadType;

        foreach ($arrValidTypes as $arrValidType) {
            if ($fileType == $arrValidType) {
                return true;
            }
        }
    }


    public function uploadFile($p_sFileTmpName, $p_sFileNewName)
    {

        if (move_uploaded_file($p_sFileTmpName, $p_sFileNewName)) {
            return true;
        } else {
            return false;
        }

    }
}

