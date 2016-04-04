<?php

//Deze klasse dient voor validatie van invoervoervelden bij gebruikers
//Indien html5 validatie faalt of indien javascript uitgeschakeld is, dan is deze validatieklasse eveneens een fall-back

class Validation
{

    public function isGeldigEmailadres($p_sEmailadres)
    {

        //controleer of e-mailadres is ingevuld
        if (empty($p_sEmailadres)) {
            return "E-mailadres is niet ingevuld.";

            // E-mailadres valideren met FILTER_VALIDATE_EMAIL (ingebouwd, geen gedoe met regex)
        } else if (filter_var($p_sEmailadres, FILTER_VALIDATE_EMAIL)) {
            //het e-mailformaat is geldig. Kijk nu of het geldige e-mailadres in de lijst met toegestane domeinen voorkomt

            //lijst toegestane domeinen
            $toegestaneDomeinen = array('student.thomasmore.be', 'thomasmore.be');

            //Explode e-mailadres en controloleer tweede deel (na de @). Vergelijk met toegestane domeinen.
            $explodedEmail = explode('@', $p_sEmailadres);
            $domain = array_pop($explodedEmail);

            //als e-mailadres een geldig formaat heeft, MAAR NIET matcht met toegestane domeinen
            if (!in_array($domain, $toegestaneDomeinen)) {
                //e-mailadres formaat is geldig + matcht met toegestane domeinen
                return "E-mailadres is geen geldig Thomas More e-mailadres.";
            }

        } else {
            //e-mailadres formaat is ongeldig
            return "E-mailadres is ongeldig.";
        }
    }


    //valideer voor- en familienaam veld
    public function isGeldigVoornaamFamilienaam($p_sVoornaamFamilienaam)
    {
        //controleer of veld voor- en familienaam niet leeg is
        if (empty($p_sVoornaamFamilienaam)) {
            return "Voor- en familienaam is niet ingevuld.";

            //controleer of voor- en familienaam geldig aantal tekens heeft
        } else if (strlen($p_sVoornaamFamilienaam) < 3 || !preg_match("~^(?:[\p{L}\p{Mn}\p{Pd}\'\x{2019}]+\s[\p{L}\p{Mn}\p{Pd}\'\x{2019}]+\s?)+$~u", $p_sVoornaamFamilienaam)) {
            return "Voor- en familienaam is ongeldig.";
        }
    }


    //valideer veld gebruikersnaam
    public function isGeldigGebruikersnaam($p_sGebruikersnaam)
    {

        //controleer of gebruikersnaam is ingevuld
        if (empty($p_sGebruikersnaam)) {
            return "Gebruikersnaam is niet ingevuld.";
            //indien gebruikersnaam is ingevuld, controleer of deze niet groter/kleiner is dan toegelaten aantal tekens
        } else if (strlen($p_sGebruikersnaam) > 20) {
            return "Gebruikersnaam is te lang. Gebruikersnaam mag niet langer dan 20 tekens zijn.";
        } else if (strlen($p_sGebruikersnaam) < 2) {
            return "Gebruikersnaam is te kort. Gebruikersnaam moet moet minstens 2 tekens lang zijn.";
        } else if (!ctype_alnum($p_sGebruikersnaam)) {
            //reguliere expressie controleert of gebruikersnaam enkel letters a-z, of cijfers bevatten. Indien niet, toon error.
            return "Gebruikersnaam ongeldig. Gebruikersnaam mag enkel letters of cijfers bevatten.";
        }
    }


    //valideer veld wachtwoord
    public function isGeldigWachtwoord($p_sWachtwoord)
    {
        if (empty($p_sWachtwoord)) {
            return "Wachtwoord is niet ingevuld.";
        } else if (strlen($p_sWachtwoord) < 6) {
            return "Wachtwoord te kort. Wachtwoord moet minstens 6 tekens lang zijn.";
        }
    }


    public function matchtNieuwWachtwoord($p_sNieuwWachtwoord, $p_sNieuwWachtwoordHerhaal)
    {
        if ($p_sNieuwWachtwoord === $p_sNieuwWachtwoordHerhaal) {
            return true;
        } else {
            return false;
        }
    }


    //valideer geldigheid zoekwoord
    public function isGeldigZoekwoord($p_sZoekwoord)
    {

        $patroonGeldigZoekwoord = "/\S*#(?:\[[^\]]+\]|\S+)/";

        if(preg_match($patroonGeldigZoekwoord, $p_sZoekwoord)){
            //geldig
            return true;
        }else{
            //niet geldig
            return false;
        }
    }


}

?>