<?php 

require_once('./UserHelper.inc');

doAdminRightsCheck();

if(isset($_GET['csr'])) {
	$csr_id = $_GET['csr'];
} else {
  	$_SESSION['message']['warning'][] = "Bitte w&auml;hlen Sie einen CSR aus!";
  	header('Location: openCSRlist.php');
  	exit();
}
$db = new DBAccess();
$where = array("id","=","'".$csr_id."'");
$dbresult = $db->get_request_all_where($where);
$csrs = reset($dbresult);
$csr = get_object_vars($csrs);

if($csr['status'] != 'created') {
  	$_SESSION['message']['warning'][] = "CSR wurde bereits bearbeitet!";
  	header('Location: viewCSR.php?csr='.$csr_id);
  	exit();
}



$pagetitle = "Zertifikatanfrage genehmigen";

include('./header.inc');

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
		</table>
	</div>
    <form method="post" action="validateCSR.php" class="form-inline">
    	<input type="hidden" name="csr" value="<?php echo $csr_id; ?>">
    	<div class="form-group col-md-6">
    		<button type="submit" name="accept" value="true" class="btn btn-success btn-lg btn-block" role="button">Anfrage annehmen</a>
    	</div>
    	<div class="form-group col-md-6">
    		<button type="submit" name="accept" value="false" class="btn btn-danger btn-lg btn-block" role="button">Anfrage ablehnen</a>
    	</div>
    </form>
</div>
<?php include('./footer.inc'); ?>