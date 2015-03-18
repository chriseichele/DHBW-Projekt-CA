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


$pagetitle = 'Zertifikatsanfrage "'.$csr['common_name'].'" herunterladen';

include('./header.php');

?>

<div class="jumbotron">
    <div class="container">
        <h1><?php echo $pagetitle; ?></h1>
        <p>W&auml;hlen sie den Dateityp f&uuml;r Ihr Zertifikat aus.</p>
    </div>
</div>
<div class="container">
	<a href="CrtDownloader.php?downloadCRT=<?php echo $csr_id; ?>&fileformat=Apache2" class="btn btn-success btn-lg btn-block" role="button">Zertifikat f&uuml;r <b>Apache2</b> herunterladen</a>
	<a href="CrtDownloader.php?downloadCRT=<?php echo $csr_id; ?>&fileformat=ngnix" class="btn btn-success btn-lg btn-block" role="button">Zertifikat f&uuml;r <b>ngnix</b> herunterladen</a>
	<a href="CrtDownloader.php?downloadCRT=<?php echo $csr_id; ?>&fileformat=generic" class="btn btn-success btn-lg btn-block" role="button">Zertifikat im <b>Generischen Format</b> herunterladen</a>
</div>

<?php include('./footer.php'); ?>