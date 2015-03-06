<?php 

include('UserHelper.php');

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
		$result = $db->update_request_verifier($where, UserHelper::GetUserEmail());
		if($result['affected_rows'] == 1) {
			if($accept) {
				try {
					//Zertifikat generieren
					$success = false;
					require_once('./generateCRT.php');
					$success = createCertificate($csr_id);
					if($success) {
						$_SESSION['message']['success'][] = "Zertifikat wurde erfolgreich erstellt! <a href='viewCSR.php?csr=".$csr_id."'>Aktualisierte Zertifikatsanfrage anzeigen</a>";
						
						//Mail an User schicken und ihn informieren
						require_once('./MailHelper.php');
						try {
							send_cert_notification_mail(UserHelper::GetUserEmail(), $csr_id);
						} catch(Exception $e) {
							$_SESSION['message']['error'][] = $e->getMessage();
						}
					}
					else {
						//Falls ein Fehler ohne Exception Auftritt, sollte aber nicht vorkommen
						$_SESSION['message']['error'][] = "Unerwarteter Fehler beim Erstellen des Zertifikats, bitte manuell nachbessern!";
					}
				}
				catch (Exception $e) {
					//Exceptions bei Zertifikat erstellen darstellen
					$_SESSION['message']['error'][] = $e->getMessage();
					//Status wieder auf inital setzten, sodass es wieder bearbeitet werden kann
					$result = $db->update_request_status($where, 1);
					//Verifier wieder zurück setzten
					$result = $db->update_request_verifier($where, null);
					$_SESSION['message']['warning'][] = "CSR wurde wieder zur&uuml;ck gesetzt!</a>";
  					header('Location: zertifikatsanfragen.php?csr='.$csr_id);
  					exit();
				}
			}
			else {
				$_SESSION['message']['success'][] = "CSR wurde abgelehnt! <a href='viewCSR.php?csr=".$csr_id."'>Aktualisierte Zertifikatsanfrage anzeigen</a>";
			}
  		}
		elseif($result['affected_rows'] < 1) {
  			$_SESSION['message']['error'][] = "Fehler bei der Aktualisierung des Verifiers f&uuml;r CSR ID \"".$csr_id."\"!";
  			//Versuche Status zurück zu setzen
			$result = $db->update_request_status($where, 1);
			if($result['affected_rows'] < 1) {
  				$_SESSION['message']['error'][] = "ACHTUNG: Der Status wurde bereits aktualisiert!";
			} 
			elseif($result['affected_rows'] == 1) {
				//Status erfolgreich zurück gesetzt
			}
			else {
  				$_SESSION['message']['error'][] = "ACHTUNG: Der Status wurde f&uuml;r zu viele Einträge zur&uuml;ck auf \"created\" gesetzt!";
			}
		}
		else {
			//Sollte nicht eintreten
  			$_SESSION['message']['error'][] = "ACHTUNG: Es wurde der Verifier &fuuml;r mehr Eintr&auml;ge als beabsichtigt aktualisiert!<br/>Der Status ist bereits aktualisiert! CSR ID \"".$csr_id."\".";
		}
	}
	elseif($result['affected_rows'] < 1) {
  		$_SESSION['message']['error'][] = "Fehler bei der Aktualisierung des Status f&uuml;r CSR ID \"".$csr_id."\"!";
	}
	else {
		//Sollte nicht eintreten
  		$_SESSION['message']['error'][] = "ACHTUNG: Es wurde der Status f&uuml;r mehr Eintr&auml;ge als beabsichtigt aktualisiert! CSR ID \"".$csr_id."\".";
	}
  	header('Location: openCSRlist.php');
  	exit();
}