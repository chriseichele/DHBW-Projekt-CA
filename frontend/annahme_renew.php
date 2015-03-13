<?php
SESSION_START();
require_once('./UserHelper.php');
require_once('./db.php');

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

if(isset($_POST['zerttype'])){
	$zerttype = $_POST['zerttype'];
}
else {
	$_SESSION['message']['error'][] = 'Kein Zertifikatstyp gesetzt.';
	Header('Location: '.$backurl);
	exit();
}

if(isset($_POST['laufzeit'])){
	$laufzeit = $_POST['laufzeit'];
}
else {
	$_SESSION['message']['error'][] = 'Keine Laufzeit ausgew&auml;hlt.';
	Header('Location: '.$backurl);
	exit();
}

if($zerttype == 'intermediate') {
	if($laufzeit == '3') {
		$jahre = 3;
	}
	elseif($laufzeit == '5') {
		$jahre = 5;
	}
	elseif($laufzeit == '10') {
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
	if($laufzeit == '1') {
		$jahre = 1;
	}
	elseif($laufzeit == '3') {
		$jahre = 3;
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
	$_SESSION['message']['error'][] = 'Ung&uuml;ltiger Zertifikatstyp.';
	Header('Location: '.$backurl);
	exit();
}



if(UserHelper::IsLoggedIn()) {

	//Datenbankeintrag zum CSR mit neuem Datum Kopieren.
	$dbresult = $db->insert_request(date("Y-m-d H:i:s"), date('Y-m-d H:i:s',strtotime(date("Y-m-d H:i:s", time()) . " + ".(365*$jahre)." day")), $request->country, $request->state, $request->city, $request->organisation_name, $request->common_name, "1", $request->organisation_unit_name, $request->responsible_email, $request->challenge_password, $request->optional_company_name, $request->intermediate, NULL, $request->path_csr, NULL);
    
	//Request ID aus DB Rückgabe holen
	$req_id = $dbresult['id'];
	
	if($req_id == null) {
		//Fehler
		$_SESSION['message']['error'][] = 'Unerwarteter Fehler bei der Zertifikatsbestellung!';
		Header('Location: renewCRT.php?csr='.$csr_id);
		exit();
	}
	else {
		//SANS mit kopieren
		$sans = $db->get_sans_all_where(array("request_id","=","'".$csr_id."'"));
		foreach($sans as $san) {
			$db->insert_sans($csr_id,$san->name);
		}
	
		$laufzeit_string = ($jahre <= 1) ? ($jahre." Jahr") : ($jahre." Jahre") ;
		$_SESSION['message']['success'][] = 'Der CSR wurde erfolgreich um die gew&uuml;nschte Laufzeit '.$laufzeit_string.' verl&auml;ngert.';
		
		//Admins über neue Datei benachrichtigen
		require_once('./MailHelper.php');
		try {
			send_new_cert_mail_to_admins($csr_id);
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