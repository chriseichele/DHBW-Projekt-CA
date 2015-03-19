<?php
SESSION_START();
require_once('./UserHelper.php');
require_once('./db.php');
require_once('./LogHelper.php');

doUserRightsCheck();

$backurl = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'index.php#noreferer';
$backurl = (basename($backurl)==basename($_SERVER['SCRIPT_NAME'])) ? 'index.php#backlink' : $backurl;

$email = UserHelper::GetUserEmail();

if(isset($_POST['csr'])) {
	$csr_id = $_POST['csr'];
	//Prüfen ob Zertifikat dem User gehört
	$db = new DBAccess();
	$where = array("requester","=","'".UserHelper::GetUserEmail()."'"," AND ","id","=","'".$csr_id."'");
	$requests = $db->get_request_all_where($where);
	$request = reset($requests);
	if($request->id == $csr_id) {
		//Alles OK
	}
	else {
		//Zertifikat gehört nicht dem User (oder ID ist quatsch)
		$_SESSION['message']['warning'][] = 'Sie haben kein g&uuml;ltiges Ausgangszertifikat ausgew&auml;hlt.';
		Header('Location: '.$backurl);
		exit();
	}
}
else {
	$_SESSION['message']['warning'][] = 'Sie haben kein Ausgangszertifikat ausgew&auml;hlt.';
	Header('Location: '.$backurl);
	exit();
}

if(isset($_POST['laufzeit'])){
	$laufzeit = $_POST['laufzeit'];
	
	if($laufzeit == '0.25') {
		$jahre = 0.25;
	}
	elseif($laufzeit == '0.5') {
		$jahre = 0.5;
	}
	elseif($laufzeit == '0.75') {
		$jahre = 0.75;
	}
	elseif($laufzeit == '1') {
		$jahre = 1;
	}
	elseif($laufzeit == '2') {
		$jahre = 2;
	}
	elseif($laufzeit == '3') {
		$jahre = 3;
	}
	elseif($laufzeit == '4') {
		$jahre = 4;
	}
	elseif($laufzeit == '5') {
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
	$_SESSION['message']['error'][] = 'Keine Laufzeit ausgew&auml;hlt.';
	Header('Location: '.$backurl);
	exit();
}

if(isset($_POST['sans'])) {
	$additional_sans = array();
	foreach($_POST['sans'] as $san) {
		//alle SANs durchgehen, die gefüllt sind
		if(!empty($san)) {
			$additional_sans[] = htmlentities($san);
		}
	}
} else {
	$additional_sans = array();
}

$log = new CsrLogger();

if(UserHelper::IsLoggedIn()) {

	//Datenbankeintrag zum CSR mit neuem Datum Kopieren.
	$dbresult = $db->insert_request(date("Y-m-d H:i:s"), date('Y-m-d H:i:s',strtotime(date("Y-m-d H:i:s", time()) . " + ".ceil(365*$jahre)." day")), $request->country, $request->state, $request->city, $request->organisation_name, $request->common_name, "1", $request->organisation_unit_name, $request->responsible_email, $request->challenge_password, $request->optional_company_name, $request->intermediate, NULL, $request->path_csr, NULL);
    
	//Request ID aus DB Rückgabe holen
	$req_id = $dbresult['id'];
	
	if($req_id == null) {
		//Fehler
		$_SESSION['message']['error'][] = 'Unerwarteter Fehler bei der Zertifikatsbestellung!';
		$log->addError("Unerwarteter Fehler beim Verlängern von CSR ID ".$csr_id.".");
		Header('Location: renewCRT.php?csr='.$csr_id);
		exit();
	}
	else {
		//SANS mit kopieren
		$sans = $db->get_sans_all_where(array("request_id","=","'".$csr_id."'"));
		foreach($sans as $san) {
			$db->insert_sans($req_id, $san->name);
		}
		//Zusätzliche SANs vom Fontend hinzufügen
		foreach($additional_sans as $san) {
			$db->insert_sans($req_id, $san);
		}
	
		$laufzeit_string = ($jahre <= 1) ? ($jahre." Jahr") : ($jahre." Jahre") ;
		$laufzeit_string = ($jahre == 0.25) ? ("&frac14; Jahr") : $laufzeit_string ;
		$laufzeit_string = ($jahre == 0.5) ? ("&frac12; Jahr") : $laufzeit_string ;
		$laufzeit_string = ($jahre == 0.75) ? ("&frac34; Jahr") : $laufzeit_string ;
		$_SESSION['message']['success'][] = 'Der CSR wurde erfolgreich um die gew&uuml;nschte Laufzeit '.$laufzeit_string.' verl&auml;ngert.';
		$log->addNotice("CSR ID ".$csr_id." erfolgreich als CSR ID ".$req_id." verl&auml;ngert.");
		
		//Admins über neuen CSR benachrichtigen
		require_once('./MailHelper.php');
		try {
			send_new_cert_mail_to_admins($req_id);
		} catch(Exception $e) {
  			//trotzdem keine fehlermeldung ausgeben, da sie den Kunden nix angehen
  			//MailHelper sollte schon ins Log geschrieben haben
		} 
			
		Header('Location: viewCSR.php?csr='.$req_id);
		exit();
	}

}
else {
	$_SESSION['message']['warning'][] = 'Bitte loggen Sie sich ein um ein Zertifikat anzufragen!';
	Header('Location: index.php');
	exit();
}

?>