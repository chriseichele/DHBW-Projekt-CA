<?php 

require_once('./UserHelper.php');
require_once('./CrtHelper.php');

doAdminRightsCheck();

$pagetitle = "Offene Zertifikatsanfragen";

include('./header.php');

?>
<div class="jumbotron">
	<div class="container">
		<h1>&Uuml;bersicht offener Zertifikats&shy;anfragen</h1>
		<p>In der Detailansicht k&ouml;nnen diese genehmigt oder abgelehnt werden.
		<br/>Ihre eigenen Zertifikate werden hier nicht angezeigt.</p> 
	</div>
</div>
<?php

echo '<div class="container table-responsive">';
echo CrtHelper::getOpenCSRList(true);
echo '</div>';

include('./footer.php'); ?>