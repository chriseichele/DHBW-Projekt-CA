<?php

include('UserHelper.php');

doAdminRightsCheck();

$pagetitle = "CSR & CRT Dateisystem";

include('./header.php');

echo '<div class="container">';
echo '<h1>Dateisystem</h1>';

echo '<h2>CSRs</h2>';
$pathToCSR = 'c:\apache24\ca\kunden\csr\\';
echo '<p>'.$pathToCSR.'</p>';
echo '<pre>';
print_r(scandir($pathToCSR));
echo '</pre>';

echo '<h2>CRTs</h2>';
$pathToCRT = 'c:\apache24\ca\kunden\crt\\';
echo '<p>'.$pathToCRT.'</p>';
echo '<pre>';
print_r(scandir($pathToCRT));
echo '</pre>';

echo '</div>';

include('./footer.php');


?>