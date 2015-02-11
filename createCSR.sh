#!/bin/bash
# $1 Name der Zertifikatsseite ohne www davor (e. g. example.com)
# $2 Wert für Country
# $3 Wert für State
# $4 Wert für Location
# $5 Wert für Organisation
# $6 Wert für Organizational Unit
# $7 Wert für die Kontakt eMail
# $8 Wert für SAN1
# $9 Wert für SAN2
# $10 Wert für den Key (BASE64 encoded)

folder=/var/www/html/$1

#prepare Folder
#sudo mkdir ${folder}

#keyfile erzeugen
keyToPath=/var/www/html/${1}/${1}.key
#sudo touch ${keyToPath} > $11

sudo openssl req -new -batch -key ${keyToPath} -out ${folder}/${1}.csr -subj "/C=${2}/ST=${3}/L=${4}/O=${5}/OU=${6}/CN=www.${1}/emailAddress=${7}/subjectAltName=DNS.1=${1},DNS.2=www.${9}.${1},DNS.2=www.${10}.${1}"

# optionale Eingaben für die CSR
#a challenge password
#optional company name
