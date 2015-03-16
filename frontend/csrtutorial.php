<?php 
$pagetitle = "CSR Tutorial";

include('./header.php');

?>

<div class="jumbotron">
    <div class="container">
        <h1>CSR Tutorial</h1>
        <p>Schritt f&uuml;r Schritt Anleitung zur Erstellung eines <b>C</b>ertificate-<b>S</b>igning-<b>R</b>equests mit OpenSSL.</p>
    </div>
</div>
<div class="container">
	<h3>Rufen Sie das Programm <b>openssl</b> auf, um die Aufforderung zu erzeugen:</h3>
	<br />
	
	<pre class="bg-info">openssl req -nodes -new -newkey rsa:2048 -sha256 -out csr.pem</pre>
	<p>
		Dies erzeugt einen privaten Schl&uuml;ssel und eine zugeh&ouml;rige Zertifikatsanfrage. 
		Es erscheint nun folgende Ausgabe auf ihrem Bildschirm:
	</p>
	<pre class="bg-info"># Generating a 2048 bit RSA private key
# ...............................................++++++
# ............................++++++
# writing new private key to 'privkey.pem'
# -----
# You are about to be asked to enter information that will be incorporated
# into your certificate request.
# What you are about to enter is what is called a Distinguished Name or a DN.
# There are quite a few fields but you can leave some blank
# For some fields there will be a default value,
# If you enter '.', the field will be left blank.
# -----</pre>
	<br />

	<h3>Im Anschluss werden Ihnen nun Fragen zu den Registrierungsinformationen gestellt</h3>
	<br />
	
	<h4>Tragen Sie hier den 2-stelligen L&auml;ndercode (DE = Deutschland) ein:</h4>
	
	<pre class="bg-info"># Country Name (2 letter code) [AU]: DE</pre>
	<br />
	
	<h4>Geben Sie hier Ihr Bundesland an:</h4>
	<pre class="bg-info"># State or Province Name (full name) [Some-State]: Baden-Wuerttemberg</pre>
	<br />
	
	<h4>Geben Sie hier Ihre Stadt an:</h4>
	<pre class="bg-info"># Locality Name (eg, city) []: Heidenheim an der Brenz</pre>
	<br />
	
	<h4>Geben Sie hier Ihren Namen bzw. Firmennamen an:</h4>
	<pre class="bg-info"># Organization Name (eg, company) [Internet Widgits Pty Ltd]: DHBW Heidenheim</pre>
	<br />
	
	<h4>Geben Sie optional Ihre Abteilung an:</h4>
	<pre># Organizational Unit Name (eg, section) []: ---</pre>
	<br />
	
	<h4>Geben Sie hier den genauen Domainnamen an, welcher durch das Zertifikat gesch&uuml;tzt werden soll:</h4>
	<pre class="bg-info"># Common Name (eg, YOUR name) []: example.com</pre>
	<p>Wichtig: Zertifikat ist dann auch nur f&uuml;r diese Eingabe g&uuml;ltig!</p>
	<br />

	<h4>Geben Sie hier die E-Mailadresse des Verantwortlichen ein:</h4>
	<pre class="bg-info"># Email Address []: hostmaster@example.org</pre>
	<br />
	
	<h4>Folgende, weitere Angaben sind optional:</h4>
	<pre># Please enter the following 'extra' attributes
# to be sent with your certificate request
# A challenge password []: 
# An optional company name []: </pre>
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