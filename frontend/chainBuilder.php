<?php
require_once('./db.php');

function deliverFile($id, $type){
#TODO: Überprüfen
$db = new DBAccess();
$where = array("id","=","'".$id."'");
$db_result = $db->get_request_all_where($where);
$csr = reset($db_result);
$pathToCRT = $csr->path_cer;
$cn = $csr->common_name;

  if($type === 'Apache2'){
    $path = c:\apache24\ca\kunden\\date("Y-m-d-H-i-s")."_".$cn.".crt;
    file_put_contents($path, file_get_contents($pathToCRT).PHP_EOL);
    file_put_contents($pathToCRT, file_get_contents("c:\Apache24\ca\ica.crt").PHP_EOL , FILE_APPEND);
    file_put_contents($pathToCRT, file_get_contents("c:\Apache24\ca\ca.crt").PHP_EOL , FILE_APPEND);
    return $path;
  }
    elseif($type === 'ngnix'){
      $path = c:\apache24\ca\kunden\\date("Y-m-d-H-i-s")."_".$cn.".crt;
      file_put_contents($path, file_get_contents($pathToCRT).PHP_EOL);
      file_put_contents($pathToCRT, file_get_contents("c:\Apache24\ca\ica.crt").PHP_EOL , FILE_APPEND);
      file_put_contents($pathToCRT, file_get_contents("c:\Apache24\ca\ca.crt").PHP_EOL , FILE_APPEND);
      return $path;
    }
      elseif($type === 'generic'){
        #generic Code
        $path = "c:\apache24\ca\kunden\\date("Y-m-d-H-i-s")."_".$cn.".zip";
        $zip = new ZipArchive();
        $zip->open($path, ZipArchive::CREATE);
        
        $zip->addFile($pathToCRT, 'certificate.txt');
        $zip->addFile('c:\Apache24\ca\ica.crt', 'intermediate.txt');
        $zip->addFile('c:\Apache24\ca\ca.crt', 'rootCA.txt');
        
        $zip->close();
        
        return $path;
      }
        else{
          throw new Exception("Der gewünschte Zertifikatstyp konnte nicht bereitgestellt werden.");
        }
}

?>
