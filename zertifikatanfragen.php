<?php 

include('UserHelper.inc');

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
$csr = $db->get_request_all_where($where);



$pagetitle = "Zertifikatanfrage genehmigen";

include('./header.inc');

?>
<div class="jumbotron">
      <div class="container">
        <h1>Zertifikatanfragen</h1>
        <p>Bitte nehmen Sie die Zertifikatanfragen an oder lehnen Sie diese ab!</p>
        <table class='table table-bordered'><?php foreach($csr as $key => $value){echo'<tr><th>'.$key.'</th><td>'.$value.'</td></tr>';}?></table>
        <p><a class="btn btn-primary btn-lg" href="#" role="button">Anfrage annehmen</a>
        	<a class="btn btn-danger btn-lg" href="#" role="button">Anfrage ablehnen</a></p>        
      </div>
    </div>
<?php include('./footer.inc'); ?>