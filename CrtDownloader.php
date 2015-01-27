<?php

//Dummy Download aufruf
$download = $_GET ['download'];
$loader = New CrtDownloader();
$loader->download();

/* Downloader Klasse fuer CRT-Files */
class CrtDownloader {
	private $basedir;
	
	public function __construct() {
		//Variablen Initialisierung
		$basedir = "/home/www/download"; //Verzeichnis außerhalb des Document Root (nicht per Web-URL ereichbar)
	}
	
	public function download($download) {
		
		//TODO: Angemeldeten User prüfen
		
		//TODO: Lesen von Dateien des Users
		// Übersetzung von Download-Bezeichner in Dateinamen.
		$filelist = array (
				"file1" => "area1/datei1.zip",
				"file2" => "area1/datei2.zip",
				"file3" => "area2/datei1.zip" 
		);
		
		//TODO: ist gewünschte Download Datei für den aktuellen User erlaubt?
		// Einbruchsversuch abfangen.
		if (! isset ( $filelist [$download] ))
			die ( "Datei $download nicht vorhanden." );
			
		//TODO: Download Pfad ermitteln
		// Vertrauenswuerdigen Dateinamen basteln.
		$filename = sprintf ( "%s/%s", $basedir, $filelist [$download] );
		
		//TODO: Datentyp im Header setzen
		// Passenden Datentyp erzeugen.
		header ( "Content-Type: application/octet-stream" );
		
		//TODO: Dateinamen im Downloader setzen
		// Passenden Dateinamen im Download-Requester vorgeben,
		// z. B. den Original-Dateinamen
		$save_as_name = basename ( $filelist [$download] );
		header ( "Content-Disposition: attachment; filename=\"$save_as_name\"" );
		
		// Datei ausgeben.
		readfile ( $filename );
	}
}
