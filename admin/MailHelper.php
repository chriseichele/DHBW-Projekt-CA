<?php

function send_cert_notification_mail($email, $csr_id) {
	$link = 'wwi12-05.dhbw-heidenheim.de/frontend/viewCSR.php?csr='.$csr_id;
	
	$empfaenger = $email;
	$betreff = 'Projekt CA || Zertifikat genehmigt';
	$nachricht = 'Ihr Zertifikat wurde genehmigt. Sie k&ouml;nnen es unter folgendem Link abrufen: <br/><a href="'.$link.'">'.$link.'</a>';
	$header = 'From: noreply@wwi12-05.dhbw-heidenheim.de' . "\r\n" .
			  'Content-Type: text/html' . "\r\n" .
    		  'X-Mailer: PHP/' . phpversion();

	$send = mail($empfaenger, $betreff, $nachricht, $header);
	
	if(!$send) {
		throw new Exception('Beim Versenden der Benachrichtigungsmail an den Kunden ist ein unerwarteter Fehler aufgetreten!');
		return false;
	} else {
		return true;
	}
}

?>