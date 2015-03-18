<?php
require_once('./db.php');
require_once('./LogHelper.php');


#db.php database class
#Logger.php class is used to Log any openssl related actions

#This function saves a given .csr file to the filesystem 
#and writes its content to a database
#Input: $filename name of the uploaded file. 
#Input: $laufzeit Laufzeit des Zertifikates (1,3 oder 5 Jahre)
#Input: $intermediate: Ist das angeforderte Zertifikat intermediate (0,1) 

function putCSR($fileObject, $laufzeit, $intermediate){
	$uploaddir = 'c:\apache24\ca\kunden\csr\\';
	$log = new OpensslLogger();

	$uploadfile = $uploaddir . basename($fileObject['name']);
	//Eindeutigen Namen vergeben
	$path_parts = pathinfo($uploadfile);
	$i = 0;
	while(file_exists($uploadfile)) {
		//Solange versuchen bis Datei noch nicht exisitert
		$uploadfile = $path_parts['dirname'] . "\\" . $path_parts['filename'] . "_" . $i . "." . $path_parts['extension'];
		$i++;
	}

	#php-Datei handler
	#move_uploaded_file speichert die empfangene Datei
	#in der validateCSR.php werden diverse Dinge geprüft
	if (move_uploaded_file($fileObject['tmp_name'], $uploadfile)) {
		#echo "File is valid, and was successfully uploaded.\n";
	} else {
		// Exception bei Fehlern, werden von Seite ausgegeben
		throw new Exception("Error 1 uploading csr");
	}

	#open request and save it to a variable
	$opensslcmd = "c:\apache24\bin\openssl.exe req -noout -text -in ".$uploadfile;
	$var = shell_exec($opensslcmd);
	$log->addNotice($opensslcmd);

	#split the string and save to variables
	$country = substr($var, strpos($var, "C=") + 2, strpos($var, "ST=") - strpos($var, "C=") - 4);
	$state = substr($var, strpos($var, "ST=") + 3, strpos($var, "L=") - strpos($var, "ST=") - 5);
	$location = substr($var, strpos($var, "L=") + 2, strpos($var, "O=") - strpos($var, "L=") - 4);
	$org = substr($var, strpos($var, "O=") + 2, strpos($var, "OU=") - strpos($var, "O=") - 4);
	$orgunit = substr($var, strpos($var, "OU=") + 3, strpos($var, "CN=") - strpos($var, "OU=") - 5);
	$domain = trim(substr($var, strpos($var, "CN=") + 3, strpos($var, "Subject Public Key") - strpos($var, "CN=") - 3));
	$temp = explode("/emailAddress=", $domain);
	$domain = $temp[0];
	$email = $temp[1];
	unset($temp);
	
		#writeToDB
	#create an entry in the request table
	$db = new DBAccess();
	$dbresult = $db->insert_request(date("Y-m-d H:i:s"), date('Y-m-d H:i:s',strtotime(date("Y-m-d H:i:s", time()) . " + ".(365*$laufzeit)." day")), $country, $state, $location, $org, $domain, "1", $orgunit, $email, NULL, NULL, $intermediate, NULL,$uploadfile, NULL);	
	//Request ID aus DB Rückgabe holen
	$req_id = $dbresult['id'];
	
	$log->addNotice("Ergebnis DB-Queue : ".print_r($dbresult));
	$log->addNotice("req_id = ".$req_id);

	#extracting SANs from the csr file
	$sanString = strpos($var, "X509v3 Subject Alternative Name");

	if ($sanString === false){
			#if no SANs were found, skip this paragraph
		}
		else {
			#save SANs to Array
			$SANs = explode("DNS", $var);

			for($i = 0; $i < count($SANs); $i++){
			$SANs[$i] = substr($SANs[$i], 1, -2);
			}
			unset($value);
			#die Sonderfälle Pos 0 und Max abfangen
			unset($SANs[0]);
			$temp = explode(" ",$SANs[count($SANs)]);
			$SANs[count($SANs)] = $temp[0];
			unset($temp);
			#Array neu schreiben
			$SANs = array_values(array_filter($SANs));
			
				
			#inserts SANs into san table
			for($i = 0; $i < count($SANs); $i++){
				$db->insert_sans($req_id, $SANs[$i]);
			}
		}
	
	#insert 2 default SANs
	$is_www = strpos($domain, "www.");
	if($is_www === false){
		$db->insert_sans($req_id, "www.".$domain);	
	}
	else{
		$array = explode("www.", $domain);
		$db->insert_sans($req_id, $array[1]);
		unset($array);
	}

	if($req_id == "0"){
		#if db queue failed, return Exception
		throw new Exception("Die Aktualisierung der DB war nicht erfolgreich!");
		#del csr from file system
		unlink($uploadfile);
	}
	else{
	//Wenn hier zuvor keine Exception war: ERFOLG -> Request ID zurück geben	
	return $req_id;
	}
}

?>
