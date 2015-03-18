<?php

	function logOS($string){
		file_put_contents("c:\apache24\ca\openssl.log", $string.PHP_EOL, FILE_APPEND);
	}

?>
