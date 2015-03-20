<?php

include('UserHelper.php');

doAdminRightsCheck();

if(isset($_POST['killall']) && $_POST['killall'] == 'true') {
	$doReset = true;
} else {
	$doReset = false;
}

require_once('./db.php');

$pagetitle = "RESET Script";

include('./header.php');

echo '<div class="container">';

if(!$doReset) {
	
	echo '<h1>'.$pagetitle.'</h1>';
	echo '<form style="margin:2em 0 0.5em;" method="post" action="'.$_SERVER["PHP_SELF"].'">';
	echo '<input type="hidden" name="killall" value="true" />';
	echo '<input type="submit" onclick="return confirm(\'Sind Sie sicher, dass Sie alle Daten unwiderruflich l&ouml;schen m&ouml;chten?\');"  class="btn btn-block btn-lg btn-danger" value="ALLES UNWIDERRRUFLICH L&Ouml;SCHEN" />';
	echo '</form>';

} else {

	//Wirklich alles zurücksetzen

	echo '<h1>DB Reset wird ausgeführt...</h1>';

	$db = new DBAccess();
	$db->reset_db();

	$pathToCSR = 'c:\apache24\ca\kunden\csr\\';
	$pathToCRT = 'c:\apache24\ca\kunden\crt\\';

	//Löschen des Dateisystems
	system("rmdir ".escapeshellarg($pathToCSR) . " /s /q");
	system("rmdir ".escapeshellarg($pathToCRT) . " /s /q");

	//Löschen der Logs
	unlink("c:\apache24\logs\\"."crtlog.log");
	unlink("c:\apache24\logs\\"."csrlog.log");
	unlink("c:\apache24\logs\\"."dblog.log");
	unlink("c:\apache24\logs\\"."maillog.log");
	unlink("c:\apache24\logs\\"."openssllog.log");
	unlink("c:\apache24\logs\\"."accountlog.log");

	echo '<h1>Verzeichnisse werden wieder angelegt...</h1>';

	echo '<br/>CSR Ordner: ';
	echo (mkdir($pathToCSR)==true) ? "Erfolg" : "Fehler";
	echo '<br/>CRT Ordner: ';
	echo (mkdir($pathToCRT)==true) ? "Erfolg" : "Fehler";

	echo '<h1>Ergebnis nach Löschaktion</h1>';
	echo '<h2>DB Requests</h2>';
	echo '<pre>';
	print_r($db->get_request_all());
	echo '</pre>';
	echo '<h2>DB SANS</h2>';
	echo '<pre>';
	print_r($db->get_sans_all());
	echo '</pre>';

	echo '<h2>Dateisystem</h2>';

	echo '<h3>CSRs</h3>';
	$pathToCSR = 'c:\apache24\ca\kunden\csr\\';
	echo '<p>'.$pathToCSR.'</p>';
	echo '<pre>';
	print_r(scandir($pathToCSR));
	echo '</pre>';

	echo '<h3>CRTs</h3>';
	$pathToCRT = 'c:\apache24\ca\kunden\crt\\';
	echo '<p>'.$pathToCRT.'</p>';
	echo '<pre>';
	print_r(scandir($pathToCRT));
	echo '</pre>';

}

echo '</div>';

include('./footer.php');

?>