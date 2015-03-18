<?php 

$pagetitle = "Home";

include('./header.php');

?>

    <!-- Main jumbotron for a primary marketing message or call to action -->
    <div class="jumbotron">
      <div class="container">
        <h1>Sichere Daten&uuml;bertragung</h1>
        <p>Wir haben es uns auf die Fahne geschrieben, die Daten&uuml;bertragung im Internet sicherer zu machen.
        <br/>Fragen sie noch heute Ihr eigenes und <b>kostenloses</b> Zertifikat mithilfe eines CSRs bei uns an!</p>
        <p><a class="btn btn-primary btn-lg" href="csrtutorial.php" role="button">Wie erstelle ich ein CSR?</a></p>
      </div>
    </div>

    <div class="container">
      <!-- Example row of columns -->
      <div class="row">
        <div class="col-md-4">
          <h2>Zertifikate</h2>
          <p>Bestellen Sie sich ihre eigenen Web-Zertifikate, um die Verbindung zu ihren Servern zu verschl&uuml;sseln.</p>
          <p><a class="btn btn-default" href="zert.php" role="button">Zertifikat bestellen »</a></p>
        </div>
        <div class="col-md-4">
          <h2>Wildcard Zertifikate</h2>
          <p>Fordern Sie Wildcard-Zertifikate an, um eine unendliche Zahl an Subdomains in einem Zertifikat abzudecken.</p>
          <p><a class="btn btn-default" href="zert.php?wildcard" role="button">Wildcard Zertifikat bestellen »</a></p>
       </div>
        <div class="col-md-4">
          <h2>Unsere Zertifikate</h2>
          <p>Laden Sie unsere Zertifikate herunter, um diese bei Bedarf in ihren Server einzubinden und die Zertifikatskette zu vervollst&auml;ndigen</p>
          <p><a class="btn btn-default" href="downloadProjektCA.php" role="button">Zertifikate herunterladen »</a></p>
        </div>
      </div>
    </div>
      
<?php include('./footer.php'); ?>