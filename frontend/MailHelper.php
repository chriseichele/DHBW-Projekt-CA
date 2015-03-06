<?php

function send_activision_mail($email, $code) {
	$activision_link = 'wwi12-05.dhbw-heidenheim.de/frontend/activate.php?email='.$email.'&code='.$code;
	
	$empfaenger = $email;
	$betreff = 'Projekt CA || Account Aktivierung';
	$nachricht = 'Bitte klicken sie folgenden Link an, um ihren Account zu aktivieren: <br/><a href="'.$activision_link.'">'.$activision_link.'</a>';
	$header = 'From: noreply@wwi12-05.dhbw-heidenheim.de' . "\r\n" .
			  'Content-Type: text/html' . "\r\n" .
    		  'X-Mailer: PHP/' . phpversion();

	$send = mail($empfaenger, $betreff, $nachricht, $header);
	
	if(!$send) {
		throw new Exception('Beim Versenden der Aktivierungsmail ist ein unerwarteter Fehler aufgetreten! Bitte kontaktieren Sie uns.');
		return false;
	} else {
		return true;
	}
}

?>