<?php

include('UserHelper.php');

doAdminRightsCheck();

$pagetitle = "Informationssystem";

include('./header.php');

$db = new DBAccess();

echo '<div class="container">';

echo '<h1>'.$pagetitle.'</h1>';

//CSS
echo '<style>pre{display:inline-block;min-width:100%;transition:height 0.7s;}</style>';

//Ausgaben
echo '<h2 id="db">DB-Info</h2>';

echo '<h3 id="db_role">';
echo '<button class="btn btn-sm btn-default" onclick="togglePre(\'#db_role_pre\');" title="Bereich Ein-/Ausblenden">TOGGLE</button>&nbsp;&nbsp;';
echo 'Rollen</h3>';
echo '<div class="table-responsive"><pre id="db_role_pre">';
print_r($db->get_role_all());
echo '</pre></div>';

echo '<h3 id="db_user">';
echo '<button class="btn btn-sm btn-default" onclick="togglePre(\'#db_user_pre\');" title="Bereich Ein-/Ausblenden">TOGGLE</button>&nbsp;&nbsp;';
echo 'User</h3>';
echo '<div class="table-responsive"><pre id="db_user_pre">';
print_r($db->get_user_all());
echo '</pre></div>';

echo '<h3 id="db_request">';
echo '<button class="btn btn-sm btn-default" onclick="togglePre(\'#db_request_pre\');" title="Bereich Ein-/Ausblenden">TOGGLE</button>&nbsp;&nbsp;';
echo 'Requests</h3>';
echo '<div class="table-responsive"><pre id="db_request_pre">';
print_r($db->get_request_all());
echo '</pre></div>';

echo '<h3 id="db_sans">';
echo '<button class="btn btn-sm btn-default" onclick="togglePre(\'#db_sans_pre\');" title="Bereich Ein-/Ausblenden">TOGGLE</button>&nbsp;&nbsp;';
echo 'SANs</h3>';
echo '<div class="table-responsive"><pre id="db_sans_pre">';
print_r($db->get_sans_all());
echo '</pre></div>';

echo '<h2 id="session">SESSION</h2>';
echo '<h3>';
echo '<button class="btn btn-sm btn-default" onclick="togglePre(\'#session_pre\');" title="Bereich Ein-/Ausblenden">TOGGLE</button>&nbsp;&nbsp;';
echo 'Session</h3>';
echo '<div class="table-responsive"><pre id="session_pre">';
print_r($_SESSION);
echo '</pre></div>';

echo '<h2 id="files">Dateisystem</h2>';

echo '<h3 id="files_csr">';
echo '<button class="btn btn-sm btn-default" onclick="togglePre(\'#files_csr_pre\');" title="Bereich Ein-/Ausblenden">TOGGLE</button>&nbsp;&nbsp;';
echo 'CSRs</h3>';
$pathToCSR = 'c:\apache24\ca\kunden\csr\\';
echo '<p>'.$pathToCSR.'</p>';
echo '<div class="table-responsive"><pre id="files_csr_pre">';
print_r(scandir($pathToCSR));
echo '</pre></div>';

echo '<h3 id="files_crt">';
echo '<button class="btn btn-sm btn-default" onclick="togglePre(\'#files_crt_pre\');" title="Bereich Ein-/Ausblenden">TOGGLE</button>&nbsp;&nbsp;';
echo 'CRTs</h3>';
$pathToCRT = 'c:\apache24\ca\kunden\crt\\';
echo '<p>'.$pathToCRT.'</p>';
echo '<div class="table-responsive"><pre id="files_crt_pre">';
print_r(scandir($pathToCRT));
echo '</pre></div>';

echo '<h2 id="logs">Logs</h2>';
echo '<p>c:\apache24\logs\...</p>';

echo '<h3 id="logs_crtlog">';
echo '<button class="btn btn-sm btn-default" onclick="togglePre(\'#logs_crtlog_pre\');" title="Bereich Ein-/Ausblenden">TOGGLE</button>&nbsp;&nbsp;';
echo 'CRT-Log</h3>';
echo '<div class="table-responsive"><pre id="logs_crtlog_pre">';
$log_path = "c:\apache24\logs\crtlog.log";
if(file_exists($log_path)) {
	print_r(file_get_contents($log_path));
}
echo '</pre></div>';

echo '<h3 id="logs_csrlog">';
echo '<button class="btn btn-sm btn-default" onclick="togglePre(\'#logs_csrlog_pre\');" title="Bereich Ein-/Ausblenden">TOGGLE</button>&nbsp;&nbsp;';
echo 'CSR-Log</h3>';
echo '<div class="table-responsive"><pre id="logs_csrlog_pre">';
$log_path = "c:\apache24\logs\csrlog.log";
if(file_exists($log_path)) {
	print_r(file_get_contents($log_path));
}
echo '</pre></div>';

echo '<h3 id="logs_openssl">';
echo '<button class="btn btn-sm btn-default" onclick="togglePre(\'#logs_openssl_pre\');" title="Bereich Ein-/Ausblenden">TOGGLE</button>&nbsp;&nbsp;';
echo 'OpenSSL-Log</h3>';
echo '<div class="table-responsive"><pre id="logs_openssl_pre">';
$log_path = "c:\apache24\logs\openssllog.log";
if(file_exists($log_path)) {
	print_r(file_get_contents($log_path));
}
echo '</pre></div>';

echo '<h3 id="logs_maillog">';
echo '<button class="btn btn-sm btn-default" onclick="togglePre(\'#logs_maillog_pre\');" title="Bereich Ein-/Ausblenden">TOGGLE</button>&nbsp;&nbsp;';
echo 'Mail-Log</h3>';
echo '<div class="table-responsive"><pre id="logs_maillog_pre">';
$log_path = "c:\apache24\logs\maillog.log";
if(file_exists($log_path)) {
	print_r(file_get_contents($log_path));
}
echo '</pre></div>';

echo '<h3 id="logs_dblog">';
echo '<button class="btn btn-sm btn-default" onclick="togglePre(\'#logs_dblog_pre\');" title="Bereich Ein-/Ausblenden">TOGGLE</button>&nbsp;&nbsp;';
echo 'DB-Log</h3>';
echo '<div class="table-responsive"><pre id="logs_dblog_pre">';
$log_path = "c:\apache24\logs\dblog.log";
if(file_exists($log_path)) {
	print_r(file_get_contents($log_path));
}
echo '</pre></div>';

echo '<h3 id="logs_accountlog">';
echo '<button class="btn btn-sm btn-default" onclick="togglePre(\'#logs_accountlog_pre\');" title="Bereich Ein-/Ausblenden">TOGGLE</button>&nbsp;&nbsp;';
echo 'User-Account-Log</h3>';
echo '<div class="table-responsive"><pre id="logs_accountlog_pre">';
$log_path = "c:\apache24\logs\accountlog.log";
if(file_exists($log_path)) {
	print_r(file_get_contents($log_path));
}
echo '</pre></div>';

echo '</div>';

include('./footer.php'); ?>

<script>
	$( document ).ready(function() {
		$( document ).find("pre").css({"height":"0", "padding":"0"});
	});

	function togglePre(thechosenone) {
		if ( $(thechosenone).css("height") != "2px" ) {
			$(thechosenone).css({"height":"0", "padding":"0"});
		} else {
			$(thechosenone).css({"height":"auto", "padding":"10px"});
		}
	}
</script>
