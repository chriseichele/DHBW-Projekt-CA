<?php

class MailLog {
	
	private $dir = "c:\apache24\logs\\";
	private $file = $path . "frontend_maillog.log";
	
	public function add($entry) {
		if (!file_exists($dir) && !is_dir($dir)) {
			//Log Verzeichnis erstellen wenn es noch nicht existiert
    		mkdir($dir);         
		}
		//Logeintrag unten dran setzen, in neue Zeile und mit Timestamp
		file_put_contents($file, date("Y-m-d H:i:s") . " # " . $entry . PHP_EOL, FILE_APPEND);
	}
	
}

?>