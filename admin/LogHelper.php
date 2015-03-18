<?php

class OpensslLogger extends Logger {
	
	public function __construct() {
		$this->file = $this->dir . "openssllog.log";
	}
	
}

class DBLogger extends Logger {
	
	public function __construct() {
		$this->file = $this->dir . "dblog.log";
	}
	
}

class MailLogger extends Logger {
	
	public function __construct() {
		$this->file = $this->dir . "maillog.log";
	}
	
}

class CrtLogger extends Logger {
	
	public function __construct() {
		$this->file = $this->dir . "admin_crtlog.log";
	}
	
}

abstract class Logger {
	
	protected $dir = "c:\apache24\logs\\";
	protected $file;
	
	protected $show_error = true;
	protected $show_notice = true;
	
	public function addError($entry) {
		if($this->show_error) {
			return $this->addLine($entry, "ERROR");
		}
		else {
			return false;
		}
	}
	public function addNotice($entry) {
		if($this->show_notice) {
			return $this->addLine($entry, "NOTICE");
		}
		else {
			return false;
		}
	}

	public function addLine($entry, $type) {
		if (!file_exists($this->dir) && !is_dir($this->dir)) {
			//Log Verzeichnis erstellen wenn es noch nicht existiert
    		mkdir($this->dir);         
		}
		//Logeintrag unten dran setzen, in neue Zeile und mit Timestamp
		file_put_contents($this->file, date("Y-m-d H:i:s") . " #" . $type . " - " . $entry . PHP_EOL, FILE_APPEND);
		//Erfolgreich
		return true;
	}
	
}

?>