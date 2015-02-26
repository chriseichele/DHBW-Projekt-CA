<?php
SESSION_START();
require_once('./UserHelper.inc');
require_once('./putCSR.php');

doUserRightsCheck();

$backurl = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'index.php#noreferer';
$backurl = (basename($backurl)==basename($_SERVER['SCRIPT_NAME'])) ? 'index.php#backlink' : $backurl;

$email = UserHelper::GetUserEmail();

if(isset($_POST['zerttype'])){
	$zerttype = $_POST['zerttype'];
}
else {
	$_SESSION['message']['error'][] = 'Kein Zertifikatstyp gesetzt.';
	Header('Location: '.$backurl);
	exit();
}

if(isset($_POST['laufzeit'])){
	$jahre = $_POST['laufzeit'];
}
else {
	$_SESSION['message']['error'][] = 'Keine Laufzeit ausgew&auml;hlt.';
	Header('Location: '.$backurl);
	exit();
}

if($zerttype == 'intermediate') {
	if($jahre == '3') {
		$jahre = 3;
	}
	elseif($jahre == '5') {
		$jahre = 5;
	}
	elseif($jahre == '10') {
		$jahre = 10;
	}
	else {
		$jahre = null;
		$_SESSION['message']['error'][] = 'Ung&uuml;ltige Laufzeit.';
		Header('Location: '.$backurl);
		exit();
	}
}
elseif($zerttype == 'normal') {
	if($jahre == '1') {
		$jahre = 1;
	}
	elseif($jahre == '3') {
		$jahre = 3;
	}
	elseif($jahre == '5') {
		$jahre = 5;
	}
	else {
		$jahre = null;
		$_SESSION['message']['error'][] = 'Ung&uuml;ltige Laufzeit.';
		Header('Location: '.$backurl);
		exit();
	}
}
else {
	$_SESSION['message']['error'][] = 'Ung&uuml;ltiger Zertifikatstyp.';
	Header('Location: '.$backurl);
	exit();
}


if(isset($_FILES['userfile'])){
	$file = $_FILES['userfile'];
	$dateiname = $_FILES['userfile']['name'];
	$dateityp = $_FILES['userfile']['type'];
}
else {
	$_SESSION['message']['error'][] = 'Keine Datei hochgeladen.';
	Header('Location: '.$backurl);
	exit();
}



if(UserHelper::IsLoggedIn()) {

	if($dateityp == "application/octet-stream") {
	
		//Datei abspeichern
		try {
			putCSR($file, $jahre, $dateiname);
	
			$_SESSION['message']['success'][] = $dateiname.' wurde hochgeladen.';
			$_SESSION['message']['success'][] = $email.' Das ist die Emailadresse';
			$_SESSION['message']['success'][] = $jahre.' Die Laufzeit haben Sie gewaehlt';
			$_SESSION['message']['success'][] = $dateityp.' Das ist der dateityp';
		} 
		catch(Exception $e) {
  			$_SESSION['message']['error'][] = $e->getMessage();
		}
		
		Header('Location: '.$backurl);
		exit();
	}
	else {
		// Fehlermeldung
		$_SESSION['message']['error'][] = 'Keine CSR-Datei.';
		Header('Location: '.$backurl);
		exit();
	}

}
else {
	$_SESSION['message']['warning'][] = 'Bitte loggen Sie sich ein um eine Datei hochzuladen';
	Header('Location: index.php');
	exit();
}










//Datenbank eintrag anlegen

?>