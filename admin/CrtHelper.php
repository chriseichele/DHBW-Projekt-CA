<?php

/* Helper & Downloader Klasse fuer CSR- & CRT-Files */

class CrtHelper {
	private $basedir;
	
	public function __construct() {
		//Variablen Initialisierung
		$this->basedir = "C:\Apache24\htdocs\\"; //Verzeichnis außerhalb des Document Root (nicht per Web-URL ereichbar)
	}
	
	public function downloadCSR($download_bezeichner) {
		$this->download($download_bezeichner, "CSR");
	}
	
	/* Download von Zertifikaten für Admins nicht vorgesehen
	public function downloadCRT($download_bezeichner) {
		$this->download($download_bezeichner, "CRT");
	}*/
	
	private function download($download_bezeichner, $filetype) {
	
		//Angemeldeten User prüfen/holen
		$user = CrtHelper::getUser();
		
		//Lesen von Dateien des Users
		$filelist = CrtHelper::getUserFiles($user);
		
		//ist gewünschte Download Datei in den erlaubten Dateien des aktuellen Users?
		if (! isset ( $filelist[$download_bezeichner] )) throw new Exception("Sie d&uuml;rfen die Datei \"$download_bezeichner\" nicht herunter laden!");
			
		//Download Pfad zusammenbauen
		if($filetype == "CSR") {
			if(!empty($filelist[$download_bezeichner]->path_csr)) {
				//$filename = sprintf ( "%s/%s", $this->basedir, $filelist[$download_bezeichner]->path_csr );
				$filename = $filelist[$download_bezeichner]->path_csr;
			}
			else {
				throw new Exception("Download Pfad f&uuml;r CSR-Datei nicht gesetzt! Bitte nehmen Sie Kontakt zu uns auf!");
			}
		}
		/* Download von Zertifikaten für Admins nicht vorgesehen
		elseif($filetype == "CRT") {
			if(!empty($filelist[$download_bezeichner]->path_cer)) {
				//$filename = sprintf ( "%s/%s", $this->basedir, $filelist[$download_bezeichner]->path_cer );
				$filename = $filelist[$download_bezeichner]->path_cer;
			}
			else {
				throw new Exception("Download Pfad f&uuml;r CRT-Datei nicht gesetzt! Bitte nehmen Sie Kontakt zu uns auf!");
			}
		}*/
		else {
			throw new Exception("Dateityp \"".$filetype."\" nicht unterst&uuml;tzt!");
		}
		$filepath_slash = str_replace("\\", "/", $filename); //Backslash für Windows, Slash für PHP im Pfad
		if (! file_exists($filepath_slash) ) throw new Exception("Unerwarteter Fehler beim herunterladen der Datei \"$download_bezeichner\". Bitte nehmen Sie Kontakt zu uns auf!"); 
		
		//Passenden Datentyp im HTTP Header setzen
		header ( "Content-Type: application/octet-stream" );
		
		//Passenden Dateinamen im Download-Requester vorgeben
		$save_as_name = basename ( $filename );
		header ( "Content-Disposition: attachment; filename=\"$save_as_name\"" );
		
		// Datei ausgeben.
		readfile ( $filename );
	}
	
	private static function getVerifiedCSR($email) {
		//Dateien von der Datenbank abfragen
		$db = new DBAccess();
		$where = array("verifier","=","'".$email."'");
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
	public static function getVerifiedCSRList($email) {
		$files = CrtHelper::getVerifiedCSR($email);
		
		$out = "<table class='table table-bordered'>";
		$out .= "<tr><th>CSR</th><th>Common Name</th><th>Startzeit</th><th>Endzeit</th><th>Status</th><th><!-- Action --></th></tr>";
		if(!empty($files)) {
			foreach($files as $id => $file) {
				$out .= "<tr>";
				$out .= "<td>Certificate Signing Request ID $file->id</td>";
				$out .= "<td>$file->common_name</td>";
				$out .= "<td>$file->start</td>";
				$out .= "<td>$file->end</td>";
				$out .= CrtHelper::getStatusColorfulTD($file->status);
				$out .= "<td><a href=\"viewCSR.php?csr=$file->id\" class='btn btn-primary'>CSR anzeigen</a></td>";
				$out .= "</tr>";
			}
		}
		$out .= "</table>";
		
		return $out;
	}
	
	private static function getOpenCSR($excludeMe = false) {
		//Dateien von der Datenbank abfragen
		$db = new DBAccess();
		if($excludeMe) {
			//Requests für einen eigenen User ausblenden
			require_once('./UserHelper.php');
			$where = array("status","=","'created'"," AND ","requester","!=","'".UserHelper::GetUserEmail()."'");
		} else {
			$where = array("status","=","'created'");
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
	public static function getOpenCSRList($excludeMe = false) {
		$files = CrtHelper::getOpenCSR($excludeMe);
		
		$out = "<table class='table table-bordered'>";
		$out .= "<tr><th>CSR</th><th>Common Name</th><th>Startzeit</th><th>Endzeit</th><th><!-- Action --></th></tr>";
		if(!empty($files)) {
			foreach($files as $id => $file) {
				$out .= "<tr>";
				$out .= "<td>Certificate Signing Request ID $file->id</td>";
				$out .= "<td>$file->common_name</td>";
				$out .= "<td>$file->start</td>";
				$out .= "<td>$file->end</td>";
				$out .= "<td><a href=\"zertifikatsanfragen.php?csr=$file->id\" class='btn btn-primary'>CSR zur Genehmigung anzeigen</a></td>";
				$out .= "</tr>";
			}
		}
		$out .= "</table>";
		
		return $out;
	}
	
	private static function getStatusColorfulTD($status) {
		switch($status) {
			case 'created':
				return "<td class='bg-info'>$status</td>";
			case 'validating':
				return "<td class='bg-warning'>$status</td>";
			case 'finished':
				return "<td class='bg-success'>$status</td>";
			case 'aborted':
				return "<td class='bg-danger'>$status</td>";
			default:
				return "<td>$status</td>";
		}
	}
}

?>