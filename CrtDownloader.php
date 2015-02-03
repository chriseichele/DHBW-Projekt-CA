<?php

//Dummy Download aufruf
$download = $_GET['download'];
if(isset($_GET['download'])) {
  //Datei downloaden
  $loader = New CrtDownloader();
  $loader->download($download);
} else {
  //Dummy dowload formular zeigen
  echo'<html>
       <head>
       <title>Downloader</title>
       </head>
       <body>
       <form action="'.$PHP_SELF.'" method="GET">
         <input type="text" name="download" placeholder="File"/>
         <input type="submit" value="download"/>
      </form>
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
		
		//TODO: Lesen von Dateien des Users (Download Bezeichner und Pfad)
		$filelist = array (
				"testzertifikat" => "test.crt",
				"testkey" => "test.key"
		);
		
		//ist gewünschte Download Datei für den erlaubten Dateien des aktuellen Users?
		if (! isset ( $filelist [$download_bezeichner] ))
			die ( "Die Datei \"$download\" ist nicht vorhanden." );
			
		//Download Pfad zusammenbauen
		$filename = sprintf ( "%s/%s", $this->basedir, $filelist[$download_bezeichner] );
		
		//Passenden Datentyp im HTTP Header setzen
		header ( "Content-Type: application/octet-stream" );
		
		//Passenden Dateinamen im Download-Requester vorgeben
		$save_as_name = basename ( $filelist [$download_bezeichner] );
		header ( "Content-Disposition: attachment; filename=\"$save_as_name\"" );
		
		// Datei ausgeben.
		readfile ( $filename );
	}
}
