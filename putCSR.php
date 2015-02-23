<?php
#add CSR to DB

function putCSR($fileName){
#Ordner erstellen
#Auf einem neuen System muss die uploaddir angepasst werden
$uploaddir = '/var/www/html/upload';
$pathToCSR = $uploaddir."/".$fileName."/";
shell_exec("mkdir ".$pathToCSR);

$uploadfile = $pathToCSR . basename($_FILES['userfile']['name']);

#php-Datei handler
#move_uploaded_file speichert die empfangene Datei
echo '<pre>';
if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
    echo "File is valid, and was successfully uploaded.\n";
} else {
    echo "Possible file upload attack!\n";
}

echo 'Here is some more debugging info:';
print_r($_FILES);

print "</pre>";

#printCSR to website
$var = shell_exec("openssl req -in ".$pathToCSR.$fileName." -noout -text");
echo($var);
echo("<br>");

#save to variables
$country = substr($var, strpos($var, "C=") + 2, strpos($var, "ST=") - strpos($var, "C=") - 4);
$state = substr($var, strpos($var, "ST=") + 3, strpos($var, "L=") - strpos($var, "ST=") - 5);
$location = substr($var, strpos($var, "L=") + 2, strpos($var, "O=") - strpos($var, "L=") - 4);
$org = substr($var, strpos($var, "O=") + 2, strpos($var, "OU=") - strpos($var, "O=") - 4);
$orgunit = substr($var, strpos($var, "OU=") + 3, strpos($var, "CN=") - strpos($var, "OU=") - 5);
$domain = substr($var, strpos($var, "CN=") + 3, strpos($var, "Subject Public Key") - strpos($var, "CN=") - 3);
#$email = $_GET["email"];

echo($country);
echo("<br>");
echo($state);
echo("<br>");
echo($location);
echo("<br>");
echo($org);
echo("<br>");
echo($orgunit);
echo("<br>");
echo($domain);
echo("<br>");

#extracting SANs
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
#someCode
}

$file=$_FILES['userfile']['name'];
echo($_FILES['userfile']['name']);

putCSR($file);

?>