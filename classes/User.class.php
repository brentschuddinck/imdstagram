<?php
include_once('Db.class.php');


class User
{

    //membervariabelen

    private $m_sFullname;
    private $m_sEmail;
    private $m_sUsername;
    private $m_sPassword;
    private $m_sNewPassword;
    private $m_iUserAccountState;
    private $m_iUserId;


    //getters en setters


    public function getMSNewPassword()
    {
        return $this->m_sNewPassword;
    }

    public function setMSNewPassword($m_sNewPassword)
    {
        $this->m_sNewPassword = $m_sNewPassword;
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

    public function getMSFullname()
    {
        return $this->m_sFullname;
    }


    public function setMSFullname($m_sFullname)
    {
        $this->m_sFullname = $m_sFullname;
    }


    public function getMSEmail()
    {
        return $this->m_sEmail;
    }


    public function setMSEmail($m_sEmail)
    {
        $this->m_sEmail = $m_sEmail;
    }


    public function getMSUsername()
    {
        return $this->m_sUsername;
    }


    public function setMSUsername($m_sUsername)
    {
        $this->m_sUsername = $m_sUsername;
    }


    public function getMSPassword()
    {
        return $this->m_sPassword;
    }


    public function setMSWachtwoord($m_sPassword)
    {
        $this->m_sPassword = $m_sPassword;
    }


    //functies

    public function Register()
    {

        //connectie db
        $conn = Db::getInstance();


        //statement voorbereiden
        $statement = $conn->prepare("INSERT INTO user (full_name, username, email, password) VALUES (:fullname, :username, :email, :password)");

        //values binden
        $statement->bindValue(":fullname", $this->m_sFullname, PDO::PARAM_STR);
        $statement->bindValue(":username", $this->m_sUsername, PDO::PARAM_STR);
        $statement->bindValue(":email", $this->m_sEmail, PDO::PARAM_STR);
        $statement->bindValue(":password", $this->m_sPassword, PDO::PARAM_STR);

        //statement uitvoeren
        if (!$statement->execute()) {
            throw new Exception(" je account is niet geregistreerd. Mogelijk bestaat er al een account met deze gegevens.");
        }

    }


    //kan er ingelogd worden
    public function canLogin()
    {
        if (!empty($this->m_sEmail) && !empty($this->m_sPassword)) {

            //database connectie
            $conn = Db::getInstance();

            // gebruiker zoeken die wil inloggen adhv e-mailadres
            $statement = $conn->prepare("SELECT * FROM user WHERE email = :email");
            // bind value to parameter :email
            $statement->bindValue(":email", $this->m_sEmail, PDO::PARAM_STR);
            //execute statement
            $statement->execute();

            // als we 1 rij terug krijgen = user bestaat
            if ($statement->rowCount() == 1) {
                // fetch row van resultaat, return array met kolomnamen als index
                $userRow = $statement->fetch(PDO::FETCH_ASSOC);
                $hash = $userRow['password'];

                // check dat het ingegeven wachtwoord van de gebruiker overeenkomt met het wachtwoord in de databank
                if (password_verify($this->m_sPassword, $hash)) {

                    //basis profielgegevens ophalen uit resultaatrij en toevoegen aan de sessie login
                    //meeste van deze gegevens komen op de meeste pagina's terug (foto, etc) zo niet steeds moeten querie halen performance

                    $_SESSION['login']['userid'] = $userRow['user_id']; //sessie_id ophalen
                    $_SESSION['login']['username'] = $userRow['username']; //username ophalen
                    $_SESSION['login']['profilepicture'] = $userRow['profile_picture']; //link profile_picture
                    $_SESSION['login']['email'] = $userRow['email']; //emailadres ophalen
                    $_SESSION['login']['name'] = $userRow['full_name']; //volledige naam ophalen
                    $_SESSION['login']['private'] = $userRow['private']; //accountstatus ophalen 0 = openbaar, 1 = private


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
        } else {
            return false;
        }

    }


    public function canUpdatePreferences()
    {
        //connectie db
        $conn = Db::getInstance();

        //statement voorbereiden
        $statement = $conn->prepare("UPDATE user SET email = :email, full_name = :fullname, username = :username WHERE user_id = :userid");

        //values binden
        $statement->bindValue(":email", $this->m_sEmail, PDO::PARAM_STR);
        $statement->bindValue(":fullname", $this->m_sFullname, PDO::PARAM_STR);
        $statement->bindValue(":username", $this->m_sUsername, PDO::PARAM_STR);
        $statement->bindValue(":userid", $_SESSION['login']['userid'], PDO::PARAM_INT);


        //statement uitvoeren
        if ($statement->execute()) {
            return true;
        } else {
            throw new Exception("door een technisch probleem kunnen we de wijzigingen niet opslaan.");
        }
    }


    public function updatePassword()
    {

        if (!empty($this->m_sPassword)) {

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
                if (password_verify($this->m_sPassword, $hash)) {
                    //database connectie (niet nodig omdat: "The connection remains active for the lifetime of that PDO object."
                    $conn = Db::getInstance();

                    // gebruiker zoeken die wil inloggen adhv e-mailadres
                    $statement = $conn->prepare("UPDATE user SET password = :newpassword WHERE user_id = :userid");

                    // bind value to parameter :userid, :newpassword
                    $statement->bindValue(":newpassword", $this->m_sNewPassword, PDO::PARAM_STR);
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
        } else {
            throw new Exception("Wachtwoord mag niet leeg zijn");
        }
    }


    public function deleteAccount()
    {
        if (!empty($this->m_sPassword)) {

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
                if (password_verify($this->m_sPassword, $hash)) {
                    $conn = Db::getInstance();

                    // gebruiker zoeken die wil inloggen adhv e-mailadres
                    //CASCADE zorgt ervoor dat we ook de rijen wissen uit andere tabellen waarin de user_id gekoppeld is
                    $statement = $conn->prepare("DELETE FROM user WHERE user_id = :userid");

                    // bind value to parameter :userid, :newpassword
                    $statement->bindValue(":userid", $this->m_iUserId, PDO::PARAM_INT);

                    //execute statement
                    $statement->execute();
                    return true;
                } else {
                    throw new Exception("het opgegeven wachtwoord is niet juist.");
                }

            } else {
                // als er geen email in de database overeenkomt(0 rijen), met het ingevulde e-mail adress
                // (het veld e-mail is in onze database UNIQUE dus we kunnen enkel 1 row of geen row terug krijgen)
                throw new Exception("door een technisch probleem is het niet mogelijk je account bij te werken. Probeer het later opnieuw. Onze excuses voor dit ongemak.");

            }
        } else {
            throw new Exception("wachtwoord mag niet leeg zijn.");
        }
    }


    public function UsernameAvailable()
    {
        //database connectie
        $conn = Db::getInstance();

        // gebruiker zoeken die wil inloggen adhv e-mailadres
        $statement = $conn->prepare("SELECT username FROM user WHERE username = :username");

        // bind value to parameter :email
        $statement->bindValue(":username", $this->m_sUsername, PDO::PARAM_STR);
        //execute statement
        if ($statement->execute()) {
            //query went ok
            if ($statement->rowCount() == 0) {
                return true;
            } else {
                return false;
            }
        } else {
            throw new Exception("Door een technisch probleem kan de geldigheid van de gebruikersnaam niet gecontroleerd worden. De instellingen zijn niet opgeslagen. Onze excuses voor dit ongemakt.");
        }

    }

    public function profilePictureOnProfile()
    {
        $conn = Db::getInstance();
        $statement = $conn->prepare("SELECT profile_picture FROM user WHERE username = :username");
        $statement->bindValue(':username', $this->m_sUsername);
        $statement->execute();
        $result = $statement->fetchColumn();
        return $result;
    }

    public function getIdFromProfile(){
        $conn = Db::getInstance();
        $statement = $conn->prepare("SELECT user_id FROM user WHERE username = :username ");
        $statement->bindValue(':username', $this->m_sUsername);
        $statement->execute();
        $result = $statement->fetchColumn();
        return $result;
    }

    public function isFollowing(){
        $userid = $_SESSION['login']['userid'];
        $conn = Db::getInstance();
        $statement = $conn->prepare("SELECT * FROM following WHERE user_id = :userId AND follows = (SELECT user_id FROM user WHERE username = :username)");
        $statement->bindValue(':userId', $userid);
        $statement->bindValue(':username', $this->m_sUsername);
        $statement->execute();
        $result = $statement->rowCount();
        return $result;
    }

    public function followUser()
    {
        $userid = $_SESSION['login']['userid'];
        $conn = Db::getInstance();
        $statementCheckIfFollows = $conn->prepare("SELECT * FROM following WHERE user_id = :userId AND follows = :follows");
        $statementCheckIfFollows->bindValue(':userId', $userid);
        $statementCheckIfFollows->bindValue(':follows', $this->m_iUserId);
        $statementCheckIfFollows->execute();


        // nog geen rijen, user heeft post nog niet geliked
        if ($statementCheckIfFollows->rowCount() == 0) {
            $statememtInsertFollow = $conn->prepare("INSERT INTO following (user_id, follows, accepted) VALUES (:userId, :follows, true)");
            $statememtInsertFollow->bindValue(':userId', $userid);
            $statememtInsertFollow->bindValue(':follows', $this->m_iUserId);
            $statememtInsertFollow->execute();
            $result = $statememtInsertFollow->fetchAll();
            return $result;
            // 1 rij: user heeft de pos al geliked en wil nu disliken
        } else {
            $statementDeleteFollow = $conn->prepare("DELETE FROM following WHERE user_id = :userId AND follows = :follows");
            $statementDeleteFollow->bindValue(':userId', $userid);
            $statementDeleteFollow->bindValue(':follows', $this->m_iUserId);
            $statementDeleteFollow->execute();
            $result = $statementDeleteFollow->fetchAll();
            return $result;
        }
    }

    public function countFollowers(){
        $conn = Db::getInstance();
        $statement = $conn->prepare("SELECT COUNT(follows) FROM following WHERE follows = (SELECT user_id FROM user WHERE username = :username)");
        $statement->bindValue(':username', $this->m_sUsername);
        $statement->execute();
        $result = $statement->fetchColumn();
        return $result;
    }

    public function getFollowers(){
        $conn = Db::getInstance();
        $statement = $conn->prepare("SELECT u.username, u.profile_picture FROM user u, following f WHERE u.user_id = f.user_id
                                      AND f.follows = (SELECT user_id FROM user WHERE username = :username)");
        $statement->bindValue(':username', $this->m_sUsername);
        $statement->execute();
        $result = $statement->fetchAll();
        return $result;
    }

    public function countFollowing(){
        $conn = Db::getInstance();
        $statement = $conn->prepare("SELECT COUNT(user_id) FROM following WHERE user_id = (SELECT user_id FROM user WHERE username = :username)");
        $statement->bindValue(':username', $this->m_sUsername);
        $statement->execute();
        $result = $statement->fetchColumn();
        return $result;
    }

    public function getFollowing(){
        $conn = Db::getInstance();
        $statement = $conn->prepare("SELECT u.username, u.profile_picture FROM user u, following f WHERE u.user_id = f.follows
                                      AND f.user_id = (SELECT user_id FROM user WHERE username = :username)");
        $statement->bindValue(':username', $this->m_sUsername);
        $statement->execute();
        $result = $statement->fetchAll();
        return $result;
    }

}
?>