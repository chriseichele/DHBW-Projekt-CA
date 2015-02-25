<?php 

require_once('./UserHelper.inc');
require_once('./CrtHelper.inc');

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

echo '<div class="container table-responsive">';
echo CrtHelper::getUserCertList();
echo '</div>';

include('./footer.inc'); ?>