<?php 

require_once('./UserHelper.inc');
require_once('./CrtDownloader.inc');

doUserRightsCheck();

$pagetitle = "Meine Zertifikatsanfragen";

include('./header.inc');
?>
<div class="jumbotron">
	<div class="container">
		<h1>&Uuml;bersicht meiner eingereichten Zertrifikats&shy;anfragen mit Status</h1>
		<p>Mit dem dazugeh&ouml;rigen Link können Sie Ihre Anfragen zu &Uuml;berpr&uuml;fung downloaden.</p>     
	</div>
</div>
<?php
echo '<div class="container">';
echo CrtDownloader::getUserRequestList();
echo '</div>';

include('./footer.inc'); ?>