<?php

	function log($string){
		file_put_contents("c:\apache24\ca\openssl.log", $string.PHP_EOL, APPEND_FILE);
	}

?>