<?php

require_once('./UserHelper.inc');
require_once('./CrtDownloader.inc');

$backurl = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'index.php#noreferer';
$backurl = (basename($backurl)==basename($_SERVER['SCRIPT_NAME'])) ? 'index.php#backlink' : $backurl;

//Dummy Download aufruf
if(isset($_GET['downloadCSR'])) {
  $download = $_GET['downloadCSR'];
  //Datei downloaden
  try {
    $loader = New CrtDownloader();
    $loader->downloadCSR($download);
  } catch (Exception $e) {
  	$_SESSION['message']['error'][] = $e->getMessage();
	//zurück leiten
	Header('Location: '.$backurl);
  }
} 
elseif(isset($_GET['downloadCRT'])) {
  $download = $_GET['downloadCRT'];
  //Datei downloaden
  try {
    $loader = New CrtDownloader();
    $loader->download($downloadCRT);
  } catch (Exception $e) {
  	$_SESSION['message']['error'][] = $e->getMessage();
	//zurück leiten
	Header('Location: '.$backurl);
  }
} 
else {
	$_SESSION['message']['warning'][] = "Sie haben keine Datei zum Download ausgew&auml;hlt!";
	//zurück leiten
	Header('Location: '.$backurl);
}