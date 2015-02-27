<!DOCTYPE html>
<html lang="de">
	<head>
	<title>Projekt CA</title>
	<style>
		html, body {
			background: #222;
			padding: 0;
			margin: 0;
			width: 100%;
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
			font-size: 2em;
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
			<a href="./frontend/" class="kunde">
				<span><img src="./frontend/icons/android-icon-192x192.png"></span>
				<span class="h">Kunde</span>
			</a>
		</div>
		<div>
			<a href="./admin/" class="admin">
				<span><img src="./admin/icons/android-icon-192x192.png"></span>
				<span class="h">Administrator</span>
			</a>
		</div>
	</body>
</html>