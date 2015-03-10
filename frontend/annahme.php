<?php
SESSION_START();
require_once('./UserHelper.php');
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
	$is_intermediate = true;
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
	$is_intermediate = false;
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
	$is_intermediate = false;
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

	if($dateityp == "application/x-x509-ca-cert" 
	|| $dateityp == "application/octet-stream" 
	|| $dateityp == "application/pkcs10") {
	
		//Datei abspeichern
		try {
			$csr_id = putCSR($file, $jahre, $is_intermediate);
			
			$laufzeit_string = ($jahre <= 1) ? ($jahre." Jahr") : ($jahre." Jahre") ;
			$_SESSION['message']['success'][] = 'Der CSR "'.$dateiname.'" mit gew&uuml;nschter Laufzeit '.$laufzeit_string.' wurde erfolgreich hochgeladen. <a href="./viewCSR.php?csr='.$csr_id.'">Anzeigen</a>';
			
			//Admins über neue Datei benachrichtigen
			require_once('./MailHelper.php');
			try {
			send_new_cert_mail_to_admins($csr_id)
			} catch(Exception $e) {
  				//trotzdem keine fehlermeldung ausgeben, da sie den Kunden nix angehen
			} 
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

?>