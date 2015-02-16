<?php 

$pagetitle = "Registrierung";

include('./header.inc');

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
      
<?php include('./footer.inc'); ?>