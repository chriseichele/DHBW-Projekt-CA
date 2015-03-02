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
			$start = $csr->start;
			$end = $csr->end;
			$duration = 365 * ($end - $start);
			#Prüfung ob die Select-Abfrage erfolgreich war
			if($pathToCSR == NULL) {
				throw new Exception("Der Pfad zur CSR Datei konnte nicht ermittelt werden!");
			}
			else {
				$update = $db->update_request_status($where, 3);
				#Prüfung ob die Update-Abfrage erfolgreich war			
				if(isset($update['affected_rows'])){
					#TODO: Pfad muss im Shell Skript angepasst werden
					#TODO: Pfad muss angepasst werden an den Ort des Skriptes auf dem Server angepasst werden.
					$pathToCRT = trim("c:\apache24\ca\kunden\crt\\".$name).".crt";
					#shell_exec("c:\apache24\bin\openssl.exe ca -config c:\apache24\htdocs\dev\arne\certificat.cnf -in ".$pathToCSR." -out ".$pathToCRT." -batch");
					shell_exec("c:\apache24\bin\openssl.exe x509 -req -in ".$pathToCSR." -CA c:\apache24\ca\ica-pub.pem -CAkey c:\apache24\ca\ica-key.pem -CAcreateserial -out ".$pathToCRT." -days ".$duration."  -sha256");
					$check = str_replace("\\", "/", $pathToCRT);
					if(file_exists($check)) {
						//Zertifikat Erstellung erfolgreich -> Pfad in DB aktualisieren
						$update_crt_path = $db->update_request_path_cer($where, $pathToCRT);
						
						if(isset($update_crt_path['affected_rows'])){
							//Alles OK
							return true;
						}
						else {
							throw new Exception("Aktualisierung des Pfads in der Datenbank fehlgeschlagen!");
						}
					}
					else {
						throw new Exception("Zertifikatserstellung mit OpenSSL fehlgeschlagen!".$pathToCRT);
					}
				}
				else {
					throw new Exception("Aktualisierung des Status in der Datenbank fehlgeschlagen!");
				}
					
			}

		}
	
	#openssl ca -config /home/arne/ssl/certificat.cnf -in /home/arne/ssl/example.com/example.csr -out /home/arne/ssl/example.crt
	#openssl x509 -req -in c:/apache24/ca/www-server.csr -CA c:/apache24/ca/ica-pub.pem -CAkey c:/apache24/ca/ica-key.pem -CAcreateserial -out c:/apache24/ca/kunden/server-pub-test.crt -days 1095 -sha512
	
?>
