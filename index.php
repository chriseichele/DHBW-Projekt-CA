<?php

if(!isset($_REQUEST['noskip'])) {
	header('Location: ./frontend/');
	exit();
}

?>

<!DOCTYPE html>
<html lang="de">
	<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	
	<title>Projekt CA</title>
    
    <!-- Web App Deklaration -->
	<meta name="theme-color" content="#202020">
  	<meta name="apple-mobile-web-app-capable" content="yes">
  	<meta name="apple-mobile-web-app-status-bar-style" content="black">
  	<meta name="apple-mobile-web-app-title" content="Projekt CA">
    
    <!-- Favicon -->
	<link rel="icon" type="image/png" sizes="192x192"  href="android-icon-192x192.png">
	<link rel="icon" type="image/png" sizes="32x32" href="favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="96x96" href="favicon-96x96.png">
	<link rel="icon" type="image/png" sizes="16x16" href="favicon-16x16.png">
	<link rel="icon" type="image/x-icon" href="favicon.ico" />
	
	<style>
		html, body {
			background: #222;
			padding: 0 10px;
			margin: 0;
			width: calc(100% - 20px);
			height: 100%;
			font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;
		}
		h1 {
			text-align: center;
			margin-top: 2.5em;
			margin-bottom: 0.2em;
			color: #fff;
		}
		h1 + p {
			margin-bottom: 4em;
			color: white;
			text-align: center;
		}
		div {
			width:50%;
			float: left;
			text-align: center;
		}
		a {
			text-decoration: none;
			border: none;
		}
		a.kunde {
			color: #009600;
		}
		a.admin {
			color: #960000;
		}
		a.kunde:hover,
		a.admin:hover {
			color: white;
		}
		span {
			display: block;
			text-align: center;
		}
		span.h {
			font-size: 1.8em;
			font-weight: 500;
			padding-top: 10px;
		}
		img {
			margin: 10px auto;
			max-width: 90%;
			max-height: 90%;
			border: none;
		}
	</style>
	</head>
	<body>
		<h1>Projekt CA</h1>
		<p>DHBW Studiengang Wirtschaftsinformatik 2012 IT Sicherheit - Gruppe 3</p>
		<div>
			<a href="./frontend/" class="kunde" title="Kunden Portal">
				<span><img src="./frontend/icons/android-icon-192x192.png"></span>
				<span class="h">Kunde</span>
			</a>
		</div>
		<div>
			<a href="./admin/" class="admin" title="Administratoren Portal">
				<span><img src="./admin/icons/android-icon-192x192.png"></span>
				<span class="h">Admin</span>
			</a>
		</div>
	</body>
</html>