# glpi-sservices

Naziv plugina: sservices (SServices u web sučelju)
Repo: https://gitlab.srce.hr/glpi/glpi-plugin-sservices

## Instalacija

Plugin se instalira na standardan GLPI način. Dakle, izvorni kod plugin-a je potrebno postaviti u direktorij
/putanja/do/glpi/plugins, s nazivom direktorija plugina 'sservices' (/putanja/do/glpi/plugins/sservices).
Pošto izvorni kod plugina postoji kao GIT repozitorij, može se iskoristiti mogućnost kloniranja samog
repozitorija, a kasnije i njegovog ažuriranja po potrebi. 

### Kloniranje repozitorija

Važno: pri kloniranju repozitorija potrebno je paziti da se direktorij u koji će se klonirati repozitorij zove
'sservices' (kao naziv plugina).

```shell
cd /putanja/do/glpi/plugins
git clone git@gitlab.srce.hr:glpi/glpi-plugin-sservices.git sservices
```

Nakon kloniranja repozitorija, u GLPI web sučelju potrebno je obaviti instalaciju plugina 'SServices', na standardni
način kao i za bilo koji drugi GLPI plugin: 
* u izborniku odabrati Setup -> Plugins
* pronaći plugin SServices
* klik na gumbić 'Install'
* klik na gumbić 'Enable'

### Ažuriranje repozitorija / update plugin-a

Ako se izvorni kod plugina postavlja iz GIT repozitorija, isti se može po potrebi ažurirati / postaviti na noviju
verziju na sljedeći način:

```shell
cd /putanja/do/glpi/plugins/sservices
git pull
```

Nakon što se povuku najnovije promjene, u GLPI web sučelju potrebno je obaviti update plugina 'SServices', na
standardni način (Setup -> Plugins, pronaći SServices, klik na gumbić 'Update', pa klik na gumbić 'Enable').
