<?php
include ("AGI/phpagi-asmanager.php");
$Anexo = $_POST['Anexo'];

$asm = new AGI_AsteriskManager();
$asm->connect("127.0.0.1","lponce","lponce");
$resultado = $asm->command("core show channels concise");
//print_r($resultado["data"]);
$info = explode(PHP_EOL,$resultado["data"]);
$info[0] = str_replace("Privilege: Command","",$info[0]);
//print_r($info);
for ($i=0;$i<=(count($info)-1);$i++) {
$info2[$i] = explode("!",$info[$i]);
}

//print_r($info2);

$canal = "SIP\/".$Anexo;
$claves = array();
$k = 0;

for ($i=0;$i<=(count($info)-1);$i++) {
    if(preg_match("/^$canal\b/i", $info2[$i][0])) {
        $claves[$k] = $i;
        $k++;
    }
}

  //print_r($claves);

  print_r($info2[$claves[0]]);
  $Canal = $info2[$claves[0]][0];
  $asm->hangup($Canal);

  $asm->disconnect();
?>



