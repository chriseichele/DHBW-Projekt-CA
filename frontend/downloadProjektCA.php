<?php

$pagetitle = 'Projekt CA Zertifikate';

include('./header.php');

echo' <div class="jumbotron">
	<div class="container">
		<h1>'. $pagetitle. '</h1>
		<p>Hier k&ouml;nnen sie unsere Zertifikate herunterladen.</p>
	</div>
</div>
<div class="container">
	<a href="./pub/ca.crt" class="btn btn-success btn-lg btn-block" role="button">Root Zertifikat</a>
	<a href="./pub/ica.crt" class="btn btn-success btn-lg btn-block" role="button">Intermediate Zertifikat</a>
</div>';

include('./footer.php'); 


?>