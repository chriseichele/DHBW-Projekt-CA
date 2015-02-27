<?php 
require_once('./UserHelper.php');

doUserRightsCheck();

$pagetitle = "Standard Zertifikat";

include('./header.php');

?>

<div class="jumbotron">
    <div class="container">
        <h1>Standard Zertifikat bestellen</h1>
        <p>Bitte laden Sie daf&uuml;r Ihre CSR-Datei f&uuml;r das Standard-Zertifikat hoch.</p>
    </div>
</div>
<div class="container">
	<form enctype="multipart/form-data" action="annahme.php" method="post">
		<input type="hidden" name="zerttype" value="normal" />
		<div class="form-group">
    		<label for="laufzeit">W&auml;hlen Sie die gew&uuml;nschte Laufzeit f&uuml;r das Zertifikat aus:</label>
    		<select id="laufzeit" name="laufzeit" class="form-control">
  				<option value="1">1 Jahr Laufzeit</option>
  				<option value="3">3 Jahre Laufzeit</option>
  				<option value="5">5 Jahre Laufzeit</option>
			</select>
		</div> 
		<div class="form-group">
			<label for="dateihochladen">W&auml;hlen Sie eine CSR-Datei von Ihrem Rechner aus:</label>
			<input type="file" name="userfile"id="dateihochladen" size="50" maxlength="100000">
		</div> 
		<br />
        <div class="form-horizontal"> 
        	<div class="form-group container">  
				<input type="submit" class="btn btn-lg btn-primary" style="margin-bottom:10px;" value="Zertifikat bestellen" />
        		<a href="csrtutorial.php" class="btn btn-lg btn-default" style="margin-bottom:10px;">Wie erstelle ich ein CSR?</a>
			</div> 
		</div>       
	</form>
</div>

<?php include('./footer.php'); ?>