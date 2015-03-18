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
          <p><a class="btn btn-default" href="zert.php" role="button">Zertifikat bestellen »</a></p>
        </div>
        <div class="col-md-4">
          <h2>Wildcard Zertifikate</h2>
          <p><a class="btn btn-default" href="zert.php" role="button">Wildcard Zertifikat bestellen »</a></p>
       </div>
        <div class="col-md-4">
          <h2>Und noch mehr in K&uuml;rze</h2>
          <p>Unser Team arbeitet kontinuierlich um Ihnen weitere Funktionen anbieten zu k&ouml;nnen. Diese finden sich dann in K&uuml;rze an dieser Stelle.</p>
        </div>
      </div>
    </div>
      
<?php include('./footer.php'); ?>