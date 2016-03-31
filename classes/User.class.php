<?php
include_once('Db.class.php');


class User
{

    //membervariabelen

    private $m_sVoornaamFamilienaam;
    private $m_sEmailadres;
    private $m_sGebruikersnaam;
    private $m_sWachtwoord;
    private $m_sNieuwWachtwoord;
    private $m_iUserAccountState;
    private $m_iUserId;


    //getters en setters

    public function getMSNieuwWachtwoord()
    {
        return $this->m_sNieuwWachtwoord;
    }

    public function setMSNieuwWachtwoord($m_sNieuwWachtwoord)
    {
        $this->m_sNieuwWachtwoord = $m_sNieuwWachtwoord;
    }


    public function getMIUserId()
    {
        return $this->m_iUserId;
    }

    public function setMIUserId($m_iUserId)
    {
        $this->m_iUserId = $m_iUserId;
    }


    public function getMiUserAccountState()
    {
        return $this->m_iUserAccountState;
    }

    public function setMiUserAccountState($m_iUserAccountState)
    {
        $this->m_iUserAccountState = $m_iUserAccountState;
    }

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

    public function Registreer()
    {

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
        if (!$statement->execute()) {
            throw new Exception(" je account is niet geregistreerd. Mogelijk bestaat er al een account met deze gegevens.");
        }

    }


    //kan er ingelogd worden
    public function canLogin()
    {
        if (!empty($this->m_sEmailadres) && !empty($this->m_sWachtwoord)) {

            //database connectie
            $conn = Db::getInstance();

            // gebruiker zoeken die wil inloggen adhv e-mailadres
            $statement = $conn->prepare("SELECT * FROM user WHERE email = :email");
            // bind value to parameter :email
            $statement->bindValue(":email", $this->m_sEmailadres, PDO::PARAM_STR);
            //execute statement
            $statement->execute();

            // als we 1 rij terug krijgen = user bestaat
            if ($statement->rowCount() == 1) {
                // fetch row van resultaat, return array met kolomnamen als index
                $userRow = $statement->fetch(PDO::FETCH_ASSOC);
                $hash = $userRow['password'];

                // check dat het ingegeven wachtwoord van de gebruiker overeenkomt met het wachtwoord in de databank
                if (password_verify($this->m_sWachtwoord, $hash)) {

                    //basis profielgegevens ophalen uit resultaatrij en toevoegen aan de sessie login
                    //meeste van deze gegevens komen op de meeste pagina's terug (foto, etc) zo niet steeds moeten querie halen performance

                    $_SESSION['login']['userid'] = $userRow['user_id']; //sessie_id ophalen
                    $_SESSION['login']['gebruikersnaam'] = $userRow['username']; //username ophalen
                    $_SESSION['login']['profielfoto'] = $userRow['profile_picture']; //link profile_picture
                    $_SESSION['login']['email'] = $userRow['email']; //emailadres ophalen
                    $_SESSION['login']['naam'] = $userRow['full_name']; //volledige naam ophalen
                    $_SESSION['login']['private'] = $userRow['private']; //accountstatus ophalen 0 = openbaar, 1 = private

                    if (empty($_SESSION['login']['profielfoto'])) {
                        $_SESSION['login']['profielfoto'] = "default.png"; //standaard profielfoto indien veld leeg in db
                    }

                    //alles okido
                    return true;
                } else {
                    throw new Exception("het ingevoerde wachtwoord komt niet overeen met het opgegeven e-mailadres.");
                }

            } else if ($statement->rowCount() == 0) {
                // als er geen email in de database overeenkomt(0 rijen), met het ingevulde e-mail adress
                // (het veld e-mail is in onze database UNIQUE dus we kunnen enkel 1 row of geen row terug krijgen)
                throw new Exception("er is geen account geregistreerd met dit e-mailadres.");

            }
        }
    }


    //account private/public zetten
    public function canUpdateAccountState()
    {
        //connectie db
        $conn = Db::getInstance();

        //statement voorbereiden
        $statement = $conn->prepare("UPDATE user SET private = :state WHERE user_id = :userid");

        //values binden
        $statement->bindValue(":state", $this->m_iUserAccountState, PDO::PARAM_INT);
        $statement->bindValue(":userid", $_SESSION['login']['userid'], PDO::PARAM_INT);


        //statement uitvoeren
        if ($statement->execute()) {
            return true;
        }else{
            return false;
        }

    }


    public function canUpdatePreferences(){
        //connectie db
        $conn = Db::getInstance();

        //statement voorbereiden
        $statement = $conn->prepare("UPDATE user SET email = :email, full_name = :fullname, username = :username WHERE user_id = :userid");

        //values binden
        $statement->bindValue(":email", $this->m_sEmailadres, PDO::PARAM_STR);
        $statement->bindValue(":fullname", $this->m_sVoornaamFamilienaam, PDO::PARAM_STR);
        $statement->bindValue(":username", $this->m_sGebruikersnaam, PDO::PARAM_STR);
        $statement->bindValue(":userid", $_SESSION['login']['userid'], PDO::PARAM_INT);


        //statement uitvoeren
        if($statement->execute()){
            return true;
        }else{
            throw new Exception("door een technisch probleem kunnen we de wijzigingen niet opslaan.");
        }
    }




    public function updatePassword(){
        if (!empty($this->m_sWachtwoord)) {

            //database connectie
            $conn = Db::getInstance();

            // gebruiker zoeken die wil inloggen adhv e-mailadres
            $statement = $conn->prepare("SELECT password FROM user WHERE user_id = :userid");

            // bind value to parameter :userid
            $statement->bindValue(":userid", $this->m_iUserId, PDO::PARAM_INT);

            //execute statement
            $statement->execute();

            // als we 1 rij terug krijgen = user bestaat
            if ($statement->rowCount() == 1) {
                // fetch row van resultaat, return array met kolomnamen als index
                $userRow = $statement->fetch(PDO::FETCH_ASSOC);
                $hash = $userRow['password'];


                // check dat het ingegeven wachtwoord van de gebruiker overeenkomt met het wachtwoord in de databank
                if (password_verify($this->m_sWachtwoord, $hash)) {
                    //database connectie (niet nodig omdat: "The connection remains active for the lifetime of that PDO object."
                    //$conn = Db::getInstance();

                    // gebruiker zoeken die wil inloggen adhv e-mailadres
                    $statement = $conn->prepare("UPDATE user SET password = :newpassword WHERE user_id = :userid");

                    // bind value to parameter :userid, :newpassword
                    $statement->bindValue(":newpassword", $this->m_sNieuwWachtwoord, PDO::PARAM_STR);
                    $statement->bindValue(":userid", $this->m_iUserId, PDO::PARAM_INT);

                    //execute statement
                    $statement->execute();
                    return true;
                } else {
                    throw new Exception("het oude opgegeven wachtwoord is niet juist.");
                }

            } else {
                // als er geen email in de database overeenkomt(0 rijen), met het ingevulde e-mail adress
                // (het veld e-mail is in onze database UNIQUE dus we kunnen enkel 1 row of geen row terug krijgen)
                throw new Exception("door een technisch probleem is het niet mogelijk je account bij te werken. Probeer het later opnieuw. Onze excuses voor dit ongemak.");

            }
        }
    }




}

?>