# IMDstagram
IMDstagram is een applicatie bedoelt voor studenten Interactive Multimedia Design (IMD) aan de Thomas More hogeschool. De applicatie heeft als doel om inspirerende afbeeldingen en video's onder de studenten uit te wisselen. De applicatie werkt zoals het concept van Instagram.

## Conventies
- OOP programmeren
- Alle validaties gebeuren in een aparte validatieklasse
- Membervariabelen: $m_typeNaam, ...
- Getters en setters worden automatisch gegenereerd
- PDO
- htmlspecialchars()
- classes gebruiken

## Feature list
- Account aanmaken met bcrypt wachtwoord
- Inloggen op een veilige manier
- Uitloggen
- Profiel en instellingen aanpassen
- Foto posten met beschrijving
- Na inloggen 20 meest recente foto's van je vriendelijst weergeven
- Meer foto's laden AJAX
- Zoekfunctie
- Foto liken AJAX
- Commentaar achterlaten op foto via AJAX
- Bescherming tegen XSS attacks
- Foto markering als ongepast
- Eigen foto's verwijderen, niet die van iemand anders
- Doorklikken op een username met detailinformatie
- Bijhouden wanneer een foto werd opgeladen in de databank
- Private account optie
- Indien private, enkel followers kunnen de foto's van deze persoon zien nadat een vriendschapsverzoek verstuurd en goedgekeurd is
- Vriendschapsverzoek versturen
- Volgers goedkeuren of afwijzen
- Inloggen via Facebook (nog niet toegevoegd)
- Foto uploaden met filters (CSSgram)
- Foto weergeven in de juiste filter
- Locatie/stad automatisch bewaren waar foto werd opgeladen (html5 geolocation)
- Account kan verwijderd worden

## Features verdelen
De volgorde van uitgewerkte features hieronder is gebaseerd op het scopebestand van het project, niet in volgorde van de opgesomde features van hierboven.
- Brent: 1, 3, 4, 6, 7, 10, 16, level 4 (delete account and all related information)
- Ben: 2, 5, 8, 9, 11, 12, 13, 14, 17
