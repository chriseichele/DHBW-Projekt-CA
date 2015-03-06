<?php
session_start();

// GET Parameter prüfen

if(isset($_GET['code'])) {
	$code = $_GET['code'];
}
else {
	$_SESSION['message']['error'][] = "Aktivierungslink Fehlerhaft! Bitte &uuml;berpr&uuml;fen Sie, ob sie diesen korrekt eingegeben haben. (Aktivierungscode fehlt)";
	header('Location: index.php');
	exit();
}

if(isset($_GET['email'])) {
	$email = $_GET['email'];
}
else {
	$_SESSION['message']['error'][] = "Aktivierungslink Fehlerhaft! Bitte &uuml;berpr&uuml;fen Sie, ob sie diesen korrekt eingegeben haben. (Email fehlt)";
	header('Location: index.php');
	exit();
}

// User von DB holen

require_once('./db.php');
$db = new DBAccess();
$where = array("email","=","'".$email."'");
$user = $db->get_user_all_where($where);
$u = reset($user);

// Code Prüfen

if($u->email == $email) {
	if($u->activision_code == $code) {
		//User aktivieren
		$dbresult = $db->update_user_code($email, null);
		if($dbresult['affected_rows'] == 1) {
			$_SESSION['message']['success'][] = "Ihr Account wurde erfolgreich aktiviert. Sie k&ouml;nnen sich jetzt einloggen.";
			header('Location: index.php');
			exit();
		}
		else {
			$_SESSION['message']['error'][] = "Aktivierungslink fehlgeschlagen! Bitte kontaktieren sie uns.";
			header('Location: index.php');
			exit();
		}
	}
	else {
		$_SESSION['message']['error'][] = "Aktivierungslink Fehlerhaft! Bitte &uuml;berpr&uuml;fen Sie, ob sie diesen korrekt eingegeben haben. (Email oder Code fehlerhaft)";
		header('Location: index.php');
		exit();
	}
}
else {
	$_SESSION['message']['error'][] = "Aktivierungslink Fehlerhaft! Bitte &uuml;berpr&uuml;fen Sie, ob sie diesen korrekt eingegeben haben. (Email oder Code fehlerhaft)";
	header('Location: index.php');
	exit();
}

?>