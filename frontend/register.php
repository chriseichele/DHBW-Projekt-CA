<?php 

require_once('./UserHelper.php');
if(UserHelper::IsLoggedIn()) {
	$_SESSION['message']['info'][] = "Sie können sich nicht neu registrieren, da Sie bereits eingeloggt sind.";
	$backurl = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'index.php#noreferer';
	$backurl = (basename($backurl)==basename($_SERVER['SCRIPT_NAME'])) ? 'index.php#backlink' : $backurl;
	header('Location: '.$backurl);
	exit();
}

$pagetitle = "Registrierung";

include('./header.php');

?>
    
    <div class="jumbotron">
      <form class="container" method="post">
      	<h3>Bitte f&uuml;llen sie das Registrierungsformular aus</h3>
        <input type="hidden" name="register" value="true"/>
  		<div class="form-group">
        	<input type="email" name="email" placeholder="Email" class="form-control">
    	</div>
  		<div class="form-group">
        	<input type="text" name="firstname" placeholder="Vorname" class="form-control">
    	</div>
  		<div class="form-group">
        	<input type="text" name="lastname" placeholder="Nachname" class="form-control">
    	</div>
  		<div class="form-group">
    		<input type="password" name="pw" placeholder="Passwort" class="form-control">
    	</div>
  		<div class="form-group">
    		<input type="password" name="pw2" placeholder="Passwort wiederholen" class="form-control">
    	</div>
  		<div class="form-group">
        	<input type="submit" class="btn btn-success" name="register" value="Registrieren"/>
    	</div>
      </form>
    </div>
      
<?php include('./footer.php'); ?>