#Bixie Development omgeving

##drukwerkcalculator.nl

**Howto aandepraat te krijgen**

_Hebbon_

* [Vagrant](https://www.vagrantup.com) en [VirtualBox](https://www.virtualbox.org/wiki/Downloads).
* [Github](https://windows.github.com) voor Windows
* [Node.js](http://nodejs.org) en [Grunt](http://gruntjs.com)
* [PHPStorm](https://www.jetbrains.com/phpstorm/) natuurlijk.

_Doen nieuwe klant_

* Kopieer standaardbestanden uit dev-repo: `phphet/config.yaml`, `.gitattributes`, `.gitignore`, `Gruntfile.js`,
 `package.json`, `syncfromrepo.sh`, deze readme en de `Vagrantfile` in map met klantnaam (rootmap).
* Maak een GIT klantenrepo aan van de rootmap via Github voor Windows.
* Open Git shell in rootmap en clone Printshop repo: `git clone https://github.com/Bixie/printshop-component`.
* Maak project aan van bestaande bestanden in PHPStorm.
* Voeg extra repo toe aan PHPStorm instellingen. Disable sync branchecontrol en zet component repo op juiste branche (checkout als new local branche).
* Maak map `/sql` aan en plaats sql-dump (met DROP TABLE) van database met naam `klantnaam_dev.sql`.
* Pas `config.yaml` aan met de klantnaam. Aanpassen in vhostadres en -alias en database gegevens.
* Voeg entry `192.168.56.101 klantnaam.dev www.klantnaam.dev` toe aan `C:\Windows\System32\drivers\etc\hosts`
* Ga naar [Puphpet](https://puphpet.com) en plak de inhoud van je `config.yaml` daar in.
* Create en download `puphpet.php` in de rootmap.
* Pak inhoud van random dir uit in de rootmap. Overschijf de `.gitattributes`, `Vagrantfile`
 en `config.yaml`.
* Commit al dit moois als initiele commit in de klantenrepo
* Time to `vagrant up`! Via console of PHPStorm. (vergeet de kop koffie niet!)
* .....omnomnom....
* De mappen `bixieprintshop`, `html` en `default` zijn aangemaakt.
* Plaats een kopie van de site in de map `bixieprintshop`.
* Pas de `configuration.php` aan. Databasegegevens en paden. Pad is `/var/www/log`.
* Ga naar `www.klantnaam.dev` in je browser, en watch the beast come alive.
* Pas evt. `.gitingore` aan met extra (zoo) mappen in site.
* Sync eventueel met live-site en commit printshop, eventuele zoo-mappen en template naar klantrepo

_Doen bestaande klantrepo_

* Clone de repo vanuit [Github](https://github.com/Bixie) naar je projectmap.
* Open de Github Shell in de nieuwe map (rootmap) en clone Printshop repo: `git clone https://github.com/Bixie/printshop-component`.
* Maak project aan van bestaande bestanden in PHPStorm.
* Voeg extra repo toe aan PHPStorm instellingen. Disable sync branchecontrol en zet component repo op juiste branche (checkout als new local branche).
* Ga naar [Puphpet](https://puphpet.com) en plak de inhoud van je `config.yaml` daar in.
* Create en download `puphpet.zip` in de rootmap.
* Pak inhoud van random dir uit in de rootmap. Overschijf de `.gitattributes`, `Vagrantfile` en `config.yaml`.
* Time to `vagrant up`! Via console of PHPStorm. (vergeet de kop koffie niet!)
* .....omnomnom....
* De mappen `html` en `default` zijn aangemaakt.
* Vul de bestanden in de map `bixieprintshop` aan met de overige bestanden van de site.
* Pas de `configuration.php` aan. Databasegegevens en paden. Pad is `/var/www/log`.
* Ga naar `www.klantnaam.dev` in je browser, en watch the beast come alive.

_Bestanden syncen_

* Controleer of de Printshop repo op de goede branche staat en kopieer de bestanden uit de repo naar de webroot door 
`bash syncfromrepo.sh` in de SSH van de Vagrant uit te voeren.
* Geef commando `grunt watch` in de console van de _lokale_ machine.
* Ready to go!

