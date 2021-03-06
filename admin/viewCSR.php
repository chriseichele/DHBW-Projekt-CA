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
$csrs = reset($dbresult);
$csr = get_object_vars($csrs);

if($dbresult == array()) {
  	$_SESSION['message']['warning'][] = "Der gew&auml;hle CSR ist nicht vorhanden!";
  	header('Location: '.$backurl);
  	exit();
}

$where = array("request_id","=","'".$csr_id."'");
$sans = $db->get_sans_all_where($where);


$pagetitle = 'Zertifikatsanfrage ID "'.$csr_id.'" anzeigen';

include('./header.php');

?>
<div class="jumbotron">
      <div class="container">
        <h1><?php echo $pagetitle; ?></h1>
        <p>Hier finden sie alle Detail-Informationen zu der Zertifikatsanfrage.</p>       
      </div>
</div>
<div class="container">
	<div class=" table-responsive">
		<table class='table table-hover table-bordered'>
			<?php foreach($csr as $key => $value){echo'<tr><th>'.$key.'</th><td>'.$value.'</td></tr>';}?>
			<?php foreach($sans as $key => $value){echo'<tr><th>san '.($key+1).'</th><td>'.$value->name.'</td></tr>';}?>
		</table>
	</div>
</div>
<?php include('./footer.php'); ?>