#!/bin/bash
#createCRT
#Ein Bash-Skript, das ein csr nimmt und es unterschreibt ohne dabei eine Userinteraktion zu benötigen
#Ausführbar aus PHP oder jeder x-beliebigen Sprache
#Das Skript muss je nach Server mit Admin-Rechten ausgeführt werden (/var/www/html/ ist standardmäßig schreibgeschützt)

#$1 name der csr-Datei
#$2 name der Output-Datei

csr=/var/www/html/${1}/${1}.csr
cmd=/var/www/html/${2}.crt
 
openssl ca -config /home/arne/ssl/certificat.cnf -in ${csr} -out ${cmd} -batch
