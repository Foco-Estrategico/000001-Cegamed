<?php
include ("AGI/phpagi-asmanager.php");
$Fono = $_POST['Tel'];
$Anexo = $_POST['Anexo'];
$Cedente = $_POST['Cedente'];
$User = $_POST['User'];
$asm = new AGI_AsteriskManager();
$asm->connect("127.0.0.1","lponce","lponce");
$Anio = date("Y");
$Mes = date("m");
$Dia = date("d");
$Hora = date("G");
$Minuto = date("H");
$Segundo = date("s");
$AnexoSip =  "SIP/".$Anexo;
$FonoSip = "SIP/".$Fono."@datavox";
$resultado = $asm->originate("$AnexoSip","$Fono","from-prueba","1","","","15000","$Fono","","12345","false","$Anexo");
sleep(2);
$canales = $asm->command("core show channels concise");
$info = explode(PHP_EOL,$canales["data"]);
$info[0] = str_replace("Privilege: Command","",$info[0]);
for ($i=0;$i<=(count($info)-1);$i++) {
$info2[$i] = explode("!",$info[$i]);
}
$canal = "SIP\/".$Anexo;
$claves = array();
$k = 0;
for ($i=0;$i<=(count($info)-1);$i++) {
    if(preg_match("/^$canal\b/i", $info2[$i][0])) {
        $claves[$k] = $i;
        $k++;
    }
}

$Canal = $info2[$claves[0]][0];
$nomArchivo = $Anio.$Mes.$Dia."-".$Hora.$Minuto.$Segundo."_".$Fono."_".$Cedente."_"."$User"."-all";
$formato = "wav";
$resultadoGrabacion = $asm->monitor($Canal,$nomArchivo,$formato);
$array = array('uno' => "$Canal", 'dos' => "$nomArchivo");
echo json_encode($array);
$asm->disconnect();
sleep(2);


?>

