<?php 

require_once('./UserHelper.php');

doAdminRightsCheck();

$backurl = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'validatedCSRlist.php#NoReferer';
$backurl = (basename($backurl)=='zertifikatsanfragen.php') ? $backurl : 'openCSRlist.php#Backlink';

if(isset($_GET['csr'])) {
	$csr_id = $_GET['csr'];
} else {
  	$_SESSION['message']['warning'][] = "Bitte w&auml;hlen Sie einen CSR aus!";
  	header('Location: '.$backurl);
  	exit();
}
$db = new DBAccess();
$where = array("id","=","'".$csr_id."'");
$dbresult = $db->get_request_all_where($where);
if($dbresult == array()) {
  	$_SESSION['message']['warning'][] = "Der gew&auml;hle CSR ist nicht vorhanden!";
  	header('Location: '.$backurl);
  	exit();
}
$csrs = reset($dbresult);
$csr = get_object_vars($csrs);

$where = array("request_id","=","'".$csr_id."'");
$sans = $db->get_sans_all_where($where);

if($csr['status'] != 'created') {
  	$_SESSION['message']['warning'][] = "CSR wurde bereits bearbeitet!";
  	header('Location: viewCSR.php?csr='.$csr_id);
  	exit();
}

$pagetitle = "Zertifikatanfrage genehmigen";

include('./header.php');

?>
<div class="jumbotron">
      <div class="container">
        <h1><?php echo $pagetitle; ?></h1>
        <p>Bitte nehmen Sie die Zertifikat&shy;anfragen an oder lehnen Sie diese ab.</p>        
      </div>
</div>
<div class="container">
	<div class=" table-responsive">
		<table class='table table-hover table-bordered'>
			<?php foreach($csr as $key => $value){echo'<tr><th>'.$key.'</th><td>'.$value.'</td></tr>';}?>
			<?php foreach($sans as $key => $value){echo'<tr><th>san '.($key+1).'</th><td>'.$value->name.'</td></tr>';}?>
		</table>
	</div>
    <form method="post" action="validateCSR.php" class="clearfix" style="border:1px solid #ccc;padding-top:15px;">
    	<div style="margin:0 15px 0.5em 15px;">
    		<textarea name="comment" placeholder="Entscheidungsbegr&uuml;ndung" style="width:100%;min-width:100%;max-width:100%;" required="required"></textarea>
		</div>
		<style>.form-inline .form-group {margin-bottom:15px;}</style>
		<div class="form-inline">
			<input type="hidden" name="csr" value="<?php echo $csr_id; ?>">
			<div class="form-group col-md-6">
				<button type="submit" name="accept" value="true" class="btn btn-success btn-lg btn-block" role="button">Anfrage annehmen</a>
			</div>
			<div class="form-group col-md-6">
				<button type="submit" name="accept" value="false" class="btn btn-danger btn-lg btn-block" role="button">Anfrage ablehnen</a>
			</div>
    	</div>
    </form>
</div>
<?php include('./footer.php'); ?>