<?php
include ("AGI/phpagi-asmanager.php");
$Fono = 994096738;
$Anexo = 1002;
$Cedente = 10;
$User = "lponce";
$asm = new AGI_AsteriskManager();
$asm->connect("127.0.0.1","lponce","lponce");
$Anio = date("Y");
$Mes = date("m");
$Dia = date("d");
$Hora = date("G");
$Minuto = date("H");
$Segundo = date("s");



$FonoSip = "SIP/".$Fono."@datavox";
$resultado = $asm->originate("$FonoSip","$Anexo","from-prueba","1","","","90000","18295218507","","12345","true","$Anexo");
print_r($resultado);

$asm->add_event_handler("OriginateResponse","mostrarDatos");
$asm->add_event_handler("hanguprequest","nueva");
$asm->add_event_handler("softhanguprequest","nueva");
$asm->add_event_handler("Hangup","mifuncion");
$canal  ='';
$nomArchivo = "";
$formato = "";

while(true){
    $asm->wait_response(true);
}

function nueva($ecode,$data,$server,$port){
    $asm = new AGI_AsteriskManager();
    $asm->connect("127.0.0.1","lponce","lponce");
    $canal =  $data['Channel'];
    $resultado2 = $asm->StopMonitor($canal);
    print_r($resultado2);
}
function mostrarDatos($ecode,$data,$server,$port) {
    echo "received event '$ecode' from $server:$port\n";
    print_r($data);
    global $Anio;
    global $Mes;
    global $Dia;
    global $Hora;
    global $Minuto;
    global $Segundo;
    global $Fono;
    global $Cedente;
    global $User;

    $nomArchivo = $Anio.$Mes.$Dia."-".$Hora.$Minuto.$Segundo."_".$Fono."_".$Cedente."_".$User."-all";
    $formato = "wav";
    $asm = new AGI_AsteriskManager();

    $asm->connect("127.0.0.1","lponce","lponce");
    echo "Canal Primera Funcion"; echo $canal =  $data['Channel'];
    $resultadoGrabacion = $asm->monitor($canal,$nomArchivo,$formato);
    echo "Comienzo Grabacion : "; print_r($resultadoGrabacion);
}

function mifuncion($ecode,$data,$server,$port) {
    $asm = new AGI_AsteriskManager();
    //$asm->Events("on");
    $asm->connect("127.0.0.1","lponce","lponce");
    echo "Evento Recibido : '$ecode' from $server:$port\n";
    echo $canal = $data['Channel'];
    echo "Insertando";

}

$asm->disconnect();
sleep(10);



?>

