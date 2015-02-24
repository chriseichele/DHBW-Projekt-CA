<?php 

$pagetitle = "Standard Zertifikat";

include('./header.inc');

?>
<div class="jumbotron">
      <div class="container">
        <h1>Bestellen Sie sich ein Standard Zertifikat</h1>
        <p>Laden Sie bitte daf&uuml;r Ihre csr-Datei für das Standard Zertifikat hoch</p>
        
        <form enctype="multipart/form-data" action="#" method="post">
        <div class="form-group">
    		<label for="dateihochladen">Datei hochladen</label>
    		<input type="file" name="csr"id="dateihochladen">
    		<p class="help-block">Hier haben Sie die M&ouml;glichkeit eine Datei hochzuladen.</p>
    		<p><input type="submit" value="absenden" /></p>
  		</div>       
 		</form>
      </div>
    </div>
<?php include('./footer.inc'); ?>