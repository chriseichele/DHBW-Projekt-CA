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
		
		// Übersetzung von Download-Bezeichner in Dateinamen.
		$filelist = array (
				"file1" => "area1/datei1.zip",
				"file2" => "area1/datei2.zip",
				"file3" => "area2/datei1.zip" 
		);
		
		// Einbruchsversuch abfangen.
		if (! isset ( $filelist [$download] ))
			die ( "Datei $download nicht vorhanden." );
			
		// Vertrauenswuerdigen Dateinamen basteln.
		$filename = sprintf ( "%s/%s", $basedir, $filelist [$download] );
		
		// Passenden Datentyp erzeugen.
		header ( "Content-Type: application/octet-stream" );
		
		// Passenden Dateinamen im Download-Requester vorgeben,
		// z. B. den Original-Dateinamen
		$save_as_name = basename ( $filelist [$download] );
		header ( "Content-Disposition: attachment; filename=\"$save_as_name\"" );
		
		// Datei ausgeben.
		readfile ( $filename );
	}
}
