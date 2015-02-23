<?php 

require_once('./UserHelper.inc');
require_once('./CrtHelper.inc');

doUserRightsCheck();

$pagetitle = "Offene Zertifikatsanfragen";

include('./header.inc');

?>
<div class="jumbotron">
	<div class="container">
		<h1>&Uuml;bersicht aller offenen Zertrifikats&shy;anfragen</h1>
		<p>In der Detailansicht k&ouml;nnen diese genehmigt oder abgelehnt werden.</p>     
	</div>
</div>
<?php

echo '<div class="container">';
echo CrtHelper::getOpenCSRList();
echo '</div>';

include('./footer.inc'); ?>