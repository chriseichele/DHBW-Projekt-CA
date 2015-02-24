<?php 

$pagetitle = "Home";

include('./header.inc');

?>

    <!-- Main jumbotron for a primary marketing message or call to action -->
    <div class="jumbotron">
      <div class="container">
        <h1>Sichere Daten&uuml;bertragung</h1>
        <p>Verschl&uuml;sseln Sie die Daten&uuml;bertragung ihrer Webseiten, und vieles mehr! Fragen sie noch heute Ihr eigenes Zertifikat mithilfe eines CSRs bei uns an!</p>
        <p><a class="btn btn-primary btn-lg" href="csrtutorial.php" role="button">Mehr Informationen »</a></p>
      </div>
    </div>

    <div class="container">
      <!-- Example row of columns -->
      <div class="row">
        <div class="col-md-4">
          <h2>Zertifikate</h2>
          <p>Ein Public-Key-Zertifikat ist ein digitales Zertifikat, das den Eigent&uuml;mer sowie weitere Eigenschaften eines &ouml;ffentlichen Schl&uuml;ssels best&auml;tigt. Durch ein Public-Key-Zertifikat k&ouml;nnen Nutzer eines asymmetrischen Kryptosystems den &ouml;ffentlichen Schl&uuml;ssel einer Identit&auml;t zuordnen. Damit erm&ouml;glichen sie den Schutz der Vertraulichkeit, Authentizit&auml;t und Integrit&auml;t von Daten.</p>
          <p><a class="btn btn-default" href="normaleszert.php" role="button">Zertifikat bestellen »</a></p>
        </div>
        <div class="col-md-4">
          <h2>Intermediate CAs</h2>
          <p>Einige Zertifikate werden nicht direkt unter einem Root-Zertifikat (manchmal auch Stamm- oder Wurzel-Zertifikat genannt) ausgestellt sondern unter einem sogenannten Zwischenzertifikat, welches anschlie&szlig;end zwischen dem (End-)Zertifikat und dem Root-Zertifikat steht. Mit Zwischenzertifikate k&ouml;nnen, im Gegesatz zu Standard End-Zertifikaten, weitere Zertifikate ausgestellt werden.</p>
          <p><a class="btn btn-default" href="intermediate.php" role="button">Intermediate Zertifikat bestellen »</a></p>
       </div>
        <div class="col-md-4">
          <h2>Und noch mehr in K&uuml;rze</h2>
          <p>Unser Team arbeitet kontinuierlich um Ihnen weitere Funktionen anbieten zu k&ouml;nnen. Diese finden sich dann in K&uuml;rze an dieser Stelle.</p>
          <p><!--<a class="btn btn-default" href="#" role="button">Details »</a>--></p>
        </div>
      </div>
    </div>
      
<?php include('./footer.inc'); ?>