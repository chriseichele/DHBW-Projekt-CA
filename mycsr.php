<?php 

require_once('./UserHelper.inc');
require_once('./CrtDownloader.inc');

doUserRightsCheck();

$pagetitle = "Meine Zertifikatsanfragen";

include('./header.inc');

echo '<div class="container">';
echo CrtDownloader::getUserRequestList();
echo '</div>';

include('./footer.inc'); ?>