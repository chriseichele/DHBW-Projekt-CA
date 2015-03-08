<?php

function send_activision_mail($email, $code) {
	$activision_link = 'wwi12-05.dhbw-heidenheim.de/frontend/activate.php?email='.urlencode($email).'&code='.urlencode($code);

	require_once('../PHP-Mailer/PHPMailerAutoload.php');
	
	$mail = new PHPMailer;

	//$mail->SMTPDebug = 3;                                    // Enable verbose debug output

	$mail->isSMTP();                                           // Set mailer to use SMTP
	$mail->Host = 'wwi12-05.dhbw-heidenheim.de';  		       // Specify main and backup SMTP servers
	$mail->SMTPAuth = true;                                    // Enable SMTP authentication
	$mail->Username = 'no-reply@wwi12-05.dhbw-heidenheim.de';  // SMTP username
	$mail->Password = 'Himmel12';                              // SMTP password
	$mail->SMTPSecure = 'tls';                                 // Enable TLS encryption, `ssl` also accepted
	$mail->Port = 587;                                         // TCP port to connect to

	$mail->From = 'no-reply@wwi12-05.dhbw-heidenheim.de';
	$mail->FromName = 'Projekt CA';
	$mail->addAddress($email);

	$mail->isHTML(true);                                       // Set email format to HTML

	$mail->Subject = 'Projekt CA || Account Verifizieren';
	$mail->Body    = 'Bitte klicken sie folgenden Link an, um ihren Account zu aktivieren: <br/><a href="'.$activision_link.'">'.$activision_link.'</a>';
	$mail->AltBody = 'Bitte Kopieren sie folgenden Link in ihre Browserzeile um ihren Account zu aktivieren: $activision_link';

	if(!$mail->send()) {
		//throw new Exception('Mailer Error: ' . $mail->ErrorInfo);
		throw new Exception('Beim Senden der Account Aktivierungsmail ist ein unerwarteter Fehler aufgetreten. Bitte kontaktieren Sie uns!');
		return false;
	} else {
		return true;
	}
}

?>