<?php
    include_once("../../includes/functions/Functions.php");
    include ("../../discador/AGI/phpagi-asmanager.php");
    QueryPHP_IncludeClasses("db");
    class CallDaemon{
         public function Start(){
            echo "BRIGDE FILETE";
         }   
    }

    $asm = new AGI_AsteriskManager();
    $asm->connect("127.0.0.1","lponce","lponce");
    $Channel1 = '';
    $Channel2 = '';
    $asm->add_event_handler("bridge","FunctionBridge");
    $asm->add_event_handler("newstate","FunctionNewstate");
    $asm->add_event_handler("Hangup","FunctionHangup");

    while(true){
        $asm->wait_response(true);
    }

    function FunctionHangup($ecode,$data,$server,$port) {
        $db = new Db();
        $Channel = $data['Channel'];
        $Cause = $data['Cause'];
        $CauseTxt = $data['Cause-txt'];
        $Account = $data['AccountCode'];
        $FechaHora = date("Y-m-d G:H:s");
        $FechaGestion = date("Y-m-d");
        $HoraGestion = date("G:H:s");
        $Array = explode("&",$Account);
        print_r($Array);
        if($Array[3] != 0){
            $Fono = $Array[1];
            $Rut = $Array[3];
            $AnexoHangup = '';
            $QueryAnexo = "SELECT Anexo FROM Asterisk_Bridge WHERE Fono = $Fono AND Rut = $Rut LIMIT 1";
            $Records = $db -> select($QueryAnexo);
            foreach($Records as $Record){
                $AnexoHangup = $Record['Anexo'];
            } 
            if($AnexoHangup!=''){
                $SqlInsertRecord = "INSERT INTO Asterisk_Hangup(Channel,Cause,CauseTxt,Fono,Rut,FechaHora,Anexo) VALUES ('$Channel','$Cause','$CauseTxt','$Fono','$Rut','$FechaHora','$AnexoHangup')";
                $InsertRecord = $db -> query($SqlInsertRecord);

                $SqlInsertRecordHistory = "INSERT INTO Asterisk_Hangup_History(Channel,Cause,CauseTxt,Fono,Rut,FechaHora,Anexo) VALUES ('$Channel','$Cause','$CauseTxt','$Fono','$Rut','$FechaHora','$AnexoHangup')";
                $InsertRecordHistory = $db -> query($SqlInsertRecordHistory);
            }
            
            $Query = "SELECT * FROM Asterisk_Bridge_History WHERE Channel1 = '$Channel' OR Channel2 = '$Channel'";
            $Validar = count($db -> select($Query));
            $QueryNewstate = "SELECT * FROM Asterisk_Newstate WHERE Canal = '$Channel'";
            $ValidarNewstate = count($db -> select($QueryNewstate));
            if($Validar == 0 && $ValidarNewstate > 0){
                $ResponseMachine = 'Agente No Disponible';
                $InsertGestion = "INSERT INTO gestion_ult_trimestre_predictivo (rut_cliente,fono_discado,status_name,fechahora,fecha_gestion,hora_gestion,Id_TipoGestion,Channel,origen) VALUES ('$Rut','$Fono','$ResponseMachine','$FechaHora','$FechaGestion','$HoraGestion','3','$Channel','1')";
                $InsertRecord = $db -> query($InsertGestion);
            }
            else if($Validar == 0 && $ValidarNewstate == 0){
                $ResponseMachine = $CauseTxt;
                $InsertGestion= "INSERT INTO gestion_ult_trimestre_predictivo (rut_cliente,fono_discado,status_name,fechahora,fecha_gestion,hora_gestion,Id_TipoGestion,Channel,origen) VALUES ('$Rut','$Fono','$ResponseMachine','$FechaHora','$FechaGestion','$HoraGestion','3','$Channel','1')";
                $InsertRecord = $db -> query($InsertGestion);
            }

            
            $DeleteIncall = "DELETE FROM Asterisk_InCall WHERE Fono = $Fono AND Rut = $Rut";
            $DeleteInCallRecord = $db -> query($DeleteIncall);
            $DeleteBridge = "DELETE FROM Asterisk_Bridge WHERE Fono = $Fono AND Rut = $Rut";
            $DeleteBridgeRecord = $db -> query($DeleteBridge);
            $DeleteHangup = "DELETE FROM Asterisk_Hangup WHERE Fono = $Fono AND Rut = $Rut";
            $DeleteHangupRecord = $db -> query($DeleteHangup);
            sleep(1);
  
        }
        
    }

    function FunctionNewstate($ecode,$data,$server,$port) {
        echo "received event '$ecode' from $server:$port ";
        $db = new Db();
        echo "NEWSTATE";
        print_r($data);
        $Event = $data['Event'];
        $Privilege = $data['Privilege'];
        $Channel = $data['Channel'];
        $Channel1 = explode("/", $Channel);
        $Channel2 = explode("-",$Channel1[1]);
        $Anexo = $Channel2[0];
        $ChannelStateDesc = $data['ChannelStateDesc'];

        $asm = new AGI_AsteriskManager();
        $asm->connect("127.0.0.1","lponce","lponce");
        $resultado = $asm->command("core show channels concise");
        //print_r($resultado);
        $info = explode(PHP_EOL,$resultado["data"]);
        $info[0] = str_replace("Privilege: Command","",$info[0]);
        for ($i=0;$i<=(count($info)-1);$i++) {
            $info2[$i] = explode("!",$info[$i]);
        }

        $canal = "SIP\/".$Anexo;
        $claves = array();
        $k = 0;

        for ($i=0;$i<=(count($info)-1);$i++) {
            if(preg_match("/^$canal\b/i", $info2[$i][0])) {
                if(preg_match("/^$canal\b/i", $info2[$i][0])) {
                    $claves[$k] = $i;
                    $k++;
                }
            }
        }    
        $Canal = $info2[$claves[0]][12];
        $AgenteNoDisponible = $info2[$claves[0]][6];
        $Queue = $info2[$claves[0]][5];
        $Result= $asm->Command("core show channels concise");
        $Canal = $Result['data'];
        $Canal2 = explode("\n",$Canal);
        $Canal3 = $Canal2[1];
        $Canal4 = explode("\n",$Canal3);
        $Canal4[0];
        $Canal5 = $Canal4[0];
        $Count= count($Canal4);
        echo "Array Cuantos : ".$Count;
        $j = 0;
        while($j<$Count){
            $ArrayTest = explode("!",$Canal4[$j]);
            if  (in_array("Queue", $ArrayTest)) {
                $Canal = $ArrayTest[0];
                $CanalFull = $Canal4[$j];
                $InsertNewstate = "INSERT INTO Asterisk_Newstate(Canal) VALUES ('$Canal')";
                $InsertRecord = $db -> query($InsertNewstate);
            }
            $j++;
        }
    }

    function FunctionBridge($ecode,$data,$server,$port) {
        echo "FUNCION BRIDGE";
        $db = new Db();
        echo "received event '$ecode' from $server:$port ";
        print_r($data);
        $Channel1 = $data['Channel1'];
        $Channel2 = $data['Channel2'];
        $Bridgestate = $data['Bridgestate'];
        $Cause = $data['Cause'];
        $Phone = '';
        $Channel = explode("/", $Channel2);
        $Channel = explode("-",$Channel[1]);
        $Anexo = $Channel[0];
        $Channel3 = explode("/", $Channel1);
        $Channel3 = explode("-",$Channel[1]);
        $Anexo2 = $Channel3[0];

        $asm = new AGI_AsteriskManager();
        $asm->connect("127.0.0.1","lponce","lponce");
        $resultado = $asm->command("core show channels concise");
        $info = explode(PHP_EOL,$resultado["data"]);
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
        print_r($info2[$claves[0]]);
        $Datas = $info2[$claves[0]][8];
        $DataExplode = explode("&",$Datas);
        /*if(count($DataExplode)==5){
            $Rut = $DataExplode[0];
            $Fono = $DataExplode[1];
            $Cedente = $DataExplode[2];
            $Usuario = $DataExplode[3];
            $Anio = date("Y");
            $Mes = date("m");
            $Dia = date("d");
            $Hora = date("G");
            $Minuto = date("H");
            $Segundo = date("s");
            $Origen  = "Manual";

            if($Bridgestate == 'Link'){
                $Canal = $Channel1;
                $formato = "wav";
                $nomArchivo = $Anio.$Mes.$Dia."-".$Hora.$Minuto.$Segundo."_".$Fono."_".$Cedente."_"."$Usuario"."-all";
                $resultadoGrabacion = $asm->monitor($Canal,$nomArchivo,$formato);
                //mysql_query("INSERT INTO Asterisk_Record_Temp (NombreGrabacion) VALUES ('$nomArchivo')");
            // mysql_query("INSERT INTO Asterisk_Bridge( Channel1,Channel2,Bridgestate,Anexo,Rut,Fono,Cedente,Usuario,NombreGrabacion,Origen) VALUES ('$Channel1','$Channel2','$Bridgestate','$Anexo2','$Rut','$Fono','$Cedente','$Usuario','$nomArchivo','$Origen')");
            }
        }*/
       if(count($DataExplode)==5){
            $Rut = $DataExplode[3];
            $Fono = $DataExplode[1];
            $Cedente = $DataExplode[4];
            $Usuario = '';
            $QueryUsuario = "SELECT usuario FROM Usuarios WHERE anexo_foco = $Anexo LIMIT 1";
            $Records = $db -> select($QueryUsuario);
            foreach($Records as $Record){
                $Usuario = $Record['usuario'];
            } 

            $Anio = date("Y");
            $Mes = date("m");
            $Dia = date("d");
            $Hora = date("G");
            $Minuto = date("H");
            $Segundo = date("s");
            $Origen = "Predictivo";
            if($Bridgestate == 'Link' && $Anexo != 0){
                echo "INSERTANDO!!!!!!!!!";
                $Canal = $Channel1;
                $formato = "wav";
                $nomArchivo = $Anio.$Mes.$Dia."-".$Hora.$Minuto.$Segundo."_".$Fono."_".$Cedente."_"."$Usuario"."-all";
                $resultadoGrabacion = $asm->monitor($Canal,$nomArchivo,$formato);
                $NombreGrabacion = "INSERT INTO Asterisk_Record_Temp (NombreGrabacion) VALUES ('$nomArchivo')";
                $InsertGrabacion = $db -> query($NombreGrabacion);
                if($Anexo!=''){
                    $InsertBridge = "INSERT INTO Asterisk_Bridge( Channel1,Channel2,Bridgestate,Anexo,Rut,Fono,Cedente,Usuario,NombreGrabacion,Origen) VALUES ('$Channel1','$Channel2','$Bridgestate','$Anexo','$Rut','$Fono','$Cedente','$Usuario','$nomArchivo','$Origen')";
                    $InsertBridgeRecord = $db -> query($InsertBridge);
                    $InsertBridgeHistory = "INSERT INTO Asterisk_Bridge_History( Channel1,Channel2,Bridgestate,Anexo,Rut,Fono,Cedente,Usuario,NombreGrabacion,Origen) VALUES ('$Channel1','$Channel2','$Bridgestate','$Anexo','$Rut','$Fono','$Cedente','$Usuario','$nomArchivo','$Origen')";
                    $InsertBridgeHistoryRecord = $db -> query($InsertBridgeHistory);
                }
                
            }
        }
    }    
?>
