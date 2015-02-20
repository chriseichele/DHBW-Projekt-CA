<?php
SESSION_START();
require_once('./UserHelper.inc');

$email = UserHelper::GetUserEmail();


$datei = $_FILES['csr']['name'];
$dateityp = $_FILES['csr']['type'];

if(UserHelper::IsLoggedIn()) {

if($_FILES['csr']['name'] != "") {

	if($_FILES['csr']['type'] == "text/php"){
	
		//Datei abspeichern
		move_uploaded_file($_FILES['csr']['tmp_name'], "datei.txt"); 
	
		$_SESSION['message']['success'][] = $datei.' wurde hochgeladen.';
		$_SESSION['message']['success'][] = $email.' Das ist die Emailadresse';
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