# IMDstagram
IMDstagram is een applicatie bedoelt voor studenten Interactive Multimedia Design (IMD) aan de Thomas More hogeschool. De applicatie heeft als doel om inspirerende afbeeldingen en video's onder de studenten uit te wisselen. De applicatie werkt zoals het concept van Instagram.
Clausule: de ontwikkelaars zijn niet verantwoordelijk voor mogelijke schade, fouten, problemen, verlies of verspreiding van gegevens en/of materiaal die voortvloeien uit het gebruik van de applicatie. Het gebruik van de applicatie op eigen risico.

Ontwikkelaars:
- Brent Schuddinck
- Ben Witters
- Daphné Van Belle

## Connecteren met online database
Om gemakkelijk te werken, werd gekozen om meteen een online database te gebruiken. Om connectie te maken via PDO:
$dbconnection = new PDO("mysql:host=159.253.0.244; dbname=brentca106_imdstagram", "brentca106_imdst", "gvqQpiGqo#(5)");

## Conventies
- OOP programmeren
- Alle validaties gebeuren in een aparte validatieklasse
- Membervariabelen: $m_typeNaam, ...
- Getters en setters worden automatisch gegenereerd
- PDO
- htmlspecialchars() voor OUTPUT, INPUT niet extra beveiligen zit in PDO
- classes gebruiken