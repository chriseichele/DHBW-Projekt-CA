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
					$pathToCRT = trim("c:\apache24\ca\kunden\crt\\".$name).".crt";
					
					#dealing with SANs
					$db_result = $db->get_sans_all_where(array("request_id","=","'".$id."'"));
					$checkSAN = reset($db_result);
					if(isset($checkSAN)){
						getSANs($id);
						#shell_exec("c:\apache24\bin\openssl.exe ca -out ".$pathToCRT." -batch -config c:\apache24\htdocs\dev\arne\openssl.cnf -extensions v3_req -infiles ".$pathToCSR." ");
						shell_exec("c:\apache24\bin\openssl.exe x509 -req -CA c:\apache24\ca\ica.crt -CAkey c:\apache24\ca\ica.key -CAcreateserial -in ".$pathToCSR." -out ".$pathToCRT." -days ".$duration." -sha256 -extensions v3_req -extfile c:\apache24\htdocs\dev\arne\openssl.cnf");
						#unlink("c:\apache24\htdocs\dev\arne\openssl.cnf");
					}
					else{
					#if no SANs where found, sign anyway
					shell_exec("c:\apache24\bin\openssl.exe x509 -req -CA c:\apache24\ca\ica.crt -CAkey c:\apache24\ca\ica.key -CAcreateserial -in ".$pathToCSR." -out ".$pathToCRT." -days ".$duration." -sha256");
					}
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
						throw new Exception("Zertifikatserstellung mit OpenSSL fehlgeschlagen!" . "<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;");
					}
				}
				else {
					throw new Exception("Aktualisierung des Status in der Datenbank fehlgeschlagen!");
				}
					
			}

		}
	
	function getSANs($id){	
		file_put_contents("c:\apache24\htdocs\dev\arne\openssl.cnf", "
[ v3_req ]
# Extensions to add to a certificate request
	
basicConstraints = CA:FALSE
keyUsage = nonRepudiation, digitalSignature, keyEncipherment
subjectAltName = @alt_names
[ alt_names ]".PHP_EOL
	
		);
	
		$db = new DBAccess();
		$where = array("request_id","=","'".$id."'");
		$db_result = $db->get_sans_all_where($where);
		for($i = 0; $i < count($db_result); $i++){
			file_put_contents("c:\apache24\htdocs\dev\arne\openssl.cnf", "DNS.".$i." = ".$db_result[$i]->name.PHP_EOL, FILE_APPEND);
		}
		

	}	

?>
