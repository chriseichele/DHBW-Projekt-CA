<?php

$pagetitle_main = "Projekt CA Admin";

require_once('UserHelper.php'); 
require_once('MessageHandler.php');

$loginform = getLoginForm();

$messagetext = getMessages();	  

?>

<!DOCTYPE html>
<html lang="de"><head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title><?php echo $pagetitle_main ?> || <?php echo $pagetitle ?></title>
    
    <!-- Web App Deklaration -->
	<meta name="theme-color" content="#600000">
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

    <nav class="navbar navbar-inverse navbar-fixed-top" style="background-color:#600000;">
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
		if(UserHelper::IsAdminLoggedIn()) {
        echo'<ul class="nav navbar-nav">
				<li><a href="openCSRlist.php" title="Offene Zertifikatsanfragen bearbeiten">Offene CSRs</a></li>
				<li><a href="validatedCSRlist.php" title="Von mir bearbeitete Zertifikatsanfragen">Bearbeitete CSRs</a></li>
				<li><a href="info.php" title="Datenbank- und Session Informationssystem">Info</a></li>
				<li><a href="filelists.php" title="Kontrolliste des Dateisystems von CSR und CRT Dateien">Dateisystem</a></li>
          	</ul>';
        }
        else?>
          
          <?php echo $loginform; ?>
        </div><!--/.navbar-collapse -->
      </div>
    </nav>

      <div class="container" style="margin-top:4.5em;">
      	<!-- Hier stehen eventuelle Meldungen -->
      	<?php echo $messagetext; ?>
      </div>