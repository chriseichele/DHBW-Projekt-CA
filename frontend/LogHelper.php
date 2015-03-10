<?php

class MailLogger extends Logger {
	
	public function __construct() {
		$this->file = $this->dir . "frontend_maillog.log";
	}
	
}

abstract class Logger {
	
	protected $dir = "c:\apache24\logs\\";
	protected $file;
	
	protected $show_error = true;
	protected $show_notice = true;
	
	public function addError($entry) {
		if($this->show_error) {
			$this->addLine($entry, "ERROR");
		}
	}
	public function addNotice($entry) {
		if($this->show_notice) {
			$this->addLine($entry, "NOTICE");
		}
	}

	protected function addLine($entry, $type) {
		if (!file_exists($this->dir) && !is_dir($this->dir)) {
			//Log Verzeichnis erstellen wenn es noch nicht existiert
    		mkdir($this->dir);         
		}
		//Logeintrag unten dran setzen, in neue Zeile und mit Timestamp
		file_put_contents($this->file, date("Y-m-d H:i:s") . " #" . $type . " - " . $entry . PHP_EOL, FILE_APPEND);
	}
	
}

?>