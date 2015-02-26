<?php 

require_once('./UserHelper.inc');

//User Eingeloggt?
doUserRightsCheck();


if(isset($_GET['csr'])) {
	$csr_id = $_GET['csr'];
} else {
  	$_SESSION['message']['warning'][] = "Bitte w&auml;hlen Sie einen CSR aus!";
  	header('Location: validatedCSRlist.php');
  	exit();
}
$db = new DBAccess();
$where = array("id","=","'".$csr_id."'");
$dbresult = $db->get_request_all_where($where);
$csrs = reset($dbresult);
$csr = get_object_vars($csrs);

if($dbresult == array()) {
  	$_SESSION['message']['warning'][] = "Der gew&auml;hle CSR ist nicht vorhanden!";
  	header('Location: validatedCSRlist.php');
  	exit();
}

//Mein Request?
$email = UserHelper::GetUserEmail();
if($csr['requester'] == $email) {
	$myrequest = true;
}
else {
	$myrequest = false;
	//Admins dürfen den Request dann trotzdem anzeigen lassen
	doAdminRightsCheck();
	//Ansonsten wird automatisch im Check zurück geleitet mit Fehlermeldung
}

$where = array("request_id","=","'".$csr_id."'");
$sans = $db->get_sans_all_where($where);


$pagetitle = "Zertifikatanfrage anzeigen";

include('./header.inc');

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
	<?php
		if($myrequest) {
			echo '<div class="form-inline">';
    		echo '<div class="form-group col-md-6">';
    		if($csr['status'] == 'finished') {
    			echo '<div class="form-group col-md-6">';
    			echo '<a href="CrtDownloader.php?downloadCRT='.$csr_id.'" class="btn btn-success btn-lg btn-block" role="button">Zertifikat herunterladen</a>';
    			echo '</div>';
    		}
    		echo '<a href="CrtDownloader.php?downloadCSR='.$csr_id.'" class="btn btn-default btn-lg btn-block" role="button">CSR wieder herunterladen</a>';
    		echo '</div>';
    		echo '</div>';
		}
	?>
</div>
<?php include('./footer.inc'); ?>