<?php 
$pagetitle = "CSR und SANs Tutorial";

include('./header.php');

?>

<div class="jumbotron">
    <div class="container">
        <h1>CSR Tutorial mit SANs</h1>
        <p>Schritt f&uuml;r Schritt Anleitung zur Erstellung eines <b>C</b>ertificate-<b>S</b>igning-<b>R</b>equests mit Hilfe von OpenSSL.</p>
    </div>
</div>
<div class="alert alert-warning container">
    <p>
        <span style="float:right;margin-right:10px;padding-top:5px;">Wollten Sie stattdessen ein Standard-Zertifikat erstellen?</span>
        <a style="float:right;" class="btn btn-default btn-sm" href="./csrtutorial.php" title="Einfaches CSR-Tutorial ansehen">CSR-Tutorial ansehen</a>
    </p>
</div>
<div class="container">
<h3>Anpassung der OpenSSL-Konfigurationsdatei</h3>
<p>Um mehrere SANs zu erzeugen muss die openssl Konfigurationsdatei angepasst werden.
<br/>Als Erstes wird der [req]-Bereich bearbeitet. Es muss mitgegeben werden, dass der zu erstellende CSR die "x509 V3 extensions" beinhalten soll.
<br/>Dieser Befehl teilt OpenSSL mit, dass die Datei einen [v3_req]-Bereich beinhalten soll:</p>
<pre class="bg-info">[req]
distinguished_name = req_distinguished_name
req_extensions = v3_req
</pre>

<p>Anschlie&szlig;end wird der [v3_req]-Bereich bearbeitet:</p>

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

<h3>Erzeugung des privaten Schl&uuml;ssels</h3>
<p>Nun muss mit folgendem Terminal-Befehl ein privater Schl&uuml;ssel erzeugt werden.</p>
<pre class="bg-info">openssl genrsa -out san_domain_com.key 2048</pre>

<h3>Erzeugung der CSR-Datei</h3>
<p>Anschlie&szlig;end wird mit Hilfe der bearbeiteten Konfigurationsdatei und folgendem Terminal-Befehl die CSR-Datei erzeugt:</p>
<pre class="bg-info">openssl req -new -out san_domain_com.csr -key san_domain_com.key -config openssl.cnf</pre>

	<br />
	<div class="alert alert-success">
		<h3 style="margin-top:0;">CSR Erstellung abgeschlossen</h3>
		<p>
			Nachdem Sie diese Schritte ausgef&uuml;hrt haben, wurden die Dateien <b>san_domain_com.key/b> und <b>san_domain_com.csr</b> erstellt, 
			welche den privaten Schl&uuml;ssel und die Zertifikatsanforderung beinhalten.
		</p>
	</div>
</div>

<?php include('./footer.php'); ?>