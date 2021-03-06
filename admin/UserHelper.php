<?php
SESSION_START();

require_once('./db.php');
require_once('./MessageHandler.php');

class UserHelper {

	/* Keine Registrtrierung für neue Admins über die Seite
	public static function RegisterCustomer($email, $firstname, $lastname, $pwhash) {
		return UserHelper::Register($email, $firstname, $lastname, $pwhash, "customer");
	}
	public static function RegisterAdmin($email, $firstname, $lastname, $pwhash) {
		return UserHelper::Register($email, $firstname, $lastname, $pwhash, "administrator");
	}
	private static function Register($email, $firstname, $lastname, $pwhash, $role) {
		$db = new DBAccess();
		return $db->insert_user($email, $firstname, $lastname, $pwhash, $role) == array(); //erfolg leifert ein leeres Array
	}*/

	public static function Login($email, $pwhash) {
	
		$db = new DBAccess();
		
		$users = $db->get_user_all_where(array("email", "=", "'".$email."'"));
		$user = reset($users);
		
		if ($user == null) {
			return false;
		} 
		else {
			//Check AdminRole
			if($user->role == "administrator") {
				//Check PW and do Login
				if($pwhash == $user->passwordhash) {
					return UserHelper::DoLogin($user->email, $user->firstname, $user->surname);
				} 
				else {
					return false;
				}
			}
			else {
				return false;
			}
		}
		//Return Boolean Success
	}

	private static function DoLogin($email, $firstname, $lastname) {
		$_SESSION["admin"]["login"] = true;
		$_SESSION["admin"]["email"] = $email;
		$_SESSION["admin"]["firstname"] = $firstname;
		$_SESSION["admin"]["lastname"] = $lastname;
		$_SESSION["admin"]["timestamp"] = time();
		return true;
	}
	
	public static function IsLoggedIn() {
		if (isset($_SESSION["admin"]["login"]) && isset($_SESSION["admin"]["timestamp"])) {
			if(($_SESSION["admin"]["timestamp"] + (10 * 60)) < time()) {
				//Alle 10 Minuten neu Prüfen ob User exisitiert
				$email = $_SESSION["admin"]["email"];
				$db = new DBAccess();
				$result = $db->get_user_all_where(array("email", "=", "'".$email."'"));
				$user = reset($result);
				if(!empty($user)) {
					$_SESSION["admin"]["timestamp"] = time();
					return ($_SESSION["admin"]["login"] == true);
				}
				else {
					//Eingeloggter User existert nicht mehr!!!
					UserHelper::Logout();
					return false;
				}
			}
			else {
				return ($_SESSION["admin"]["login"] == true);
			}
		}
		else {
			return false;
		}
	}
	public static function IsAdminLoggedIn() {
		if (UserHelper::IsLoggedIn()) {
			$email = $_SESSION["admin"]["email"];
			$db = new DBAccess();
			$result = $db->get_user_all_where(array("email", "=", "'".$email."'"));
			$user = reset($result);
			return ($user->role == "administrator");
		}
		else {
			return false;
		}
	}
	
	public static function GetUserEmail() {
		if(UserHelper::IsLoggedIn()) {
			return $_SESSION["admin"]["email"];
		} 
		else {
			return "";
		}
	}
	
	public static function GetUserName() {
		if(UserHelper::IsLoggedIn()) {
			return $_SESSION["admin"]["firstname"] . " " . $_SESSION["admin"]["lastname"];
		} 
		else {
			return "";
		}
	}
	
	public static function Logout() {
		if(isset($_SESSION)) {
			session_destroy();
			$_SESSION = array();
		}
		return true;
	}
	
}

// #################################### Funktionen

/* Keine Normalen User im Admin Bereich
function doUserRightsCheck() {
	if(UserHelper::IsLoggedIn()) {
		return true;
	}
	else {
		$_SESSION['message']['info'][] = "Sie m&uuml;ssen sich zuerst einloggen, um die gew&uuml;nschte Seite anzeigen zu k&ouml;nnen.";
		$backurl = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'index.php#noreferer';
		Header('Location: '.$backurl);
		exit();
	}
}*/
function doAdminRightsCheck() {
	if(UserHelper::IsAdminLoggedIn()) {
		return true;
	}
	else {
		addMessageIfNew("info", "Sie m&uuml;ssen sich zuerst als Administrator einloggen, um die gew&uuml;nschte Seite anzeigen zu k&ouml;nnen.");
		Header('Location: index.php');
		exit();
	}
}

function getLoginForm() {

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
		$email = $_POST['email'];
		$email = strtolower($email);
		$pwhash = hash('sha512', $_POST['pw'] . $email);
	  
		/* Keine Registrierung für Admins über die Seite
		if(isset($_POST['register']) && isset($_POST['firstname']) && isset($_POST['pw2']) && isset($_POST['lastname'])) {
			//Register
			$firstname = $_POST['firstname'];
			$lastname = $_POST['lastname'];
			$pwhash2 = hash('sha512', $_POST['pw2'] . $email);
			
			if ($pwhash == $pwhash2 
			&& strlen($email)>0 
			&& strlen($firstname)>0
			&& strlen($lastname)>0 ) {
				$success = UserHelper::RegisterCustomer($email, $firstname, $lastname, $pwhash);
				//$success = UserHelper::RegisterAdmin($email, $firstname, $lastname, $pwhash); //Befehl nur zu Testzwecken enthalten, wird auskommentiert und Admins später manuell angelegt
				if($success) {
		  	  		UserHelper::Login($email, $pwhash);
		  	  		$_SESSION['message']['success'][] = "Registrierung erfolgreich.";
					$backurl = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'index.php#noreferer';
					$backurl = (basename($backurl)==basename($_SERVER['SCRIPT_NAME'])) ? 'index.php#backlink' : $backurl;
					Header('Location: '.$backurl);
					exit();
		    	} 
		    	else {
		  	  		$_SESSION['message']['error'][] = "Registrierung fehlgeschlagen!";
		    	}
			}
			else {
		  	  $_SESSION['message']['warning'][] = "Bitte stellen sie sicher, dass alle Felder richtig ausgef&uuml;llt sind!";
			}
			
			Header('Location: '.$_SERVER['PHP_SELF']);
			exit();
			
		} else { */
		  //Login
		  $success = UserHelper::Login($email, $pwhash);
		  if(!$success) {
		  	$_SESSION['message']['error'][] = "Der Benutzername oder das Passwort ist falsch.";
		  }
		  $backurl = $_SERVER['PHP_SELF'];
		  $backurl = (basename($backurl)=='register.php') ? 'index.php#backlink' : $backurl;
		  Header('Location: '.$backurl);
		  exit();
		/*}*/
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
       /* Keine Registrierung für Admins über die Seite
       if(basename($_SERVER['SCRIPT_NAME']) != 'register.php') {
       	  //Registrieren Link nicht auf Registrieren Seite zeigen
    	  $out .= '<a href="register.php" style="margin-left:4px;" class="btn btn-default">Registrieren</a>';
       }*/
       $out .= '</form>';
	}

}

return $out;

}