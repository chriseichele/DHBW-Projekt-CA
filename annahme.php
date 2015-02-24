<?php
SESSION_START();
require_once('./UserHelper.inc');
require_once('./putCSR.php');

$email = UserHelper::GetUserEmail();

$jahre = $_POST['laufzeit'];

$datei = $_FILES['userfile']['name'];
$dateityp = $_FILES['userfile']['type'];


if(UserHelper::IsLoggedIn()) {

if($_FILES['userfile']['name'] != "") {


	if($_FILES['userfile']['type'] == "application/octet-stream"){
	
		//Datei abspeichern
		try {
			putCSR($datei);
	
			$_SESSION['message']['success'][] = $datei.' wurde hochgeladen.';
			$_SESSION['message']['success'][] = $email.' Das ist die Emailadresse';
			$_SESSION['message']['success'][] = $jahre.' Die Laufzeit haben Sie gewaehlt';
			$_SESSION['message']['success'][] = $dateityp.' Das ist der dateityp';
		} catch(Exception $e) {
  			$_SESSION['message']['error'][] = $e->getMessage();
		}
		
		Header('Location: intermediate.php');
		exit();
	}
	else {
		// Fehlermeldung
		$_SESSION['message']['error'][] = 'Keine PHP-Datei.';
		Header('Location: intermediate.php');
		exit();
		}
//Datei validierung -- ist es eine richtige Datei
}
else{
$_SESSION['message']['error'][] = 'Es wurde keine Datei hochgeladen';
		Header('Location: intermediate.php');
		exit();
}
}
else {
$_SESSION['message']['warning'][] = 'Bitte loggen Sie sich ein um eine Datei hochzuladen';
		Header('Location: intermediate.php');
		exit();
}










//Datenbank eintrag anlegen

?>