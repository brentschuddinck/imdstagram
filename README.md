# IMDstagram
IMDstagram is een applicatie bedoelt voor studenten Interactive Multimedia Design (IMD) aan de Thomas More hogeschool. De applicatie heeft als doel om inspirerende afbeeldingen en video's onder de studenten uit te wisselen. De applicatie werkt zoals het concept van Instagram.
Clausule: de ontwikkelaars zijn niet verantwoordelijk voor mogelijke schade, fouten, problemen, verlies of verspreiding van gegevens en/of materiaal die voortvloeien uit het gebruik van de applicatie. Het gebruik van de applicatie op eigen risico.

Ontwikkelaars:
- Brent Schuddinck
- Ben Witters
- Daphné Van Belle

## Connecteren met online database
- Om gemakkelijk te werken, werd gekozen om meteen een online database te gebruiken. Om connectie te maken via PDO:
$dbconnection = new PDO("mysql:host=178.62.241.17; dbname=imdstagram", "imdstagram", "[lavfte{gqwedzsrjme7xsmXU");
- url database : http://178.62.241.17/phpmyadmin

## Conventies
- OOP programmeren
- Alle validaties gebeuren in een aparte validatieklasse
- Membervariabelen: $m_typeNaam, ...
- Getters en setters worden automatisch gegenereerd
- PDO
- htmlspecialchars() voor OUTPUT, INPUT niet extra beveiligen zit in PDO
- classes gebruiken

## Features verdelen
- Brent: 1, 4, 7, 10, 13, 16, level 4
- Ben: 2, 5, 8, 11, 14,17 level 4
- Daphné: 3, 6, 9, 12, 15, level 4

## Nieuwe PHP file aanmaken
- Helemaal bovenaan sessiecontrole.php includen op elke standaard PHP pagina.

## Sessiewaarden
Ik denk dat het een goed idee dat wanenner de gebruiker ingelogd wordt, er eenmalig waarden uit db in sessie gestopt worden voor onderstaande zaken. Op die manier moet niet bij elke pagina dezelfde query uitgevoerd worden. Mijn code is ingesteld op (dummycode):
- $_SESSION['login']['userid'] //sessie_id ophalen
- $_SESSION['login']['gebruikersnaam'] //username ophalen
- $_SESSION['login']['profielfoto']  //link profile_picture
- $_SESSION['login']['email'] //emailadres ophalen
- $_SESSION['login']['naam'] //volledige naam ophalen
- $_SESSION['login']['private'] //accountstatus ophalen 0 = openbaar, 1 = private

## Feedback tonen via zelf gemaakte feedbackbox
In het document inc/feedbackbox.inc.php staat uileg hoe je op een heel gemakkelijke manier coole error/succesboodschappen kan tonen. Ik heb de cases zelf geschreven om herhalingen tegen te gaan bij het forumeren van errorberichten.
