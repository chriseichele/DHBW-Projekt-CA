<?php 

require_once('./UserHelper.php');
require_once('./CrtHelper.php');

doUserRightsCheck();

$pagetitle = "Meine Zertifikatsanfragen";

include('./header.php');

?>
<div class="jumbotron">
	<div class="container">
		<h1>&Uuml;bersicht meiner eingereichten Zertifikats&shy;anfragen mit Status</h1>
		<p>Mit dem dazugeh&ouml;rigen Link können Sie Ihre Anfragen zu &Uuml;berpr&uuml;fung downloaden.</p>     
	</div>
</div>
<?php

echo '<div class="container table-responsive">';
echo CrtHelper::getUserRequestList();
echo '</div>';

include('./footer.php'); ?>