<?php 

include('UserHelper.inc');

doAdminRightsCheck();

$backurl = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'openCSRlist.php';
$backurl = (basename($backurl)=='zertifikatsanfragen.php') ? $backurl : 'openCSRlist.php';

if(isset($_POST['accept'])) {
	$accept = $_POST['accept'];
	if($accept == 'true') {
		$accept = true;
	}
	elseif($accept == 'false') {
		$accept = false;
	}
	else {
		$accept = null;
  		$_SESSION['message']['warning'][] = "Bitte w&auml;hlen Sie eine g&uuml;ltige Aktion!";
  		header('Location: '.$backurl);
  		exit();
	}
} else {
  	$_SESSION['message']['warning'][] = "Bitte w&auml;hlen Sie eine Aktion!";
  	header('Location: '.$backurl);
  	exit();
}

if(isset($_POST['csr'])) {
	$csr_id = $_POST['csr'];
} else {
  	$_SESSION['message']['warning'][] = "Bitte w&auml;hlen Sie einen CSR aus!";
  	header('Location: '.$backurl);
  	exit();
}


$db = new DBAccess();
$where = array("id","=","'".$csr_id."'");
$db_result = $db->get_request_all_where($where);
$csr = reset($db_result);

if($csr->status != 'created') {
  	$_SESSION['message']['error'][] = "CSR wurde bereits bearbeitet!";
  	header('Location: '.$backurl);
  	exit();
} 
else {
	if($accept) {
		$new_status = 2;
	}
	else {
		$new_status = 4;
	}
	$result = $db->update_request_status($where, $new_status);
	if($result['affected_rows'] == 1) {
		if($accept) {
  			$_SESSION['message']['success'][] = "CSR wurde erfolgreich genehmigt!";
  			try {
  				$success = true; //TODO Zertifikat generieren
  				if($success) {
  					$_SESSION['message']['success'][] = "Zertifikat wurde erfolgreich erstellt!";
  				else {
  					$_SESSION['message']['error'][] = "Unerwarteter Fehler beim erstellen des Zertifikats!";
  				}
  			}
  			catch (Exception $e) {
  				$_SESSION['message']['error'][] = $e->getMessage();
  			}
  		}
  		else {
  			$_SESSION['message']['success'][] = "CSR wurde abgelehnt!";
  		}
	}
	elseif($result['affected_rows'] < 1) {
  		$_SESSION['message']['error'][] = "Fehler bei der Aktualisierung des Status!";
	}
	else {
  		$_SESSION['message']['error'][] = "ACHTUNG: Es wurden mehr Eintr&auml;ge als beabsichtigt aktualisiert!";
	}
	echo'<pre>';
  	header('Location: openCSRlist.php');
}