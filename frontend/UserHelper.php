<?php
SESSION_START();

require_once('./db.php');
require_once('./MessageHandler.php');

class UserHelper {

	public static function RegisterCustomer($email, $firstname, $lastname, $pwhash) {
		//Nur Customer neu registrieren lassen
		return UserHelper::Register($email, $firstname, $lastname, $pwhash, "customer");
	}
	private static function Register($email, $firstname, $lastname, $pwhash, $role) {
		//Aufruf um Benutzer neu zu registrieren
		require_once('./LogHelper.php');
		$log = new AccountLogger();
		
		$db = new DBAccess();
		//Prüfen ob user bereits existiert
		$db_result = $db->get_user_all_where(array("email","=","'".$email."'"));
		if(!empty($db_result)) {
			//Es existiert bereits ein User mit der Mailadresse (ID)
			throw new Exception("Sie haben sich bereits mit dieser Email Adresse registriert. Bitte loggen Sie sich stattdessen in ihren Account ein.");
		}
		else {
			//User registrieren
			$code = hash('sha512', rand(1,100000).$email.date("YmdHis").rand(1,100000)); //Zufällige Zeichenfolge
			$db_success = $db->insert_user($email, $firstname, $lastname, $pwhash, $role, $code) == array(); //erfolg liefert ein leeres array
			if($db_success) {
				$log->addNotice("User &lt;".$email."&gt; hat sich erfolgreich registriert.");
				//Aktivierungsmail schicken
				require_once('./MailHelper.php');
				try {
					send_activision_mail($email, $code);
				} catch(Exception $e) {
					$_SESSION['message']['error'][] = $e->getMessage();
				}
				//Registrieren war hier in jedem Fall erfolgreich
				return true;
			}
			else {
				$log->addError("User &lt;".$email."&gt; konnte nicht registriert werden.");
				return false;
			}
		}
	}

	public static function Login($email, $pwhash) {
		//Login Aufruf für Außerhalb
		$db = new DBAccess();
		
		$users = $db->get_user_all_where(array("email", "=", "'".$email."'"));
		$user = reset($users);
		
		if ($user == null) {
			return false;
		} else {
			//Check PW and do Login
			if($pwhash == $user->passwordhash) {
				//Check Account Activiated
				if($user->activation_code == null) {
					return UserHelper::DoLogin($user->email, $user->firstname, $user->surname);
				}
				else {
					throw new Exception("Account ist nicht aktiviert! Bitte &uuml;berprüfen Sie ihr Email Postfach auf die Aktivierungsmail.");
				}
			} 
			else {
				return false;
			}
		}
		//Return Boolean Success
	}

	private static function DoLogin($email, $firstname, $lastname) {
		//Login Daten in Session Schreiben
		$_SESSION["user"]["login"] = true;
		$_SESSION["user"]["email"] = $email;
		$_SESSION["user"]["firstname"] = $firstname;
		$_SESSION["user"]["lastname"] = $lastname;
		$_SESSION["user"]["timestamp"] = time();
		return true;
	}
	
	public static function IsLoggedIn() {
		//Login Überprüfung (true/false)
		if (isset($_SESSION["user"]["login"]) && isset($_SESSION["user"]["timestamp"])) {
			if(($_SESSION["user"]["timestamp"] + (10 * 60)) < time()) {
				//Alle 10 Minuten neu Prüfen ob User exisitiert
				$email = $_SESSION["user"]["email"];
				$db = new DBAccess();
				$result = $db->get_user_all_where(array("email", "=", "'".$email."'"));
				$user = reset($result);
				if(!empty($user)) {
					$_SESSION["user"]["timestamp"] = time();
					return ($_SESSION["user"]["login"] == true);
				}
				else {
					//Eingeloggter User existert nicht mehr!!!
					UserHelper::Logout();
					return false;
				}
			}
			else {
				return ($_SESSION["user"]["login"] == true);
			}
		}
		else {
			return false;
		}
	}
	
	public static function GetUserEmail() {
		//Return email vom eingeloggten User
		if(UserHelper::IsLoggedIn()) {
			return $_SESSION["user"]["email"];
		} 
		else {
			return "";
		}
	}
	
	public static function GetUserName() {
		//Return Vorname+Nachname vom eingeloggten User
		if(UserHelper::IsLoggedIn()) {
			return $_SESSION["user"]["firstname"] . " " . $_SESSION["user"]["lastname"];
		} 
		else {
			return "";
		}
	}
	
	public static function Logout() {
		//Do Logout
		if(isset($_SESSION)) {
			session_destroy();
			$_SESSION = array();
		}
		return true;
	}
	
}

// #################################### Funktionen

function doUserRightsCheck() {
	//User Berechtigungsprüfung
	if(UserHelper::IsLoggedIn()) {
		//Erfolg bei Eingeloggtem Benutzer im Frontend
		return true;
	}
	else {
		//kein Eingeloggter User -> zurück leiten und Fehlermeldung
		addMessageIfNew("info", "Sie m&uuml;ssen sich zuerst einloggen, um die gew&uuml;nschte Seite anzeigen zu k&ouml;nnen.");
		$backurl = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'index.php#noreferer';
		Header('Location: '.$backurl);
		exit();
	}
}

function getLoginForm() {
/*
 * Gibt entweder das Login Fomular zurück, oder den Username mit Logout Button
 * Kapselt auch die Aufrufe für Registrierung und Login
 *
 */

$out = "";

if(UserHelper::IsLoggedIn()) {

	if(isset($_GET['logout'])) {
		UserHelper::Logout();
		Header('Location: index.php');
		exit();
	}
	else {
	  $out .= '<form action="'.$_SERVER['PHP_SELF'].'" class="navbar-form navbar-right" method="get">
	  		  <div class="form-group"><span style="color:darkgrey;margin-right:1em;">'.UserHelper::GetUserName().'</span>
		      <input type="hidden" name="logout" value="true" />
              <button type="submit" class="btn btn-success">Logout</button></div>
            </form>';
	}

}
else {

	if(isset($_POST['email']) && isset($_POST['pw'])) {
		$email = htmlentities($_POST['email']);
		$email = strtolower($email);
		$pwhash = hash('sha512', $_POST['pw'] . $email);
	  
		if(isset($_POST['register']) && isset($_POST['firstname']) && isset($_POST['pw2']) && isset($_POST['lastname'])) {
			//Register
			$firstname = htmlentities($_POST['firstname']);
			$lastname = htmlentities($_POST['lastname']);
			$pwhash2 = hash('sha512', $_POST['pw2'] . $email);
			
			if ($pwhash == $pwhash2 
			&& strlen($email)>0 
			&& strlen($firstname)>0
			&& strlen($lastname)>0 ) {
				try {
					$success = UserHelper::RegisterCustomer($email, $firstname, $lastname, $pwhash);
					if($success) {
						//Login
						try {
							UserHelper::Login($email, $pwhash);
						} catch (Exception $e) {
							//Exception bei Accountakivierung
							$_SESSION['message']['warning'][] = $e->getMessage();
						}
						$_SESSION['message']['success'][] = "Registrierung erfolgreich.";
						$backurl = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'index.php#noreferer';
						$backurl = (basename($backurl)==basename($_SERVER['SCRIPT_NAME'])) ? 'index.php#backlink' : $backurl;
						Header('Location: '.$backurl);
						exit();
					} 
					else {
						$_SESSION['message']['error'][] = "Registrierung fehlgeschlagen!";
					}
		    	} catch (Exception $e) {
		    		$_SESSION['message']['warning'][] = $e->getMessage();
		    	}
			}
			else {
		  	  $_SESSION['message']['warning'][] = "Bitte stellen sie sicher, dass alle Felder richtig ausgef&uuml;llt sind!";
			}
			
			Header('Location: '.$_SERVER['PHP_SELF']);
			exit();
			
		} else {
		  //Login
		  try {
		  	$success = UserHelper::Login($email, $pwhash);
		  	if(!$success) {
		  		$_SESSION['message']['error'][] = "Der Benutzername oder das Passwort ist falsch.";
		  	}
  		  } catch (Exception $e) {
  		  	//Exception bei Accountakivierung
  			$_SESSION['message']['warning'][] = $e->getMessage();
		  }
		  $backurl = $_SERVER['PHP_SELF'];
		  $backurl = (basename($backurl)=='register.php') ? 'index.php#backlink' : $backurl;
		  Header('Location: '.$backurl);
		  exit();
		}
	} 
	else {
	  //Login fomular zeigen
	  $out .= '<form action="'.$_SERVER['PHP_SELF'].'" class="navbar-form navbar-right" method="post">
            <div class="form-group">
              <input type="email" name="email" placeholder="Email" class="form-control">
            </div>
            <div class="form-group">
              <input type="password" name="pw" placeholder="Passwort" class="form-control">
            </div>
            <button type="submit" class="btn btn-success">Login</button>';
       if(basename($_SERVER['SCRIPT_NAME']) != 'register.php') {
       	  //Registrieren Link nicht auf Registrieren Seite zeigen
    	  $out .= '<a href="register.php" style="margin-left:4px;" class="btn btn-default">Registrieren</a>';
       }
       $out .= '</form>';
	}

}

return $out;

}