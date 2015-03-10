<?php

class MailLog {
	
	private $dir = "c:\apache24\logs\\";
	private $file = "c:\apache24\logs\frontend_maillog.log";
	
	public function addLine($entry) {
		if (!file_exists($this->dir) && !is_dir($this->dir)) {
			//Log Verzeichnis erstellen wenn es noch nicht existiert
    		mkdir($this->dir);         
		}
		//Logeintrag unten dran setzen, in neue Zeile und mit Timestamp
		file_put_contents($this->file, date("Y-m-d H:i:s") . " # " . $entry . PHP_EOL, FILE_APPEND);
	}
	
}

?>