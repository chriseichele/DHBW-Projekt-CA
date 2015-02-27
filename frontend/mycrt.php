<?php 

require_once('./UserHelper.php');
require_once('./CrtHelper.php');

doUserRightsCheck();

$pagetitle = "Meine Zertifikate";

include('./header.php');

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

include('./footer.php'); ?>