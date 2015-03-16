<?php 
$pagetitle = "CSR und SANs Tutorial";

include('./header.php');

?>

<div class="jumbotron">
    <div class="container">
        <h1>CSR Tutorial</h1>
        <p>Schritt f&uuml;r Schritt Anleitung zur Erstellung eines <b>C</b>ertificate-<b>S</b>igning-<b>R</b>equests mit mehreren SANs mit Hilfe von OpenSSL.</p>
    </div>
</div>
<div class="container">
	<h3>Um mehrere SANs zu erzeugen muss die openssl Konfigurationsdatei angepasst werden.</h3>
	<h3>Als erstes wird der [req] Bereich bearbeitet. Es muss mitgegeben werden dass das zu erstellende CSR die x509 V3 extensions beinhalten.</h3>

	<pre class="bg-info">[req]
	distinguished_name = req_distinguished_name
req_extensions = v3_req
</pre>
<h3>Dies teilt dem openssl mit das ein v3_req Bereich beinhaltet wird.</h3>
<h3>Nun wird der v3_req Bereich bearbeitet.</h3>

<pre class="bg-info">[req_distinguished_name]
countryName = Country Name (2 letter code)
stateOrProvinceName = State or Province Name (full name)
localityName = Locality Name (eg, city)
organizationalUnitName	= Organizational Unit Name (eg, section)
commonName = Internet Widgits Ltd
commonName_max	= 64

[ v3_req ]
# Extensions to add to a certificate request
basicConstraints = CA:FALSE
keyUsage = nonRepudiation, digitalSignature, keyEncipherment
subjectAltName = @alt_names

[alt_names]
DNS.1 = dhbw.heidenheim.com
DNS.2 = dhbw.beispiel.org
DNS.3 = system.heidenheim.net
IP.1 = 192.168.1.1
IP.2 = 192.168.69.14
</pre>

<h3>Nun muss ein privater Schl&uuml;ssel erzeugt werden.</h3>
<pre class="bg-info">openssl genrsa -out san_domain_com.key 2048</pre>

<h3>Anschließend wird die CSR-Datei erzeugt.</h3>
<pre class="bg-info">openssl req -new -out san_domain_com.csr -key san_domain_com.key -config openssl.cnf</pre>

	
	<div class="alert alert-success">
		<h3 style="margin-top:0;">CSR Erstellung abgeschlossen</h3>
		<p>
			Nachdem Sie diese Schritte ausgef&uuml;hrt haben, wurden die Dateien <b>privkey.pem</b> und <b>csr.pem</b> erstellt, 
			welche den privaten Schl&uuml;ssel und die Zertifikatsanforderung beinhalten.
		</p>
	</div>
</div>

<?php include('./footer.php'); ?>