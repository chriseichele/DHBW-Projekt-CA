<?php

if(isset($_GET['download'])) {

	$download = $_GET['download'];

	if($download == "ca") {
		$path = "c:\apache24\ca\ca.crt";
	}
	elseif($download == "ica") {
		$path = "c:\apache24\ca\ica.crt";
	}
	else {
		$path = NULL;
	}
	if(!empty($path)) {
		//Passenden Datentyp im HTTP Header setzen
		header ( "Content-Type: application/octet-stream" );
		//Passenden Dateinamen im Download-Requester vorgeben
		$save_as_name = basename ( $path );
		header ( "Content-Disposition: attachment; filename=\"$save_as_name\"" );
		// Datei ausgeben.
		readfile ( $path );
	} else {
  		$_SESSION['message']['warning'][] = "Ung&uuml;ltiger Download!";
  		header('Location: '.$_SERVER['PHP_SELF']);
  		exit();
	}

} else {

	$pagetitle = 'Projekt CA Zertifikate';

	include('./header.php');
	
	echo' <div class="jumbotron">
		<div class="container">
			<h1>'. $pagetitle. '</h1>
			<p>Hier k&ouml;nnen sie unsere Zertifikate herunterladen.</p>
		</div>
	</div>
	<div class="container">
		<a href="'.$_SERVER['PHP_SELF'].'?download=ca" class="btn btn-success btn-lg btn-block" role="button">Root Zertifikat</a>
		<a href="'.$_SERVER['PHP_SELF'].'?download=ica" class="btn btn-success btn-lg btn-block" role="button">Intermediate Zertifikat</a>
	</div>';

	include('./footer.php'); 

}

?>