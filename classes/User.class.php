<?php
include_once('Db.class.php');
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


    //kan er ingelogd worden
    public function canLogin(){


        if(!empty($this->m_sEmailadres) && !empty($this->m_sWachtwoord)){

            //database connectie
            $conn = Db::getInstance();

            // gebruiker zoeken die wil inloggen adhv e-mailadres
            $statement = $conn->prepare("SELECT * FROM user WHERE email = :email");
            // bind value to parameter :email
            $statement->bindValue(":email", $this->m_sEmailadres, PDO::PARAM_STR);
            //execute statement
            $statement->execute();

            // als we 1 rij terug krijgen = user bestaat
            if($statement->rowCount() == 1){
                // fetch row van resultaat, return array met kolomnamen als index
                $userRow = $statement->fetch(PDO::FETCH_ASSOC);
                $hash = $userRow['password'];

                // check dat het ingegeven wachtwoord van de gebruiker overeenkomt met het wachtwoord in de databank
                if(password_verify($this->m_sWachtwoord, $hash)){

                    //basis profielgegevens ophalen uit resultaatrij en toevoegen aan de sessie login
                    $_SESSION['login']['userid'] = $userRow['user_id']; //sessie_id ophalen
                    $_SESSION['login']['gebruikersnaam'] = $userRow['username']; //username ophalen
                    $_SESSION['login']['profielfoto'] = $userRow['profile_picture']; //link profile_picture
                    if(empty($_SESSION['login']['profielfoto'])){
                        $_SESSION['login']['profielfoto'] = "default.png"; //standaard profielfoto indien veld leeg in db
                    }
                    //$_SESSION['login']['email'] = $userRow['email']; //emailadres ophalen
                    $_SESSION['login']['naam'] = $userRow['full_name']; //volledige naam ophalen
                    return true;
                }else{
                    throw new Exception("Het ingevoerde wachtwoord komt niet overeen met het opgegeven e-mailadres.");
                }

            }else if($statement->rowCount() == 0){
                // als er geen email in de database overeenkomt(0 rijen), met het ingevulde e-mail adress
                // (het veld e-mail is in onze database UNIQUE dus we kunnen enkel 1 row of geen row terug krijgen)
                throw new Exception("Er is geen account geregistreerd met dit e-mail adres. ");

            }
        }
    }

}

?>