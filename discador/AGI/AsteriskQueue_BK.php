<?php
include ("phpagi-asmanager.php");

$host_name = '192.168.1.10';
$pass_word = 's9q7l5.,777';
$user_name = 'root';
$database_name = 'foco';
$conn = mysql_connect($host_name, $user_name, $pass_word) or die ('Error connecting to mysql');
mysql_select_db($database_name);


$IdCola = $argv[1];
$NombreQueue = '';
$X = '';
$Cedente = '';
$ColaAsterisk = '';
$ColaOriginal = '';


$QueryQueue = mysql_query("SELECT q.Queue,d.Cola,d.numero_canales,d.Id_Cedente FROM Asterisk_All_Queues q , Asterisk_Discador_Cola d WHERE q.id_discador = d.id AND d.id=$IdCola");
while($row = mysql_fetch_array($QueryQueue)){

    $NombreQueue = $row[0];
    $ColaAsterisk  = "DR_".$NombreQueue."_".$row[1];
    $X = $row[2];
    $Cedente = $row[3];
    $ColaOriginal = $row[1];

}

function Stop(){
    global $IdCola;
    echo "Esto puede ser";
    echo $IdCola;
    $QueryStop = mysql_num_rows(mysql_query("SELECT * FROM Asterisk_Discador_Cola WHERE id = $IdCola AND Status = 1 "));
    if($QueryStop==0){
        $Stop = 0;
        echo "Detenidnedo aca";
        echo $FechaHora = date("Y-m-d G:i:s");
        echo $Query = "UPDATE Asterisk_Discador_Cola SET Estado=$Stop ,FeFin = '$FechaHora' WHERE id = $IdCola";
        mysql_query($Query);
        return $Stop;
    }
    else{
        $Stop = 1;
        echo "Comenzando";
        return $Stop;
    }
}
function Multiplicador(){
    global $X;
    global $NombreQueue;
    $asm = new AGI_AsteriskManager();
    $asm->connect("127.0.0.1","lponce","lponce");
    $AgentesDisponibles = array();
    $QueryAgentes = mysql_query("SELECT Agente FROM Asterisk_Agentes WHERE Queue = $NombreQueue");
    while($row = mysql_fetch_array($QueryAgentes)){
        
        $Agentes = $row[0];
        array_push($AgentesDisponibles,"$Agentes");

    }
    $Unavailable = array();
    $Available = array();
    $ToReturn = array();
    $Contar = count($AgentesDisponibles);
    $i = 0;
    $contar2 = count($AgentesDisponibles);
    while($i<$contar2){

        $resultado = $asm->Command("queue show $NombreQueue");
        $Anexo = $AgentesDisponibles[$i];
        $test = implode("\n",$resultado);
        $array = explode("\n",$test);
        $contar = count($array);
        $j = 0;
        while($j<$contar){
            $array3 = explode(" ",$array[$j]);
            if  (in_array("$Anexo", $array3) && in_array("(Unavailable)", $array3)) {
                echo $Anexo." No Disponible";
            }
            else if (in_array("$Anexo", $array3) && in_array("(paused)", $array3)){
                array_push($Unavailable, "$Anexo");
                echo $Anexo." En Pausa";

            }
            else if (in_array("$Anexo", $array3)){
                array_push($Available, "$Anexo");
                echo "Available";
            }

            else{ 

            }
            $j++;
        }
        $i++;
   }
    echo "Pausa"; echo $Pausa  =  count($Unavailable);
    echo "Dipo : "; echo $Disponible = count($Available);
    echo "Multi;"; echo $Multiplicador = $Disponible*$X;
    $ToReturn = array('Mul' => $Multiplicador, 'Pausa' => $Pausa);
    return $ToReturn;
    $asm->disconnect();

}
$ValReturn = Multiplicador();
echo "Maravilla = "; echo $Multi = $ValReturn['Mul'];
echo "Pausa Ma = "; echo $Pause = $ValReturn['Pausa'];
while($Multi == 0 && $Pause > 0){
    echo "No hay Agentes Disponibles , Pero hay uno Pausado";
    sleep(1);
    $ValReturn = Multiplicador();
    echo "Maravilla = "; echo $Multi = $ValReturn['Mul'];
    echo "Pausa Ma = "; echo $Pause = $ValReturn['Pausa'];

}

$Stop = Stop();
while($Stop == 1){
    $ValReturn = Multiplicador();
    echo "Maravilla = "; echo $Multi = $ValReturn['Mul'];
    echo "Pausa Ma = "; echo $Pause = $ValReturn['Pausa'];
    while($Multi == 0 && $Pause > 0){
        echo "No hay Agentes Disponibles , Pero hay uno Pausado";
        sleep(1);
        $ValReturn = Multiplicador();
        echo "Maravilla = "; echo $Multi = $ValReturn['Mul'];
        echo "Pausa Ma = "; echo $Pause = $ValReturn['Pausa'];
  }

$Comenzar = mysql_query("SELECT id,Fono,Rut FROM $ColaAsterisk WHERE llamado = 0 LIMIT  $Multi");
$Validar = mysql_num_rows($Comenzar);
if($Validar > 0){
    while($row = mysql_fetch_array($Comenzar)){
        $Fono = $row[1];
        $Id = $row[0];
        $Rut = $row[2];
        $ContarOnline = mysql_num_rows(mysql_query("SELECT * FROM Asterisk_InCall"));
        if($ContarOnline>=$Multi){
            echo "No insertar";
            sleep(2);
            $ValReturn = Multiplicador();
            echo "Maravilla = "; echo $Multi = $ValReturn['Mul'];
            echo "Pausa Ma = "; echo $Pause = $ValReturn['Pausa'];
            $Stop = Stop();
        }
        else{

            mysql_query("INSERT INTO Asterisk_InCall(Fono,Rut) VALUES ('$Fono','$Rut')");
            mysql_query("UPDATE $ColaAsterisk SET llamado = 1 WHERE Fono = $Fono");
            echo $FonoSip = "SIP/".$Fono."@datavox";
            $asm = new AGI_AsteriskManager();
            $asm->connect("127.0.0.1","lponce","lponce");
            echo $VarAgi = $Id."&".$Fono."&".$NombreQueue."&".$Rut."&".$Cedente;
            $resultado = $asm->originate("$FonoSip","$NombreQueue","from-prueba","1","","","18000","Foco-Estrategico","$VarAgi","$VarAgi","false","1001");
            print_r($resultado);
            $Stop = Stop();
            $asm->disconnect();
        }
    }

}
else{
    $Stop = 0;
    echo $FechaHora = date("Y-m-d G:i:s");
    echo $ColaOriginal;
    echo $Query = "UPDATE Asterisk_Discador_Cola SET Estado=$Stop ,FeFin = '$FechaHora' WHERE id = $IdCola";
    mysql_query($Query);
    echo "Deteniendo";
    }
}

?>
