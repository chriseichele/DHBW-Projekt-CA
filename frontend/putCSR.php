<?php
require_once('./db.php');

#This function saves a given .csr file to the filesystem 
#and writes its content to a database
#Input: $filename name of the uploaded file. 

function putCSR($fileObject, $laufzeit){
	#Ordner erstellen
	#Auf einem neuen System muss die uploaddir angepasst werden
	$uploaddir = 'c:\apache24\ca\kunden\csr\\';
	#shell_exec("mkdir ".$pathToCSR);

	$uploadfile = $uploaddir . basename($fileObject['name']);
	//Eindeutigen Namen vergeben
	$path_parts = pathinfo($uploadfile);
	while(file_exists($uploadfile)) {
		//Solange versuchen bis Datei noch nicht exisitert
		$uploadfile = $path_parts['dirname'] . "\\" . $path_parts['filename'] . "_" . $i . $path_parts['extension'];
		$i++;
	}
	#echo($uploadfile);
	#exit();

	#php-Datei handler
	#move_uploaded_file speichert die empfangene Datei
	#Der Inhalt des Uploads muss noch geprüft werden! 
	#Mögliche File-Upload Attack
	echo '<pre>';
	if (move_uploaded_file($fileObject['tmp_name'], $uploadfile)) {
		echo "File is valid, and was successfully uploaded.\n";
	} else {
		// Exception bei Fehlern, werden von Seite ausgegeben
		throw new Exception("Possible file upload attack!");
	}

	echo 'Here is some more debugging info:';
	print_r($fileObject);

	print "</pre>";





	#printCSR to website
	$var = shell_exec('c:\apache24\bin\openssl.exe req -noout -text -in '.$uploadfile);
	echo('<pre>'.$var.'</pre>');

	#save to variables
	$country = substr($var, strpos($var, "C=") + 2, strpos($var, "ST=") - strpos($var, "C=") - 4);
	$state = substr($var, strpos($var, "ST=") + 3, strpos($var, "L=") - strpos($var, "ST=") - 5);
	$location = substr($var, strpos($var, "L=") + 2, strpos($var, "O=") - strpos($var, "L=") - 4);
	$org = substr($var, strpos($var, "O=") + 2, strpos($var, "OU=") - strpos($var, "O=") - 4);
	$orgunit = substr($var, strpos($var, "OU=") + 3, strpos($var, "CN=") - strpos($var, "OU=") - 5);
	$domain = substr($var, strpos($var, "CN=") + 3, strpos($var, "Subject Public Key") - strpos($var, "CN=") - 3);
	#$email = $_GET["email"];

	echo("<pre>");
	echo('<h3>Country</h3>'.$country);
	echo('<h3>State</h3>'.$state);
	echo('<h3>Location</h3>'.$location);
	echo('<h3>Org</h3>'.$org);
	echo('<h3>OrgUnit</h3>'.$orgunit);
	echo('<h3>Domain</h3>'.$domain);
	echo("</pre>");

	#extracting SANs from the csr file
	$sanString = strpos($var, "X509v3 Subject Alternative Name");

	if ($sanString === false){
			echo("error");
		}
		else {
			$SANs = explode("DNS", $var);

			for($i = 0; $i < count($SANs); $i++){
			$SANs[$i] = substr($SANs[$i], 1, -2);
			#echo($SANs[$i]);
			#echo("<br>");
			}
		}
		unset($value);
		#die Sonderfälle Pos 0 und Max abfangen
		unset($SANs[0]);
		$temp = explode(" ",$SANs[count($SANs)]);
		$SANs[count($SANs)] = $temp[0];
		unlink($temp);
		print_r($SANs);
	
	#writeToDB
	$db = new DBAccess();
	$dbresult = $db->insert_request(date("Y-m-d H:i:s"), date('Y-m-d H:i:s',strtotime(date("Y-m-d H:i:s", time()) . " + ".(365*$laufzeit)." day")), $country, $state, $location, $org, $domain, "1", $orgunit, NULL, NULL, NULL, NULL, NULL,$uploadfile, NULL);
	
	echo '<pre><h3>DB Result</h3>';
	print_r($dbresult);
	echo '</pre>';
	
	//exit();
	//Wenn hier zuvor keine Exception war: ERFOLG
	return true;
}

?>
