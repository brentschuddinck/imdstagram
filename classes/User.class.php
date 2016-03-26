<?php

class User{

    //membervariabelen

    private $m_sVoornaamFamilienaam;
    private $m_sEmailadres;
    private $m_sGebruikersnaam;
    private $m_sWachtwoord;


    //getters en setters

    public function getMSVoornaamFamilienaam()
    {
        return $this->m_sVoornaamFamilienaam;
    }


    public function setMSVoornaamFamilienaam($m_sVoornaamFamilienaam)
    {
        $this->m_sVoornaamFamilienaam = $m_sVoornaamFamilienaam;
    }


    public function getMSEmailadres()
    {
        return $this->m_sEmailadres;
    }


    public function setMSEmailadres($m_sEmailadres)
    {
        $this->m_sEmailadres = $m_sEmailadres;
    }


    public function getMSGebruikersnaam()
    {
        return $this->m_sGebruikersnaam;
    }


    public function setMSGebruikersnaam($m_sGebruikersnaam)
    {
        $this->m_sGebruikersnaam = $m_sGebruikersnaam;
    }


    public function getMSWachtwoord()
    {
        return $this->m_sWachtwoord;
    }


    public function setMSWachtwoord($m_sWachtwoord)
    {
        $this->m_sWachtwoord = $m_sWachtwoord;
    }






    //functies

    public function Registreer() {

        //connectie db
        $conn = Db::getInstance();


        //statement voorbereiden
        $statement = $conn->prepare("INSERT INTO user (full_name, username, email, password) VALUES (:fullname, :username, :email, :password)");

        //values binden
        $statement->bindValue(":fullname", $this->m_sVoornaamFamilienaam, PDO::PARAM_STR);
        $statement->bindValue(":username", $this->m_sGebruikersnaam, PDO::PARAM_STR);
        $statement->bindValue(":email", $this->m_sEmailadres, PDO::PARAM_STR);
        $statement->bindValue(":password", $this->m_sWachtwoord, PDO::PARAM_STR);

        //statement uitvoeren
        if($statement->execute()){
            //query went OK
        }else{
            throw new Exception("Ow... je account kan niet worden aangemaakt. Probeer het later opnieuw.");
        }

    }

}

?>