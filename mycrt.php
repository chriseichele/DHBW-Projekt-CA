<?php 

require_once('./UserHelper.inc');
require_once('./CrtDownloader.inc');

doUserRightsCheck();

$pagetitle = "Meine Zertifikate";

include('./header.inc');

?>
<div class="jumbotron">
    <div class="container">
        <h1>&Uuml;bersicht meiner erworbenen Zertifikate</h1>
        <p>Mit dem dazugeh&ouml;igen Link k&ouml;nnen Sie Ihre Zertifikate downloaden.</p>    
    </div>
</div>
<?php 

echo '<div class="container">';
echo CrtDownloader::getUserCertList();
echo '</div>';

include('./footer.inc'); ?>