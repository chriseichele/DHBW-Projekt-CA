<?php
	#input csr: Pfad zur csr Datei
	#input dir: directory für das Zertifikat
	#output .crt Datei:  
		function createCertificate($dir){
			#test-csr
			#echo $dir."<br>";
			global $exp;
			$csr = "/var/www/html/".$exp."/retina.csr";
			#echo "$csr";	
			#echo("<br>");
			shell_exec("cd /var/www/html/");
			shell_exec("mkdir lol");
			#Ordner für die crt Datei anlegen
			$cmd = "/var/www/html/".$dir."/";
			#echo($cmd."<br>");
			#shell_exec("mkdir "+$cmd);
			
			#Config-Datei auswählen	
			#$conf = "/home/arne/ssl/ca.cnf";	
			
			#Zertifikat erstellen	
			$crtcmd = "openssl ca -config /home/arne/ssl/certificat.cnf -in ".$csr.".csr -out ".$cmd."example.crt";
			#echo"$crtcmd";
			$test = shell_exec($crtcmd);
			#echo "$test";
			#Ergebnis in Datei schreiben
		
		}
		
		$exp = $_GET["test"];
		#echo($exp);
		#echo('<br>');
		createCertificate($exp);
	
	#openssl ca -config /home/arne/ssl/certificat.cnf -in /home/arne/ssl/example.com/example.csr -out /home/arne/ssl/example.crt
	
?>
