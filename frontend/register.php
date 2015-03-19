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
        	<input type="email" name="email" placeholder="Email" class="form-control" required="required">
    	</div>
  		<div class="form-group">
        	<input type="text" name="firstname" placeholder="Vorname" class="form-control" required="required">
    	</div>
  		<div class="form-group">
        	<input type="text" name="lastname" placeholder="Nachname" class="form-control" required="required">
    	</div>
  		<div class="form-group" style="margin-bottom:5px;">
    		<input id="passwordInput" type="password" name="pw" placeholder="Passwort" class="form-control" required="required">
    	</div>
    	<div class="progress" style="margin-bottom:5px;height: 0.5em;font-size: 0.5em;margin-left: 1px;margin-right: 1px;">
  			<div id="passwordStrength" class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
		</div>
  		<div class="form-group">
    		<input id="passwordInputConfirm" type="password" name="pw2" placeholder="Passwort wiederholen" class="form-control" required="required">
    	</div>
  		<div class="form-group">
        	<input type="submit" class="btn btn-success" name="register" value="Registrieren"/>
    	</div>
      </form>
    </div>
      
<?php include('./footer.php'); ?>

<script>

$(document).ready(function() {
 
    $('#passwordInput, #passwordInputConfirm').on('keyup', function(e) {
 
        if($('#passwordInput').val() == '' && $('#passwordInputConfirm').val() == '')
        {
            $('#passwordStrength').removeClass().addClass('progress-bar progress-bar-striped active progress-bar-danger').html('').css('width','0%').attr('aria-valuenow','0');
            return false;
        }
 
     if($('#passwordInput').val() != '' && $('#passwordInputConfirm').val() != '' && $('#passwordInput').val() != $('#passwordInputConfirm').val())
    	{
    		$('#passwordStrength').removeClass().addClass('progress-bar progress-bar-striped active progress-bar-danger')
        	return false;
    	}
 
        // Must have capital letter, numbers and lowercase letters
        var strongRegex = new RegExp("^(?=.{8,})(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*\\W).*$", "g");
 
        // Must have either capitals and lowercase letters or lowercase and numbers
        var mediumRegex = new RegExp("^(?=.{7,})(((?=.*[A-Z])(?=.*[a-z]))|((?=.*[A-Z])(?=.*[0-9]))|((?=.*[a-z])(?=.*[0-9]))).*$", "g");
 
        // Must be at least 6 characters long
        var okRegex = new RegExp("(?=.{6,}).*", "g");
 
        if (okRegex.test($(this).val()) === false) {
            // If ok regex doesn't match the password
        	$('#passwordStrength').removeClass().addClass('progress-bar progress-bar-striped active progress-bar-danger').css('width','20%').attr('aria-valuenow','20');
 
        } else if (strongRegex.test($(this).val())) {
            // If reg ex matches strong password
            $('#passwordStrength').removeClass().addClass('progress-bar progress-bar-striped active progress-bar-success').css('width','100%').attr('aria-valuenow','100');
        } else if (mediumRegex.test($(this).val())) {
            // If medium password matches the reg ex
            $('#passwordStrength').removeClass().addClass('progress-bar progress-bar-striped active progress-bar-info').css('width','70%').attr('aria-valuenow','70');
        } else {
            // If password is ok
            $('#passwordStrength').removeClass().addClass('progress-bar progress-bar-striped active progress-bar-warning').css('width','50%').attr('aria-valuenow','50');
        }
        
        return true;
    });
});

</script>