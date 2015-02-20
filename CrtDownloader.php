<?php

require_once('./UserHelper.inc');

$backurl = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'index.php#noreferer';
$backurl = (basename($backurl)==basename($_SERVER['SCRIPT_NAME'])) ? 'index.php#backlink' : $backurl;

//Dummy Download aufruf
if(isset($_GET['download'])) {
  $download = $_GET['download'];
  //Datei downloaden
  try {
    $loader = New CrtDownloader();
    $loader->download($download);
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

/* Downloader Klasse fuer CRT-Files */

class CrtDownloader {
	private $basedir;
	
	public function __construct() {
		//Variablen Initialisierung
		$this->basedir = "../../download"; //Verzeichnis außerhalb des Document Root (nicht per Web-URL ereichbar)
	}
	
	public function download($download_bezeichner) {
	
		//Angemeldeten User prüfen/holen
		$user = CrtDownloader::getUser();
		
		//Lesen von Dateien des Users
		$filelist = CrtDownloader::getUserFiles($user);
		
		//ist gewünschte Download Datei in den erlaubten Dateien des aktuellen Users?
		if (! isset ( $filelist[$download_bezeichner] )) throw new Exception("Die Datei \"$download_bezeichner\" ist nicht vorhanden!");
			
		//Download Pfad zusammenbauen
		$filename = sprintf ( "%s/%s", $this->basedir, $filelist[$download_bezeichner] );
		if (! file_exists($filename) ) throw new Exception("Unerwarteter Fehler beim herunterladen der Datei \"$download_bezeichner\". Bitte nehmen Sie Kontakt zu uns auf!"); 
		
		//Passenden Datentyp im HTTP Header setzen
		header ( "Content-Type: application/octet-stream" );
		
		//Passenden Dateinamen im Download-Requester vorgeben
		$save_as_name = basename ( $filelist [$download_bezeichner] );
		header ( "Content-Disposition: attachment; filename=\"$save_as_name\"" );
		
		// Datei ausgeben.
		readfile ( $filename );
	}
	
	private static function getUser() {
		$email = UserHelper::GetUserEmail();
		if (!empty($email)) {
			return $email;
		}
		else {
			throw new Exception("Sie sind nicht angemeldet!");
		}
	}
	
	private static function getUserFiles($email) {
		//Dateien des Users von der Datenbank abfragen
		$db = new DBAccess();
		//$db->get_request_all_where(array());
		
		//TODO: Lesen von Dateien des Users (Download Bezeichner und Pfad)
		$files = array (
				"testzertifikat" => "test.crt",
				"testkey" => "test.key"
		);
		
		//-> return der dateien als Array
		return $files;
	}
	
	public static function getUserFileList() {
		$files = CrtDownloader::getUserFiles(CrtDownloader::getUser());
		
		$out = "<ul>";
		foreach($files as $name => $path) {
			$out .= "<li><a href=\"CrtDownloader.php?download=$name\">$name</a></li>";
		}
		$out .= "</ul>";
		
		return $out;
	}
}
