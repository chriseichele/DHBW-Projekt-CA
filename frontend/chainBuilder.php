<?php
require_once('./db.php');
require_once('./LogHelper.php');

function deliverFile($id, $type){
#TODO: Überprüfen
$db = new DBAccess();
$log = new OpensslLogger();
$where = array("id","=","'".$id."'");
$db_result = $db->get_request_all_where($where);
$csr = reset($db_result);
$pathToCRT = $csr->path_cer;
$cn = $csr->common_name;

  if($type === 'Apache2'){
    $path = "c:\apache24\ca\kunden\\".date("Y-m-d-H-i-s")."_".$cn.".crt";
    file_put_contents($path, file_get_contents($pathToCRT).PHP_EOL);
    file_put_contents($pathToCRT, file_get_contents("c:\Apache24\ca\ica.crt").PHP_EOL , FILE_APPEND);
    file_put_contents($pathToCRT, file_get_contents("c:\Apache24\ca\ca.crt").PHP_EOL , FILE_APPEND);
    return $path;
  }
    elseif($type === 'nginx'){
      $path = "c:\apache24\ca\kunden\\".date("Y-m-d-H-i-s")."_".$cn.".chained.crt";
      file_put_contents($path, file_get_contents($pathToCRT).PHP_EOL);
      file_put_contents($pathToCRT, file_get_contents("c:\Apache24\ca\ica.crt") , FILE_APPEND);
      file_put_contents($pathToCRT, file_get_contents("c:\Apache24\ca\ca.crt").PHP_EOL , FILE_APPEND);
      return $path;
    }
      elseif($type === 'generic'){
        #generic Code
        $path = "c:\apache24\ca\kunden\\".date("Y-m-d-H-i-s")."_".$cn.".zip";
        $zip = new ZipArchive();
        $zip->open($path, ZipArchive::CREATE);
        
        $zip->addFile($pathToCRT, 'certificate.txt');
        $zip->addFile('c:\Apache24\ca\ica.crt', 'intermediate.txt');
        $zip->addFile('c:\Apache24\ca\ca.crt', 'rootCA.txt');
        
        $zip->close();
        
        return $path;
      }
        elseif($type === 'PEM-Format'){
          $path = "c:\apache24\ca\kunden\\".date("Y-m-d-H-i-s")."_".$cn.".pem";
          $opensslcmd = "c:\apache24\bin\openssl.exe x509 -inform der ".$pathToCRT." -out ".$path;
          shell_exec($opensslcmd);
          $log->addNotice($opensslcmd);
          return $path;
        }
          else{
            throw new Exception("Der gewünschte Zertifikatstyp konnte nicht bereitgestellt werden.");
          }
}

?>
