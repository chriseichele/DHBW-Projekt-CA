<?php 
require_once('./UserHelper.php');
require_once('./db.php');

doUserRightsCheck();

$backurl = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'mycrt.php#noreferer';
$backurl = (basename($backurl)==basename($_SERVER['SCRIPT_NAME'])) ? 'mycrt.php#backlink' : $backurl;

//CSR ID gesetzt
if(isset($_GET['csr'])) {
	$csr_id = $_GET['csr'];
	//Prüfen ob Zertifikat dem User gehört
	$db = new DBAccess();
	$where = array("requester","=","'".UserHelper::GetUserEmail()."'"," AND ","id","=","'".$csr_id."'");
	$requests = $db->get_request_all_where($where);
	$request = reset($requests);
	if($request->id == $csr_id) {
		//Alles OK
	}
	else {
		//Zertifikat gehört nicht dem User (oder ID ist quatsch)
		$_SESSION['message']['warning'][] = 'Sie haben kein g&uuml;ltiges Ausgangszertifikat ausgew&auml;hlt.';
		Header('Location: '.$backurl);
		exit();
	}
}
else {
	$_SESSION['message']['warning'][] = 'Sie haben kein Ausgangszertifikat ausgew&auml;hlt.';
	Header('Location: '.$backurl);
	exit();
}

//Get SANs zu CSR
$where = array("request_id","=","'".$csr_id."'");
$sans = $db->get_sans_all_where($where);

$pagetitle = "Zertifikat neu anfordern";

include('./header.php');

?>

<div class="jumbotron">
    <div class="container">
        <h1><?php echo $pagetitle; ?></h1>
        <p>Das Zertifikat f&uuml;r einen neuen Zeitraum ab heute anfordern</p>
    </div>
</div>
<div class="container">
	<?php
		require_once('./function_viewcsr.php');
		echo displayCSRtable($request, $sans, false);
	?>
</div>
<div class="container">
	<div class="alert alert-warning">
		<h3 style="margin-top:0;">Vorsicht</h3>
		<p>
			Beim Verl&auml;ngern eines Zertifikats wird der alte Schl&uuml;ssel wieder verwendet. 
			<br/>W&auml;hlen Sie diese Option nicht, wenn Ihr altes Zertifikat kompromittiert wurde!
		</p>
	</div>
	<form enctype="multipart/form-data" action="annahme_renew.php" method="post">
		<div class="form-group">
    		<label for="laufzeit">W&auml;hlen Sie die gew&uuml;nschte Laufzeit(ab heute) f&uuml;r das neue Zertifikat aus:</label>
    		<select id="laufzeit" name="laufzeit" class="form-control">
  				<option value="0.25">&frac14; Jahr Laufzeit</option>
  				<option value="0.5">&frac12; Jahr Laufzeit</option>
  				<option value="0.75">&frac34; Jahr Laufzeit</option>
  				<option value="1" selected="selected">1 Jahr Laufzeit</option>
  				<option value="2">2 Jahre Laufzeit</option>
  				<option value="3">3 Jahre Laufzeit</option>
  				<option value="4">4 Jahre Laufzeit</option>
  				<option value="5">5 Jahre Laufzeit</option>
			</select>
		</div> 
		<input type="hidden" name="csr" value="<?php echo $csr_id; ?>" />
		<div class="form-group" id="add_sans">
			<style>#add_sans input, #add_sans button {display: block;min-width:200px;padding:3px;}</style>
			<label>Weitere SANs hinzuf&uuml;gen:</label>
			<input type="text" name="sans[0]" placeholder="SAN 1" />
			<input type="text" name="sans[1]" placeholder="SAN 2" />
			<button type="button" id="add_san_line" class="btn btn-default btn-sm" onclick="addSANline();false;">Zeile hinzuf&uuml;gen</button>
		</div> 
		<br />
        <div class="form-horizontal"> 
        	<div class="form-group container">  
				<input type="submit" class="btn btn-lg btn-primary" style="margin-bottom:10px;" value="Zertifikat neu anfordern" />
				<a href="zert.php" class="btn btn-lg btn-default" style="margin-bottom:10px;">Zertifikat mit neuem CSR anfordern</a>
			</div> 
		</div>       
	</form>
</div>

<?php include('./footer.php'); ?>

<script>
function addSANline() {
	$("#add_sans button").before('<input type="text" name="sans[' + $("#add_sans input").length + ']" placeholder="SAN '+($("#add_sans input").length+1)+'" />');
}
</script>