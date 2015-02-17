<?php

$pagetitle_main = "Projekt CA";

require_once('UserHelper.php'); 

$loginform = getLoginForm();

//Fehler & Infomeldung Überprüfung
$messagetext = '';
if(isset($_SESSION['message']['loginerror'])) {
	$messagetext .= '<div class="alert alert-danger  alert-error alert-dismissible" role="alert">
  					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  					<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
  					<span>Der Benutzername oder das Passwort ist falsch.</span>
				   </div>';
	unset($_SESSION['message']['loginerror']);
} 
if(isset($_SESSION['message']['registererror'])) {
	$messagetext .= '<div class="alert alert-danger  alert-error alert-dismissible" role="alert">
  					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  					<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
  					<span>Registrierung fehlgeschlagen. Bitte stellen sie sicher, dass alle Felder ausgef&uuml;llt sind!</span>
				   </div>';
	unset($_SESSION['message']['registererror']);
}  
if(isset($_SESSION['message']['registersuccess'])) {
	$messagetext .= '<div class="alert alert-success alert-dismissible" role="alert">
  					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  					<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
  					<span>Registrierung erfolgreich.</span>
				   </div>';
	unset($_SESSION['message']['registersuccess']);
}  	  

?>

<!DOCTYPE html>
<html lang="de"><head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title><?php echo $pagetitle_main ?> || <?php echo $pagetitle ?></title>
    
    <!-- Web App Deklaration -->
	<meta name="theme-color" content="#000000">
  	<meta name="apple-mobile-web-app-capable" content="yes">
  	<meta name="apple-mobile-web-app-status-bar-style" content="black">
  	<meta name="apple-mobile-web-app-title" content="<?php echo $pagetitle_main ?>">
    
    <!-- Favicon -->
	<link rel="icon" type="image/png" sizes="192x192"  href="./icons/android-icon-192x192.png">
	<link rel="icon" type="image/png" sizes="32x32" href="./icons/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="96x96" href="./icons/favicon-96x96.png">
	<link rel="icon" type="image/png" sizes="16x16" href="./icons/favicon-16x16.png">
	<link rel="icon" type="image/x-icon" href="./icons/favicon.ico" />

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>

    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="index.php"><?php echo $pagetitle_main ?></a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
        
        <?php 
		if(UserHelper::IsLoggedIn()) {
        echo'<ul class="nav navbar-nav">
				<li class="dropdown">
				  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Zertifikate anfordern <span class="caret"></span></a>
				  <ul class="dropdown-menu" role="menu">
					<li><a href="#">Intermediate Zertifikat</a></li>
					<li><a href="#">Standard Zertifikat</a></li>
				  </ul>
				</li>
				<li class="dropdown">
				  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Mein Konto <span class="caret"></span></a>
				  <ul class="dropdown-menu" role="menu">
					<li><a href="#">Meine Zertifikatsanfragen</a></li>
					<li><a href="#">Meine Zertifikate</a></li>
				  </ul>
				</li>
          	</ul>';
        }?>
          
          <?php echo $loginform; ?>
        </div><!--/.navbar-collapse -->
      </div>
    </nav>

      <div class="container" style="margin-top:4.5em;">
      	<!-- Hier stehen eventuelle Fehlermeldungen -->
      	<?php echo $messagetext; ?>
      </div>