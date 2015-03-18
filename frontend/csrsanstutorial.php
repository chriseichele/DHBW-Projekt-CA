<?php 
$pagetitle = "CSR und SANs Tutorial";

include('./header.php');

?>

<div class="jumbotron">
    <div class="container">
        <h1>CSR Tutorial mit SANs</h1>
        <p>Schritt f&uuml;r Schritt Anleitung zur Erstellung eines <b>C</b>ertificate-<b>S</b>igning-<b>R</b>equests mit mehreren SANs mit Hilfe von OpenSSL.</p>
        <br/>
        <p>Wollten Sie stattdessen ein Standard-Zertifikat erstellen?</p>
        <a class="btn btn-primary btn-lg" href="./csrsanstutorial.php" title="Einfaches CSR-Tutorial ansehen">CSR-Tutorial</a>
    </div>
</div>
<div class="container">
<p>Um mehrere SANs zu erzeugen muss die openssl Konfigurationsdatei angepasst werden.</p>
<p>Als Erstes wird der [req]-Bereich bearbeitet. Es muss mitgegeben werden, dass der zu erstellende CSR die "x509 V3 extensions" beinhalten soll.</h3>

<pre class="bg-info">[req]
distinguished_name = req_distinguished_name
req_extensions = v3_req
</pre>
<p>Dies teilt OpenSSL mit, dass ein [v3_req]-Bereich beinhaltet sein soll</p>

<p>Nun wird der [v3_req]-Bereich bearbeitet.</p>

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
IP.2 = 192.168.69.14</pre>

<p>Nun muss ein privater Schl&uuml;ssel erzeugt werden.</p>
<pre class="bg-info">openssl genrsa -out san_domain_com.key 2048</pre>

<p>Anschließend wird die CSR-Datei erzeugt.</p>
<pre class="bg-info">openssl req -new -out san_domain_com.csr -key san_domain_com.key -config openssl.cnf</pre>

	<br />
	<div class="alert alert-success">
		<h3 style="margin-top:0;">CSR Erstellung abgeschlossen</h3>
		<p>
			Nachdem Sie diese Schritte ausgef&uuml;hrt haben, wurden die Dateien <b>privkey.pem</b> und <b>csr.pem</b> erstellt, 
			welche den privaten Schl&uuml;ssel und die Zertifikatsanforderung beinhalten.
		</p>
	</div>
</div>

<?php include('./footer.php'); ?>