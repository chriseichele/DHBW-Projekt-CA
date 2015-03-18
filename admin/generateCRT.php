<?php
require_once('./db.php');
require_once('./LogHelper.php');

#input id: id für das Zertifikat in der DB
#output .crt Datei 
		
		function createCertificate($id){

			$log = new OpensslLogger();

			#TODO: Abfragen ob der User eingeloggt  ist
			#get various informations from database used for signing the certificate
			$db = new DBAccess();
			$where = array("id","=","'".$id."'");
			$db_result = $db->get_request_all_where($where);
			$csr = reset($db_result);
			$pathToCSR = $csr->path_csr;
			$name = date("Y-m-d-H-i-s")."_".$csr->common_name;
			$start = $csr->start;
			$end = $csr->end;
			$duration = 365 * ($end - $start);
			#Prüfung ob die Select-Abfrage erfolgreich war
			if($pathToCSR == NULL) {
				throw new Exception("Der Pfad zur CSR Datei konnte nicht ermittelt werden!");
			}
			else {
				#update the request status to finished. Then it will be displayed in the frontend
				$update = $db->update_request_status($where, 3);
				#Prüfung ob die Update-Abfrage erfolgreich war			
				if(isset($update['affected_rows'])){
					$pathToCRT = trim("c:\apache24\ca\kunden\crt\\".$name).".crt";
					#check if SANs exist in DB
					$db_result = $db->get_sans_all_where(array("request_id","=","'".$id."'"));
					$checkSAN = $db_result[0]->name;
					if($checkSAN != NULL){
						#create certificate with SANs
						getSANs($id, 0);
						$opensslcmd = "c:\apache24\bin\openssl.exe x509 -req -CA c:\apache24\ca\ica.crt -CAkey c:\apache24\ca\ica.key -CAcreateserial -in ".$pathToCSR." -out ".$pathToCRT." -days ".$duration." -sha256 -extensions v3_req -extfile c:\apache24\htdocs\dev\arne\openssl.cnf";
						#sign certificate
						shell_exec($opensslcmd);
						#write Command and config to log
						$log->addNotice($opensslcmd);
						$log->addNotice(file_get_contents("c:\apache24\htdocs\dev\arne\openssl.cnf"));
						#delete config created in getSANs($id, 0)
						unlink("c:\apache24\htdocs\dev\arne\openssl.cnf");
					}
					else{
					#if no SANs where found, sign normally
					#write command and config used to log file
					$opensslcmd = "c:\apache24\bin\openssl.exe x509 -req -CA c:\apache24\ca\ica.crt -CAkey c:\apache24\ca\ica.key -CAcreateserial -in ".$pathToCSR." -out ".$pathToCRT." -days ".$duration." -sha256";
					shell_exec($opensslcmd);
					$log->addNotice($opensslcmd);
					}
					$check = str_replace("\\", "/", $pathToCRT);
					if(file_exists($check)) {
						//Zertifikat Erstellung erfolgreich -> Pfad in DB aktualisieren
						$update_crt_path = $db->update_request_path_cer($where, $pathToCRT);
						if(isset($update_crt_path['affected_rows'])){
							//ADD certificate chain to the certificate
							file_put_contents($pathToCRT, file_get_contents("c:\Apache24\ca\ica.crt") , FILE_APPEND);
							file_put_contents($pathToCRT, file_get_contents("c:\Apache24\ca\ca.crt") , FILE_APPEND);
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
	
	function createIntermediate($id){
	#TODO: Abfragen ob der User eingeloggt ist
	$db = new DBAccess();
	$log = new OpensslLogger();
	
	$where = array("id","=","'".$id."'");
	$db_result = $db->get_request_all_where($where);
	$csr = reset($db_result);
	$pathToCSR = $csr->path_csr;
	$name = date("Y-m-d-H-i-s")."_".$csr->common_name;
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
		$checkSAN = $db_result[0]->name;
		if($checkSAN != NULL){
		getSANs($id, 1);
		$opensslcmd = "c:\apache24\bin\openssl.exe x509 -req -CA c:\apache24\ca\ica.crt -CAkey c:\apache24\ca\ica.key -CAcreateserial -in ".$pathToCSR." -out ".$pathToCRT." -days ".$duration." -sha256 -extensions v3_req -extfile c:\apache24\htdocs\dev\arne\openssl.cnf";
		shell_exec($opensslcmd);
		$log->addNotice($opensslcmd);
		$log->addNotice(file_get_contents("c:\apache24\htdocs\dev\arne\openssl.cnf"));
		unlink("c:\apache24\htdocs\dev\arne\openssl.cnf");
		}
		else{
			#if no SANs where found, sign normally
			file_put_contents("c:\apache24\htdocs\dev\arne\openssl.cnf", "
			
			[ v3_req ]
			# Extensions to add to a certificate request
			basicConstraints = CA:TRUE
			subjectKeyIdentifier=hash

			authorityKeyIdentifier=issuer
			keyUsage = nonRepudiation, digitalSignature, keyEncipherment");
			$opensslcmd = "c:\apache24\bin\openssl.exe x509 -req -CA c:\apache24\ca\ica.crt -CAkey c:\apache24\ca\ica.key -CAcreateserial -in ".$pathToCSR." -out ".$pathToCRT." -days ".$duration." -sha256 -extensions v3_req -extfile c:\apache24\htdocs\dev\arne\openssl.cnf";
			shell_exec($opensslcmd);
			$log->addNotice($opensslcmd);
			$log->addNotice(file_get_contents("c:\apache24\htdocs\dev\arne\openssl.cnf"));
			unlink("c:\apache24\htdocs\dev\arne\openssl.cnf");
			
		}
			#add CA-Intermediate and Root certificate to the intermediate for chain
			file_put_contents($pathToCRT, file_get_contents("c:\Apache24\ca\ica.crt") , FILE_APPEND);
			file_put_contents($pathToCRT, file_get_contents("c:\Apache24\ca\ca.crt") , FILE_APPEND);
			
			$check = str_replace("\\", "/", $pathToCRT);
			if(file_exists($check)) {
			//Zertifikat Erstellung erfolgreich -> Crt-Pfad in DB aktualisieren
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
	
	function getSANs($id, $isIntermediate){
		#create a temporary config for certificate signing. 
		#Use different constraints for non-intermediate and intermediate certificates
		if($isIntermediate == 0){
			$configContent = "
		[ v3_req ]
		# Extensions to add to a certificate request
			
		basicConstraints = CA:FALSE
		keyUsage = nonRepudiation, digitalSignature, keyEncipherment
		subjectAltName = @alt_names
		[ alt_names ]".PHP_EOL;
			
		}
		else{
			$configContent = "
			[ v3_req ]
			# Extensions to add to a certificate request
			basicConstraints = CA:TRUE
			subjectKeyIdentifier=hash
		
			authorityKeyIdentifier=issuer
			keyUsage = nonRepudiation, digitalSignature, keyEncipherment
			subjectAltName = @alt_names
			[ alt_names ]".PHP_EOL;	
			
		}
		file_put_contents("c:\apache24\htdocs\dev\arne\openssl.cnf", $configContent);
		#lookup SANs in DB and attach them to the config file
		$db = new DBAccess();
		$where = array("request_id","=","'".$id."'");
		$db_result = $db->get_sans_all_where($where);
		for($i = 0; $i < count($db_result); $i++){
			file_put_contents("c:\apache24\htdocs\dev\arne\openssl.cnf", "DNS.".$i." = ".$db_result[$i]->name.PHP_EOL, FILE_APPEND);
		}
		

	}

?>
