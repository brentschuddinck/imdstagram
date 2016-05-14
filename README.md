# IMDstagram
IMDstagram is een applicatie bedoelt voor studenten Interactive Multimedia Design (IMD) aan de Thomas More hogeschool. De applicatie heeft als doel om inspirerende afbeeldingen en video's onder de studenten uit te wisselen. De applicatie werkt zoals het concept van Instagram.

## Connecteren met online database
- Om gemakkelijk te werken, werd gekozen om meteen een online database te gebruiken.
- url online database : http://178.62.241.17/phpmyadmin

## Conventies
- OOP programmeren
- Alle validaties gebeuren in een aparte validatieklasse
- Membervariabelen: $m_typeNaam, ...
- Getters en setters worden automatisch gegenereerd
- PDO
- htmlspecialchars() voor OUTPUT, INPUT niet extra beveiligen zit in PDO
- classes gebruiken

## Features verdelen
- Brent: 1, 3, 4, 7, 10, 16, level 4 (delete account and all related information)
- Ben: 2, 5, 8, 9, 11, 12, 13, 14, 17
- Daphné: [oorspronkelijk: 3 (Brent gedaan), 6, 9(Ben gedaan), 12(Ben gedaan), 15, level 4]. Daphné heeft geen enkele feature uitgewerkt, gecommit, of doorgestuurd.

## Nieuwe PHP file aanmaken
- Helemaal bovenaan sessiecontrole.php includen op elke standaard PHP pagina.

## Sessiewaarden
Ik denk dat het een goed idee dat wanenner de gebruiker ingelogd wordt, er eenmalig waarden uit db in sessie gestopt worden voor onderstaande zaken. Op die manier moet niet bij elke pagina dezelfde query uitgevoerd worden. Mijn code is ingesteld op (dummycode):
- $_SESSION['login']['userid'] //sessie_id ophalen
- $_SESSION['login']['username'] //username ophalen
- $_SESSION['login']['profilepicture']  //link profile_picture
- $_SESSION['login']['email'] //emailadres ophalen
- $_SESSION['login']['name'] //volledige naam ophalen
- $_SESSION['login']['private'] //accountstatus ophalen 0 = openbaar, 1 = private

## Feedback tonen via zelf gemaakte feedbackbox
In het document inc/feedbackbox.inc.php staat uileg hoe je op een heel gemakkelijke manier error/succesboodschappen kan tonen. Ik heb de cases zelf geschreven om herhalingen tegen te gaan bij het forumeren van errorberichten.
