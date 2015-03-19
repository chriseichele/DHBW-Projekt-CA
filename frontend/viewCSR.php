<?php 

require_once('./UserHelper.php');

//User Eingeloggt?
doUserRightsCheck();


if(isset($_GET['csr'])) {
	$csr_id = $_GET['csr'];
} else {
  	$_SESSION['message']['warning'][] = "Bitte w&auml;hlen Sie einen CSR aus!";
  	header('Location: mycsr.php');
  	exit();
}
$db = new DBAccess();
$where = array("id","=","'".$csr_id."'");
$dbresult = $db->get_request_all_where($where);
$csrs = reset($dbresult);
$csr = get_object_vars($csrs);

if($dbresult == array()) {
	//Kein CSR gefunden
  	$_SESSION['message']['warning'][] = "Der gew&auml;hle CSR ist nicht vorhanden oder Sie d&uuml;rfen ihn nicht anzeigen!";
  	header('Location: mycsr.php');
  	exit();
}

//Mein Request?
$email = UserHelper::GetUserEmail();
if($csr['requester'] == $email) {
	$myrequest = true;
}
else {
	$myrequest = false;
  	$_SESSION['message']['warning'][] = "Der gew&auml;hle CSR ist nicht vorhanden oder Sie d&uuml;rfen ihn nicht anzeigen!";
  	header('Location: mycsr.php');
  	exit();
}

$where = array("request_id","=","'".$csr_id."'");
$sans = $db->get_sans_all_where($where);


$pagetitle = 'Zertifikatsanfrage "'.$csr['common_name'].'"';

include('./header.php');

?>
<div class="jumbotron">
      <div class="container">
        <h1><?php echo $pagetitle; ?></h1>
        <p>Hier finden sie alle Detail-Informationen zu der Zertifikatsanfrage.</p>        
      </div>
</div>
<div class="container">
	<?php
		require_once('./function_viewcsr.php');
		echo displayCSRtable($csr, $sans);
		
		if($myrequest) {
			//Zusätzliche Prüfung, Seite sollte aber eh nicht gezeigt werden, wenn nicht mein Request
			echo '<div class="form-inline container">';
    		if($csr['status'] == 'finished') {
    			echo '<div class="form-group col-md-6">';
    			echo '<a href="download.php?csr='.$csr_id.'" class="btn btn-success btn-lg btn-block" role="button">Zertifikat herunterladen</a>';
    			echo '</div>';
    		}
    		echo '<div class="form-group col-md-6">';
    		echo '<a href="CrtDownloader.php?downloadCSR='.$csr_id.'" class="btn btn-default btn-lg btn-block" role="button">CSR wieder herunterladen</a>';
    		echo '</div>';
    		if($csr['status'] == 'created') {
    			echo '<form action="delete.php" method="post" class="form-group col-md-6">';
    			echo '<input type="hidden" name="csr" value="'.$csr_id.'" />';
    			echo '<button onclick="confirm(\'Sind Sie sicher, dass Sie den CSR unwiderruflich l&ouml;schen m&ouml;chten?\');" class="btn btn-danger btn-lg btn-block" role="button">Request unwiderruflich l&ouml;schen</button>';
    			echo '</form>';
    		}
    		echo '</div>';
		}
	?>
</div>
<?php include('./footer.php'); ?>