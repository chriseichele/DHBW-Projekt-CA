<?php
require_once('./db.php');	
#input id: id für das Zertifikat in der DB
#output .crt Datei:  
		function createCertificate($id){
			#TODO: Abfragen ob der User eingeloggt  ist
			
			$db = new DBAccess();
			$where = array("id","=","'".$id."'");
			$db_result = $db->get_request_all_where($where);
			$csr = reset($db_result);
			$pathToCSR = $csr->path_csr;
			$name = $csr->common_name;
			#Prüfung ob die Select-Abfrage erfolgreich war
			if($pathToCSR == NULL)
				{
					throw new Exception("Etwas blödes ist passiert. Das tut uns leid. Fehler 1");
				}
			else{
				$update = $db->update_request_status("id = ".$id, 3);
				#Prüfung ob die Update-Abfrage erfolgreich war			
				if(isset($update)){
					#TODO: Pfad muss im Shell Skript angepasst werden
					#TODO: Pfad muss angepasst werden an den Ort des Skriptes auf dem Server angepasst werden.
					shell_exec("c:\apache24\bin\openssl.exe ca -config c:\apache24\htdocs\dev\arne\certificat.cnf -in ".$pathToCSR." -out c:\apache24\htdocs\\".$name." -batch");
					return true;
				}
				else{
					throw new Exception("Etwas blödes ist passiert. Das tut uns leid. Fehler 2");
					}
					
			}

		}
	
	#openssl ca -config /home/arne/ssl/certificat.cnf -in /home/arne/ssl/example.com/example.csr -out /home/arne/ssl/example.crt
	
?>
