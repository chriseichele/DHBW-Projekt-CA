<?php

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
		if (! isset ( $filelist[$download_bezeichner] )) throw new Exception("Sie d&uuml;rfen die Datei \"$download_bezeichner\" nicht herunter laden!");
			
		//Download Pfad zusammenbauen
		$filename = sprintf ( "%s/%s", $this->basedir, $filelist[$download_bezeichner]->path );
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
		$requests = $db->get_request_all_where(array('user_email','=',"'".$email."'"));
		$requests = $db->get_request_all_where(array('user_email','=',"'test@test.de'")); //momentan einziger testeintrag //TODO delete line
		if(!empty($requests)) {
			foreach($requests as $request) {
				$files[$request->id] = $request;
			}
		}
		
		//-> return der dateien als Array, bezeichner als key
		return $files;
	}
	
	public static function getUserRequestList() {
		$files = CrtDownloader::getUserFiles(CrtDownloader::getUser());
		
		$out = "<table class='table table-bordered'>";
		$out .= "<tr><th>Datei</th><th>Common Name</th></tr>";
		if(!empty($files)) {
			foreach($files as $id => $file) {
				$out .= "<tr>";
				$out .= "<td><a href=\"CrtDownloader.php?download=$file->id\">Certificate Signing Request ID $file->id</a></td>";
				$out .= "<td>$file->common_name</td>";
				$out .= "</tr>";
			}
		}
		$out .= "</table>";
		
		return $out;
	}
}

?>