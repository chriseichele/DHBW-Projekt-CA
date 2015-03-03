<?php

include('UserHelper.php');

doAdminRightsCheck();

$pagetitle = "Informationssystem";

include('./header.php');

$db = new DBAccess();

echo '<div class="container">';

echo '<h1>'.$pagetitle.'</h1>';
//Inhaltsverzeichnis
echo '<nav><ul>';
echo '  <li><a href="#db">Datenbank</a><ul>';
echo '    <li><a href="#db_role">Rollen</a></li>';
echo '    <li><a href="#db_user">User</a></li>';
echo '    <li><a href="#db_requests">Requests</a></li>';
echo '    <li><a href="#db_sans">SANs</a></li>';
echo '  </ul></li>';
echo '  <li><a href="#session">SESSION</a></li>';
echo '  <li><a href="#files">Filesystem</a><ul>';
echo '    <li><a href="#files_csr">CSRs</a></li>';
echo '    <li><a href="#files_crt">CRTs</a></li>';
echo '  </ul></li>';
echo '</ul></nav>';

echo '<h2 id="db">DB-Info</h2>';

echo '<h3 id="db_role">Rollen</h3>';
echo '<pre>';
print_r($db->get_role_all());
echo '</pre>';

echo '<h3 id="db_user">User</h3>';
echo '<pre>';
print_r($db->get_user_all());
echo '</pre>';

echo '<h3 id="db_request">Requests</h3>';
echo '<pre>';
print_r($db->get_request_all());
echo '</pre>';

echo '<h3 id="db_sans">SANs</h3>';
echo '<pre>';
print_r($db->get_sans_all());
echo '</pre>';

echo '<h2 id="session">SESSION</h2>';
echo '<pre>';
print_r($_SESSION);
echo '</pre>';

echo '<h2 id="files">Dateisystem</h2>';

echo '<h3 id="files_csr">CSRs</h3>';
$pathToCSR = 'c:\apache24\ca\kunden\csr\\';
echo '<p>'.$pathToCSR.'</p>';
echo '<pre>';
print_r(scandir($pathToCSR));
echo '</pre>';

echo '<h3 id="files_crt">CRTs</h3>';
$pathToCRT = 'c:\apache24\ca\kunden\crt\\';
echo '<p>'.$pathToCRT.'</p>';
echo '<pre>';
print_r(scandir($pathToCRT));
echo '</pre>';

echo '</div>';

include('./footer.php'); ?>