<?php

/* Downloader Klasse fuer CRT-Files */

class CrtDownloader {
	private $basedir;
	
	public function __construct() {
		//Variablen Initialisierung
		$this->basedir = "../../"; //Verzeichnis außerhalb des Document Root (nicht per Web-URL ereichbar)
	}
	
	public function downloadCSR($download_bezeichner) {
		download($download_bezeichner, "CSR")
	}
	
	public function downloadCRT($download_bezeichner) {
		download($download_bezeichner, "CRT")
	}
	
	public function download($download_bezeichner, $filetype) {
	
		//Angemeldeten User prüfen/holen
		$user = CrtDownloader::getUser();
		
		//Lesen von Dateien des Users
		$filelist = CrtDownloader::getUserFiles($user);
		
		//ist gewünschte Download Datei in den erlaubten Dateien des aktuellen Users?
		if (! isset ( $filelist[$download_bezeichner] )) throw new Exception("Sie d&uuml;rfen die Datei \"$download_bezeichner\" nicht herunter laden!");
			
		//Download Pfad zusammenbauen
		if($filetype == "CSR") {
			$filename = sprintf ( "%s/%s", $this->basedir, $filelist[$download_bezeichner]->path_csr );
		}
		elseif($filetype == "CRT") {
			$filename = sprintf ( "%s/%s", $this->basedir, $filelist[$download_bezeichner]->path_cer );
		}
		else {
			throw new Exception("Dateityp \"".$filetype."\" nicht unterst&uuml;tzt!");
		}
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
	
	private static function getUserFiles($email, $status = null) {
		//Dateien des Users von der Datenbank abfragen
		$db = new DBAccess();
		if($status != null) {
			$where = array('requester','=',"'".$email."'","AND","status","=","'".$status."'");
		}
		else {
			$where = array('requester','=',"'".$email."'");
		}
		$requests = $db->get_request_all_where($where);
		$files = null;
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
		$out .= "<tr><th>Anfrage</th><th>Common Name</th><th>Status</th><!-- Download --></th></tr>";
		if(!empty($files)) {
			foreach($files as $id => $file) {
				$out .= "<tr>";
				$out .= "<td>Certificate Signing Request ID $file->id</td>";
				$out .= "<td>$file->common_name</td>";
				$out .= "<td>$file->status</td>";
				$out .= "<td><a href=\"CrtDownloader.php?downloadCSR=$file->id\" class='btn'>Download CSR</a></td>";
				$out .= "</tr>";
			}
		}
		$out .= "</table>";
		
		return $out;
	}
	
	public static function getUserCertList() {
		$files = CrtDownloader::getUserFiles(CrtDownloader::getUser(), 3);
		
		$out = "<table class='table table-bordered'>";
		$out .= "<tr><th>Anfrage</th><th>Common Name</th><th><!-- Download --></th></tr>";
		if(!empty($files)) {
			foreach($files as $id => $file) {
				$out .= "<tr>";
				$out .= "<td>Certificate ID $file->id</td>";
				$out .= "<td>$file->common_name</td>";
				$out .= "<td><a href=\"CrtDownloader.php?downloadCRT=$file->id\" class='btn'>Download CRT</a></td>";
				$out .= "</tr>";
			}
		}
		$out .= "</table>";
		
		return $out;
	}
}

?>