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
	<div class=" table-responsive">
		<table class='table table-hover table-bordered'>
			<?php foreach($request as $key => $value){
				if($key != 'id' && $key != 'requester' && $key != 'verifier' && $key != 'path_csr' && $key != 'path_cer') { //ID & Requester & Verifier & Pfade nicht mit anzeigen
					echo'<tr><th>'.$key.'</th><td>'.$value.'</td></tr>';
				}
			}?>
			<?php foreach($sans as $key => $value){echo'<tr><th>san '.($key+1).'</th><td>'.$value->name.'</td></tr>';}?>
		</table>
	</div>
</div>
<div class="container">
	<div class="alert alert-warning">
		<h3 style="margin-top:0;">Vorsicht</h3>
		<p>
			Beim verl&auml;ngern eines Zertifikats wird der alte Schl&uuml;ssel wieder verwendet. 
			<br/>W&auml;hlen Sie diese Option nicht, wenn Ihr altes Zertifikat kompromittiert wurde!
		</p>
	</div>
	<form enctype="multipart/form-data" action="annahme_renew.php" method="post">
		<input type="hidden" name="zerttype" value="<?php if($request->intermediate == 1){echo'intermediate';}elseif($request->intermediate == null){echo'normal';} ?>" />
		<div class="form-group">
    		<label for="laufzeit">W&auml;hlen Sie die gew&uuml;nschte Laufzeit f&uuml;r das neue Zertifikat aus:</label>
    		<select id="laufzeit" name="laufzeit" class="form-control">
    			<?php if($request->intermediate == null){echo'<option value="1">1 Jahr Laufzeit</option>';} ?>
  				<option value="3">3 Jahre Laufzeit</option>
  				<option value="5">5 Jahre Laufzeit</option>
    			<?php if($request->intermediate == 1){echo'<option value="10">10 Jahre Laufzeit</option>';} ?>
			</select>
		</div> 
		<input type="hidden" name="csr" value="<?php echo $csr_id; ?>" />
		<br />
        <div class="form-horizontal"> 
        	<div class="form-group container">  
				<input type="submit" class="btn btn-lg btn-primary" style="margin-bottom:10px;" value="Zertifikat neu anfordern" />
				<?php if($request->intermediate == 1){
					echo'<a href="intermediate.php" class="btn btn-lg btn-default" style="margin-bottom:10px;">Zertifikat mit neuem CSR anfordern</a>';
				}elseif($request->intermediate == null){
					echo'<a href="normaleszert.php" class="btn btn-lg btn-default" style="margin-bottom:10px;">Zertifikat mit neuem CSR anfordern</a>';
				}?>
			</div> 
		</div>       
	</form>
</div>

<?php include('./footer.php'); ?>