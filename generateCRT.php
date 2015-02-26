<?php
require_once('./db.php');
require_once('./UserHelper.inc');
#input id: id für das Zertifikat in der DB
#output .crt Datei:  
		function createCertificate($id){
			doUserRightsCheck();
			
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
				$update = $db->update_request_status($where, 3);
				#Prüfung ob die Update-Abfrage erfolgreich war			
				if(isset($update['affected_rows'])){
					#TODO: openssl config einrichten
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
