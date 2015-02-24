<?php 

$pagetitle = "Standard Zertifikat";

include('./header.inc');

?>
<div class="jumbotron">
      <div class="container">
        <h1>Bestellen Sie sich ein Standard Zertifikat</h1>
        <p>Laden Sie bitte daf&uuml;r Ihre csr-Datei für das Standard Zertifikat hoch</p>
        
        <form enctype="multipart/form-data" action="annahme.php" method="post">
        <input type="hidden" name="zerttype" value="normal" />
        <div class="form-group">
 			<div class="radio">
  				<label>
    				<input type="radio" name="laufzeit" id="optionsRadios1" value=1>
    					1 Jahr Laufzeit
  				</label>
		</div>
		<div class="radio">
  				<label>
    				<input type="radio" name="laufzeit" id="optionsRadios2" value=3>
						3 Jahre Laufzeit
  				</label>
		</div>
		<div class="radio">
  				<label>
    				<input type="radio" name="laufzeit" id="optionsRadios3" value=5>
    					5 Jahre Laufzeit
  				</label>
		</div>

    		<label for="dateihochladen">W&auml;hlen Sie eine CSR-Datei von Ihrem Rechner aus:</label>
    		<input type="file" name="userfile"id="dateihochladen" size="50" maxlength="100000">
    		<p class="help-block">Hier haben Sie die M&ouml;glichkeit eine Datei hochzuladen.</p>
    		<p><input type="submit" value="absenden" /></p>
  		</div>       
 		</form>
      </div>
    </div>
<?php include('./footer.inc'); ?>