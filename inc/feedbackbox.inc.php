<?php

//deze file dient om error boxen te tonen wanneer dat nodig is. Werkwijze:
// 1. Include deze file op een pagina waar u een fout/successboodschap wil tonen
// 2. Op de plaats waar u de feedback box wil opbouwen (bijvoorbeeld in catch blok) typ $feedback = ("$p_sFeedbackType", "Boodschap");
// 3. In de html doe een echo op de plaats waar de errorbox getoond moet worden. Voorbeeld van uitprint feedbackbox:
/*
if (!empty($feedback)) {
    echo $feedback;
}*/
// Noot: bij klikken op dismiss kruisje zal het error blok verwijdert worden.


function bouwFeedbackBox($p_sFeedbackType, $p_sFeedbackMessage){

    switch($p_sFeedbackType){

        case "success":
            $errorTitle = "Succes! ";
            break;

        case "info":
            $errorTitle = "Info: ";
            break;

        case "warning":
            $errorTitle = "Opgelet: ";
            break;

        case "danger":
            $errorTitle = "Oeps... ";
            break;

        default:
            $errorTitle = "Error: ";
            $p_sFeedbackType = "warning";
            break;
    }

    $feedback = "<div class=\"errorbox\"><a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a><div class=\"alert alert-" . $p_sFeedbackType . "\"><strong>" . $errorTitle ."</strong>" . $p_sFeedbackMessage . "</div></div>";
    return $feedback;
}