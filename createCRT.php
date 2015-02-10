<?php
	#input csr: Pfad zur csr Datei
	#input dir: directory für das Zertifikat
	#output .crt Datei:  
		function createCertificate($dir){
			#test-csr
		$res = shell_exec("sudo /bin/createCRT.sh ".$dir);
		echo($res);
		}
		
		$exp = $_GET["test"];
		createCertificate($exp);
	
	#openssl ca -config /home/arne/ssl/certificat.cnf -in /home/arne/ssl/example.com/example.csr -out /home/arne/ssl/example.crt
	
?>
