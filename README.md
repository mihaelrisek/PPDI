# Katalog sportskih automobila

Ovaj projekt izrađen je kao vježba za predmet **Podatkovna povezanost i digitalna infrastruktura**.

Projekt predstavlja jednostavnu web aplikaciju za prikaz i pretraživanje sportskih automobila. Aplikacija koristi **PHP** za obradu podataka i prikaz stranica, dok se podaci o korisnicima i automobilima pohranjuju u **XML datotekama**. Struktura XML podataka definirana je pomoću **XML Schema (.xsd)** datoteka.

## Funkcionalnosti

- prijava korisnika putem login forme
- provjera korisničkih podataka iz XML datoteke
- prikaz sportskih automobila u obliku kartica
- pretraživanje automobila prema nazivu, marki i ostalim podacima
- prikaz detaljne stranice za svaki automobil
- zaštita stranica pomoću PHP sessiona
- XML shema za definiranje strukture podataka

## Korištene tehnologije

- PHP
- HTML
- CSS
- XML
- XML Schema (XSD)

## Struktura projekta

```text
/project
  index.php
  login.php
  logout.php
  home.php
  details.php
  /data
    users.xml
    cars.xml
    users.xsd
    cars.xsd
  /assets
    /css
      style.css
    /images
