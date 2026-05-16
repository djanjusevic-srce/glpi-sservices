# glpi-plugin-sservices

Naziv plugina: sservices (SServices u web sučelju)
Repo: https://github.com/djanjusevic-srce/glpi-sservices
Autor: Marko Ivančić <mivanci@srce.hr>
Doprinos: Dragan Janjušević <drago@srce.hr>

## Instalacija

Arhivu s izvornim kodom je potrebno postaviti u GLPI plugin/ direktorij i otpakirati sadržaj arhive 
u direktorij imena 'sservices' (/putanja/do/glpi/plugins/sservices) te direktoriju 'sservices' i svim datotekama 
unutar postaviti isto vlasništvo kao što je postavljeno na GLPI core datotekama i direktorijima (najčešće apache:root,
apache:apache, www-data:root ili www-data:www-data).  

Pošto izvorni kod plugina postoji kao GIT repozitorij, može se iskoristiti mogućnost kloniranja samog repozitorija, 
a kasnije i njegovog ažuriranja po potrebi.

### Kloniranje repozitorija

Važno: pri kloniranju repozitorija potrebno je paziti da se direktorij u koji će se klonirati repozitorij zove
'sservices' (kao naziv plugina).

```shell
cd /putanja/do/glpi/plugins
git clone git@github.com:djanjusevic-srce/glpi-sservices.git sservices
```

Nakon kloniranja repozitorija, u GLPI web sučelju potrebno je obaviti instalaciju plugina 'SServices', na standardni
način kao i za bilo koji drugi GLPI plugin: 
* u izborniku odabrati Setup -> Plugins
* pronaći plugin SServices
* klik na gumb 'Install'
* klik na gumb 'Enable'

### Ažuriranje repozitorija / update plugin-a

Ako se izvorni kod plugina postavlja iz GIT repozitorija, isti se može po potrebi ažurirati / postaviti na noviju
verziju na sljedeći način:

```shell
cd /putanja/do/glpi/plugins/sservices
git pull
```

Nakon što se dohvate najnovije promjene, u GLPI web sučelju potrebno je obaviti nadogradnju plugina 'SServices', na
standardni način (Setup -> Plugins, pronaći SServices, klik na gumb 'Update', pa klik na gumb 'Enable').
