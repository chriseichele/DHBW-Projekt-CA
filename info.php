<?php

include('UserHelper.inc');

doAdminRightsCheck();

$pagetitle = "Datenbank- &amp; Session Information";

include('./header.inc');

$db = new DBAccess();

echo '<pre class="container">';

echo '<h2>DB-Info</h2>';
echo '<hr />';

echo '<h3>Rollen</h3>';
print_r($db->get_role_all());
echo '<hr />';

echo '<h3>User</h3>';
print_r($db->get_user_all());
echo '<hr />';

echo '<h3>Requests</h3>';
print_r($db->get_request_all());
echo '<hr />';

echo '<h3>Sans</h3>';
print_r($db->get_sans_all());
echo '<hr />';

echo '<h2>SESSION-Info</h2>';
echo '<hr />';

echo '<h3>Session</h3>';
print_r($_SESSION);
echo '<hr />';

echo '</pre>';

include('./footer.inc'); ?>