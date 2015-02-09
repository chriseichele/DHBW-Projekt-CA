<?php

//Dummy Download aufruf
if(isset($_GET['download'])) {
  $download = $_GET['download'];
  //Datei downloaden
  try {
    $loader = New CrtDownloader();
    $loader->download($download);
  } catch (Exception $e) {
    echo 'Exception abgefangen: ',  $e->getMessage(), "\n";
  }
} else {
  //Dummy download formular zeigen
  echo'<html>
       <head>
       <title>Downloader</title>
       </head>
       <body>
       <form action="'.$_SERVER['PHP_SELF'].'" method="GET">
         <input type="text" name="download" placeholder="File"/>
         <input type="submit" value="download"/>
      </form>
      '.CrtDownloader::getUserFileList().'
      </body>
      </html>';
}

/* Downloader Klasse fuer CRT-Files */

class CrtDownloader {
	private $basedir;
	
	public function __construct() {
		//Variablen Initialisierung
		$this->basedir = "../../download"; //Verzeichnis außerhalb des Document Root (nicht per Web-URL ereichbar)
	}
	
	public function download($download_bezeichner) {
	
		//TODO: Angemeldeten User prüfen
		$user = CrtDownloader::getUser();
		
		//Lesen von Dateien des Users
		$filelist = CrtDownloader::getUserFiles($user);
		
		//ist gewünschte Download Datei für den erlaubten Dateien des aktuellen Users?
		if (! isset ( $filelist[$download_bezeichner] )) throw new Exception("Die Datei \"$download_bezeichner\" ist nicht vorhanden!");
			
		//Download Pfad zusammenbauen
		$filename = sprintf ( "%s/%s", $this->basedir, $filelist[$download_bezeichner] );
		if (! file_exists($filename) ) throw new Exception("Die Datei \"$download_bezeichner\" existiert nicht!"); 
		
		//Passenden Datentyp im HTTP Header setzen
		header ( "Content-Type: application/octet-stream" );
		
		//Passenden Dateinamen im Download-Requester vorgeben
		$save_as_name = basename ( $filelist [$download_bezeichner] );
		header ( "Content-Disposition: attachment; filename=\"$save_as_name\"" );
		
		// Datei ausgeben.
		readfile ( $filename );
	}
	
	private static function getUser() {
	    //TODO: get current User
		// throw new Exception("Kein User angemeldet!");
		return "User";
	}
	
	private static function getUserFiles($username) {
		//TODO: Dateien des Users von der Datenbank abfragen
		
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
