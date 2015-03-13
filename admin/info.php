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
echo '    <li><a href="#db_request">Requests</a></li>';
echo '    <li><a href="#db_sans">SANs</a></li>';
echo '  </ul></li>';
echo '  <li><a href="#session">SESSION</a></li>';
echo '  <li><a href="#files">Filesystem</a><ul>';
echo '    <li><a href="#files_csr">CSRs</a></li>';
echo '    <li><a href="#files_crt">CRTs</a></li>';
echo '  </ul></li>';
echo '  <li><a href="#files">Logs</a><ul>';
echo '    <li><a href="#logs_admin_crtlog">Admin CRT-Log</a></li>';
echo '    <li><a href="#logs_admin_maillog">Admin Mail-Log</a></li>';
echo '    <li><a href="#logs_frontend_maillog">Frontend Mail-Log</a></li>';
echo '  </ul></li>';
echo '</ul></nav>';

//CSS
echo '<style>pre{display:inline-block;min-width:100%;}</style>';

//Ausgaben
echo '<h2 id="db">DB-Info</h2>';

echo '<h3 id="db_role">Rollen</h3>';
echo '<div class="table-responsive"><pre>';
print_r($db->get_role_all());
echo '</pre></div>';

echo '<h3 id="db_user">User</h3>';
echo '<div class="table-responsive"><pre>';
print_r($db->get_user_all());
echo '</pre></div>';

echo '<h3 id="db_request">Requests</h3>';
echo '<div class="table-responsive"><pre>';
print_r($db->get_request_all());
echo '</pre></div>';

echo '<h3 id="db_sans">SANs</h3>';
echo '<div class="table-responsive"><pre>';
print_r($db->get_sans_all());
echo '</pre></div>';

echo '<h2 id="session">SESSION</h2>';
echo '<div class="table-responsive"><pre>';
print_r($_SESSION);
echo '</pre></div>';

echo '<h2 id="files">Dateisystem</h2>';

echo '<h3 id="files_csr">CSRs</h3>';
$pathToCSR = 'c:\apache24\ca\kunden\csr\\';
echo '<p>'.$pathToCSR.'</p>';
echo '<div class="table-responsive"><pre>';
print_r(scandir($pathToCSR));
echo '</pre></div>';

echo '<h3 id="files_crt">CRTs</h3>';
$pathToCRT = 'c:\apache24\ca\kunden\crt\\';
echo '<p>'.$pathToCRT.'</p>';
echo '<div class="table-responsive"><pre>';
print_r(scandir($pathToCRT));
echo '</pre></div>';

echo '<h2 id="logs">Logs</h2>';

echo '<h3 id="logs_admin_crtlog">Admin CRT-Log</h3>';
echo '<div class="table-responsive"><pre>';
$admin_crtlog = "c:\apache24\logs\admin_crtlog.log";
if(file_exists($admin_crtlog)) {
	print_r(file_get_contents($admin_crtlog));
}
echo '</pre></div>';

echo '<h3 id="logs_admin_maillog">Admin Mail-Log</h3>';
echo '<div class="table-responsive"><pre>';
$admin_maillog = "c:\apache24\logs\admin_maillog.log";
if(file_exists($admin_maillog)) {
	print_r(file_get_contents($admin_maillog));
}
echo '</pre></div>';

echo '<h3 id="logs_frontend_maillog">Frontend Mail-Log</h3>';
echo '<div class="table-responsive"><pre>';
$frontend_maillog = "c:\apache24\logs\\frontend_maillog.log";//Achtung '\f' ist eine definierte PHP Escape Sewuenz
if(file_exists($frontend_maillog)) {
	print_r(file_get_contents($frontend_maillog));
}
echo '</pre></div>';

echo '</div>';

include('./footer.php'); ?>