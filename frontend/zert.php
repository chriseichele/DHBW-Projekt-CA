<?php 
require_once('./UserHelper.php');

doUserRightsCheck();

$pagetitle = "Zertifikat bestellen";

include('./header.php');

?>

<div class="jumbotron">
    <div class="container">
        <h1><?php echo $pagetitle; ?></h1>
        <p>Bitte laden Sie daf&uuml;r Ihre CSR-Datei f&uuml;r das Zertifikat hoch.</p>
    </div>
</div>
<div class="container">
	<form enctype="multipart/form-data" action="annahme.php" method="post">
		<div class="form-group">
    		<label for="laufzeit">W&auml;hlen Sie die gew&uuml;nschte Laufzeit(ab heute) f&uuml;r das Zertifikat aus:</label>
    		<select id="laufzeit" name="laufzeit" class="form-control" required="required">
  				<option value="1">1 Jahr Laufzeit</option>
  				<option value="2">2 Jahre Laufzeit</option>
  				<option value="3">3 Jahre Laufzeit</option>
  				<option value="4">4 Jahre Laufzeit</option>
  				<option value="5">5 Jahre Laufzeit</option>
			</select>
		</div> 
		<div class="form-group">
			<label for="dateihochladen">W&auml;hlen Sie eine CSR-Datei von Ihrem Rechner aus:</label>
			<input type="file" name="userfile"id="dateihochladen" size="50" maxlength="100000" required="required">
		</div> 
		<div class="checkbox">
    		<label>
      			<input type="checkbox" name="wildcard"> Wildcard Zertifikat f&uuml;r meine Domain erstellen
    		</label>
  		</div>
		<div class="form-group" id="add_sans">
			<style>#add_sans input, #add_sans button {display: block;min-width:200px}</style>
			<label>Weitere SANs hinzuf&uuml;gen:</label>
			<input type="text" name="sans[san0]" placeholder="weiterer SAN" />
			<input type="text" name="sans[san1]" placeholder="weiterer SAN" />
			<button type="button" id="add_san_line" class="btn btn-default btn-sm" onclick="addSANline();false;">Zeile hinzuf&uuml;gen</button>
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

<script>
function addSANline() {
	$("#add_sans button").before('<input type="text" name="sans[san' + $("#add_sans input").length + ']" placeholder="weiterer SAN" />');
}
</script>