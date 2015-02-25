<?php 

require_once('./UserHelper.inc');
require_once('./CrtHelper.inc');

doAdminRightsCheck();

$pagetitle = "Meine bearbeiteten Zertifikatsanfragen";

include('./header.inc');

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

include('./footer.inc'); ?>