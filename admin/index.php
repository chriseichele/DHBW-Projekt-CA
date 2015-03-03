<?php 

$pagetitle = "Home";

include('./header.php');

if(!UserHelper::IsAdminLoggedIn()) {
	echo '<div class="jumbotron alert alert-error">
      		<div class="container">
        		<h1>Administratoren Seite</h1>
        		<p>Bitte authentifizieren sie sich zuerst!</p>
      		</div>
    	  </div>';
	include('./footer.php'); 
	exit();
}
//else

?>

    <!-- Main jumbotron for a primary marketing message or call to action -->
    <div class="jumbotron">
      <div class="container">
        <h1>Administratoren Seite</h1>
        <p>Hier haben sie als Administrator der Projekt CA die M&ouml;glichkeit, Zertifikatsantr&auml;ge zu bearbeiten.</p>
      </div>
    </div>

    <div class="container">
      <!-- Example row of columns -->
      <div class="row">
        <div class="col-md-4">
          <h2>Offene CSRs</h2>
          <p><a class="btn btn-default" href="openCSRlist.php" role="button" title="Offene Zertifikatsanfragen genehmigen">Zertifikate genehmigen »</a></p>
        </div>
        <div class="col-md-4">
          <h2>Bearbeitete CSRs</h2>
          <p><a class="btn btn-default" href="validatedCSRlist.php" role="button" title="Von mir bearbeitete Zertifikatsanfragen">Meine bearbeiteten Requests ansehen »</a></p>
       </div>
        <div class="col-md-4">
          <h2>Informationssystem</h2>
          <p><a class="btn btn-default" href="info.php" role="button" title="Datenbank-, Session- &amp; File-Informationssystem">Informationen anzeigen »</a></p>
        </div>
      </div>
    </div>
      
<?php 

include('./footer.php'); 

?>