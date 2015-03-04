<?php

function send_cert_notification_mail($email, $csr_id) {
	$link = 'wwi12-05.dhbw-heidenheim.de/frontend/viewCSR.php?csr='.$csr_id;

	require_once('../PHP-Mailer/PHPMailerAutoload.php');
	
	$mail = new PHPMailer;

	//$mail->SMTPDebug = 3;                               // Enable verbose debug output

	$mail->isSMTP();                                      // Set mailer to use SMTP
	$mail->Host = 'smtp1.example.com;smtp2.example.com';  // Specify main and backup SMTP servers
	$mail->SMTPAuth = true;                               // Enable SMTP authentication
	$mail->Username = 'user@example.com';                 // SMTP username
	$mail->Password = 'secret';                           // SMTP password
	$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
	$mail->Port = 587;                                    // TCP port to connect to

	$mail->From = 'noreply@wwi12-05.dhbw-heidenheim.de';
	$mail->FromName = 'Projekt CA';
	$mail->addAddress($email);

	$mail->isHTML(true);                                  // Set email format to HTML

	$mail->Subject = 'Account Verifizierungs-Mail';
	$mail->Body    = 'Ihr Zertifikat wurde genehmigt. Sie k&ouml;nnen es unter folgendem Link abrufen: <br/><a href="'.$link.'">'.$link.'</a>';
	$mail->AltBody = 'Ihr Zertifikat wurde genehmigt. Sie können es unter folgendem Link abrufen: $link';

	if(!$mail->send()) {
		throw new Exception('Mailer Error: ' . $mail->ErrorInfo);
		return false;
	} else {
		return true;
	}
}

?>