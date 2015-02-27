<?php 

require_once('./UserHelper.php');
require_once('./CrtHelper.php');

doAdminRightsCheck();

$pagetitle = "Meine bearbeiteten Zertifikatsanfragen";

include('./header.php');

?>
<div class="jumbotron">
	<div class="container">
		<h1>Meine bearbeiteten Zertifikatsanfragen</h1>
		<p>&Uuml;bersicht aller von mir bearbeiteten Zertifikats&shy;anfragen.</p>     
	</div>
</div>
<?php

echo '<div class="container table-responsive">';
echo CrtHelper::getVerifiedCSRList(UserHelper::GetUserEmail());
echo '</div>';

include('./footer.php'); ?>