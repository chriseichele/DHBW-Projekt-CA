<?php
session_start();

require_once('./LogHelper.php');
$log = new AccountLogger();

// GET Parameter prüfen

if(isset($_GET['code'])) {
	$code = $_GET['code'];
}
else {
	$_SESSION['message']['error'][] = "Aktivierungslink Fehlerhaft! Bitte &uuml;berpr&uuml;fen Sie, ob sie diesen korrekt eingegeben haben. (Aktivierungscode fehlt)";
	header('Location: index.php');
	exit();
}

// User von DB holen

require_once('./db.php');
$db = new DBAccess();
$where = array("activation_code","=","'".$code."'");
$user = $db->get_user_all_where($where);
$u = reset($user);

// Code Prüfen

if($u->activation_code == $code) {
	//User aktivieren
	$dbresult = $db->update_user_activation_code($where, array(null));
	if($dbresult['affected_rows'] == 1) {
		$_SESSION['message']['success'][] = "Ihr Account wurde erfolgreich aktiviert. Sie k&ouml;nnen sich jetzt einloggen.";
		$log->addNotice("Useraccount &lt;".$u->email."&gt; erfolgreich aktiviert.");
		header('Location: index.php');
		exit();
	}
	else {
		$_SESSION['message']['error'][] = "Aktivierung fehlgeschlagen! Bitte kontaktieren sie uns.";
		$log->addError("Useraccount &lt;".$u->email."&gt; nicht aktiviert.");
		header('Location: index.php');
		exit();
	}
}
else {
	$_SESSION['message']['error'][] = "Aktivierungslink Fehlerhaft! Bitte &uuml;berpr&uuml;fen Sie, ob sie diesen korrekt eingegeben haben. (Code fehlerhaft oder Account bereits aktiviert)";
	header('Location: index.php');
	exit();
}

?>
