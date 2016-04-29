<?php

//Deze klasse dient voor validatie van invoervoervelden bij gebruikers
//Indien html5 validatie faalt of indien javascript uitgeschakeld is, dan is deze validatieklasse eveneens een fall-back

class Validation
{

    public function isValidEmail($p_Email)
    {

        //controleer of e-mailadres is ingevuld
        if (empty($p_Email)) {
            return "E-mailadres is niet ingevuld.";

            // E-mailadres valideren met FILTER_VALIDATE_EMAIL (ingebouwd, geen gedoe met regex)
        } else if (filter_var($p_Email, FILTER_VALIDATE_EMAIL)) {
            //het e-mailformaat is geldig. Kijk nu of het geldige e-mailadres in de lijst met toegestane domeinen voorkomt

            //lijst toegestane domeinen
            $allowedDomains = array('student.thomasmore.be', 'thomasmore.be');

            //Explode e-mailadres en controloleer tweede deel (na de @). Vergelijk met toegestane domeinen.
            $explodedEmail = explode('@', $p_Email);
            $domain = array_pop($explodedEmail);

            //als e-mailadres een geldig formaat heeft, MAAR NIET matcht met toegestane domeinen
            if (!in_array($domain, $allowedDomains)) {
                //e-mailadres formaat is geldig + matcht met toegestane domeinen
                return "E-mailadres is geen geldig Thomas More e-mailadres.";
            }

        } else {
            //e-mailadres formaat is ongeldig
            return "E-mailadres is ongeldig.";
        }
    }


    //valideer voor- en familienaam veld
    public function isValidFullname($p_sFullname)
    {
        //controleer of veld voor- en familienaam niet leeg is
        if (empty($p_sFullname)) {
            return "Voor- en familienaam is niet ingevuld.";

            //controleer of voor- en familienaam geldig aantal tekens heeft
        } else if (strlen($p_sFullname) < 3 || !preg_match("~^(?:[\p{L}\p{Mn}\p{Pd}\'\x{2019}]+\s[\p{L}\p{Mn}\p{Pd}\'\x{2019}]+\s?)+$~u", $p_sFullname)) {
            return "Voor- en familienaam is ongeldig.";
        }
    }


    //valideer veld gebruikersnaam
    public function isValidUsername($p_sUsername)
    {

        //controleer of gebruikersnaam is ingevuld
        if (empty($p_sUsername)) {
            return "Gebruikersnaam is niet ingevuld.";
            //indien gebruikersnaam is ingevuld, controleer of deze niet groter/kleiner is dan toegelaten aantal tekens
        } else if (strlen($p_sUsername) > 20) {
            return "Gebruikersnaam is te lang. Gebruikersnaam mag niet langer dan 20 tekens zijn.";
        } else if (strlen($p_sUsername) < 2) {
            return "Gebruikersnaam is te kort. Gebruikersnaam moet moet minstens 2 tekens lang zijn.";
        } else if (!ctype_alnum($p_sUsername)) {
            //reguliere expressie controleert of gebruikersnaam enkel letters a-z, of cijfers bevatten. Indien niet, toon error.
            return "Gebruikersnaam ongeldig. Gebruikersnaam mag enkel letters of cijfers bevatten.";
        }
    }


    //valideer veld wachtwoord
    public function isValidPassword($p_sPassword)
    {
        if (empty($p_sPassword)) {
            return "Wachtwoord is niet ingevuld.";
        } else if (strlen($p_sPassword) < 6) {
            return "Wachtwoord te kort. Wachtwoord moet minstens 6 tekens lang zijn.";
        }
    }


    public function matchNewpassword($p_sNewPassword, $p_sNewPasswordRepeat)
    {
        if ($p_sNewPassword === $p_sNewPasswordRepeat) {
            return true;
        } else {
            return false;
        }
    }


    //valideer geldigheid zoekwoord
    public function isValidHashtag($p_sSearchTerm)
    {
        //strlen = string length
        if(strlen($p_sSearchTerm) > 1 && preg_match("/[+#a-zA-Z0-9]/", $p_sSearchTerm) && !preg_match("/\s/", $p_sSearchTerm) && !preg_match("/[\'\/~`\!@\$%\^&\*\(\)\_\-\+=\{\}\[\]\|;:\"\<\>,\.\?\\\]/", $p_sSearchTerm)){
            //geldig
            return true;
        }else{
            //niet geldig
            return false;
        }
    }

    public function isValidSearchTerm($p_sSearchTerm)
    {

        if(strlen($p_sSearchTerm) > 1 && preg_match("/[+#a-zA-Z0-9]/", $p_sSearchTerm) && !preg_match("/[\'\/~`\!@\$%\^&\*\(\)\+=\{\}\[\]\|;:\"\<\>,\.\?\\\]/", $p_sSearchTerm)){
            //geldig
            return true;
        }else{
            //niet geldig
            return false;
        }
    }

}

?>