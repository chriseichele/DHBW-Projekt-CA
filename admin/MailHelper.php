<?php

require_once('./LogHelper.php');

function send_cert_notification_mail($email, $csr_id) {
	$link = 'https://wwi12-05.dhbw-heidenheim.de/frontend/viewCSR.php?csr='.$csr_id;

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

	$mail->Subject = 'Projekt CA || Zertifikat genehmigt';
	$mail->Body    = '<p>Sehr geehrter Kunde,</p><p>Ihr Zertifikat wurde genehmigt.</p><p>Sie k&ouml;nnen es unter folgendem Link abrufen: <br/><a href="'.$link.'">'.$link.'</a></p>';
	$mail->AltBody = 'Ihr Zertifikat wurde genehmigt. Sie können es unter folgendem Link abrufen: '.$link;

	if(!$mail->send()) {
		$log = new MailLogger();
		$log->addError('Mailer Error: ' . $mail->ErrorInfo . ' bei Benachrichtigungsmail (Zertifikat ID '.$csr_id.' genehmigt) an den Kunden &lt;'.$email.'&gt;');
		throw new Exception('Beim Versenden der Benachrichtigungsmail (Zertifikat ID '.$csr_id.' genehmigt) an den Kunden &lt;'.$email.'&gt; ist ein unerwarteter Fehler aufgetreten!');
		return false;
	} else {
		$log = new MailLogger();
		$log->addNotice('Mail (Zertifikat ID '.$csr_id.' genehmigt) erfolgreich versendet an: &lt;'.$email.'&gt;');
		return true;
	}
}

function send_cert_abbortion_mail($email, $csr_id, $reason) {
	$link = 'https://wwi12-05.dhbw-heidenheim.de/frontend/viewCSR.php?csr='.$csr_id;

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

	$mail->Subject = 'Projekt CA || Zertifikat leider abgelehnt';
	$mail->Body    = '<p>Sehr geehrter Kunde,</p><p>Ihr Zertifikat wurde leider abgelehnt.</p><p><strong>Begr&uuml;ndung:</strong><br/>"'.$reason.'"</p><p>Sie k&ouml;nnen ihre Anfrage unter folgendem Link nochmal anschauen: <br/><a href="'.$link.'">'.$link.'</a></p><p>Bitte senden Sie uns eine neue Anfrage.</p>';
	$mail->AltBody = 'Ihr Zertifikat wurde leider abgelehnt. Begr&uuml;ndung: "'.$reason.'" Sie können ihre Anfrage unter folgendem Link nochmal anschauen: '.$link.'  Bitte senden Sie und eine neue Anfrage.';

	if(!$mail->send()) {
		$log = new MailLogger();
		$log->addError('Mailer Error: ' . $mail->ErrorInfo . ' bei Benachrichtigungsmail (Zertifikat ID '.$csr_id.' abgelehnt) an den Kunden &lt;'.$email.'&gt;');
		throw new Exception('Beim Versenden der Benachrichtigungsmail (Zertifikat ID '.$csr_id.' abgelent) an den Kunden &lt;'.$email.'&gt; ist ein unerwarteter Fehler aufgetreten!');
		return false;
	} else {
		$log = new MailLogger();
		$log->addNotice('Mail (Zertifikat ID '.$csr_id.' abgelehnt) erfolgreich versendet an: &lt;'.$email.'&gt;');
		return true;
	}
}

?>
