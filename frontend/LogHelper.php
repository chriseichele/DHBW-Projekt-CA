<?php

class MailLog {
	
	private $file = "c:\apache24\logs\frontend_maillog.log";
	
	public function add($entry) {
		file_put_contents($file, date("Y-m-d H:i:s") . " # " . $entry . PHP_EOL, FILE_APPEND);
	}
	
}

?>