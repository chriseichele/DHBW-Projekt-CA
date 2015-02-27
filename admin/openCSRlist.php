<?php 

require_once('./UserHelper.php');
require_once('./CrtHelper.php');

doAdminRightsCheck();

$pagetitle = "Offene Zertifikatsanfragen";

include('./header.php');

?>
<div class="jumbotron">
	<div class="container">
		<h1>&Uuml;bersicht aller offenen Zertifikats&shy;anfragen</h1>
		<p>In der Detailansicht k&ouml;nnen diese genehmigt oder abgelehnt werden.</p>     
	</div>
</div>
<?php

echo '<div class="container table-responsive">';
echo CrtHelper::getOpenCSRList();
echo '</div>';

include('./footer.php'); ?>