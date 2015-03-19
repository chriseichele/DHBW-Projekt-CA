<?php 

require_once('./UserHelper.php');
require_once('./LogHelper.php');

//User Eingeloggt?
doUserRightsCheck();


if(isset($_POST['csr'])) {
	$csr_id = $_POST['csr'];
} else {
  	$_SESSION['message']['warning'][] = "Bitte w&auml;hlen Sie einen CSR aus!";
  	header('Location: mycsr.php');
  	exit();
}
$db = new DBAccess();
$where = array("id","=","'".$csr_id."'");
$dbresult = $db->get_request_all_where($where);
$csrs = reset($dbresult);
$csr = get_object_vars($csrs);

if($dbresult == array()) {
	//Kein CSR gefunden
  	$_SESSION['message']['warning'][] = "Der gew&auml;hle CSR ist nicht vorhanden oder Sie sind nicht berechtigt!";
  	header('Location: mycsr.php');
  	exit();
}

//Mein Request?
$email = UserHelper::GetUserEmail();
if($csr['requester'] == $email) {
	$myrequest = true;
}
else {
	$myrequest = false;
  	$_SESSION['message']['warning'][] = "Der gew&auml;hle CSR ist nicht vorhanden oder Sie sind nicht berechtigt!";
  	header('Location: mycsr.php');
  	exit();
}

//CSR Status created?
if($csr['status'] == "created") {
	//Alles OK
}
else {
  	$_SESSION['message']['warning'][] = "Der gew&auml;hle CSR kann nicht gel&ouml;scht werden!";
  	header('Location: viewCSR.php?csr='.$csr_id);
  	exit();
}

$where = array("request_id","=","'".$csr_id."'");
$sans = $db->get_sans_all_where($where);

$log = new CsrLogger();

//Löschen vornehmen

//CSR Datei Löschen
//Zuerst prüfen, ob nicht ein anderer CSR mit der Datei existiert!
$where = array("path_csr","=","'".$csr['path_csr']."'");
$csr_with_same_path = $db->get_request_all_where($where);
if(count($csr_with_same_path) <= 1) {
	unlink($csr['path_csr']);
	$log->addNotice('CSR-Datei zu CSR ID '.$csr_id.' gel&ouml;scht.');
}
else {
  	$_SESSION['message']['warning'][] = "CSR Datei wurde nicht gel&ouml;scht, da sie noch von einem anderen Ihrer CSRs ben&ouml;tigt wird (Durch die Verl&auml;ngern Funktion)!";
}

//SANS Löschen
foreach($sans as $san) {
	$db->delete_sans($san->request_id, $san->name);
}

//CSR Löschen
$result = $db->delete_request($csr_id);
if($result != array()) {
	$log->addError('DB-Eintrag zu CSR ID '.$csr_id.' nicht gel&ouml;scht. Fehler: '.$result[0]);
	$_SESSION['message']['error'][] = 'Fehler beim L&ouml;schen des CSRs!';
  	header('Location: viewCSR.php?csr='.$csr_id);
  	exit();
}
else {
	//Erfolg
	$log->addNotice('DB-Eintrag zu CSR ID '.$csr_id.' gel&ouml;scht.');
	$_SESSION['message']['success'][] = 'Der CSR "'.$csr['common_name'].'" wurde erfolgreich gel&ouml;scht!';
	header('Location: mycsr.php');
	exit();
}

?>