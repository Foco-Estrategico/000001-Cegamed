<?php
//ASTERISK VICIDIAL
$ConViciDial=mysql_connect('192.168.1.80','root','m9a7r5s3');
mysql_select_db('asterisk',$ConViciDial);
//CONEXION A FOCO
$ConFoco=mysql_connect('192.168.1.8','root','s9q7l5.,777');
mysql_select_db('foco',$ConFoco);
//$Fecha = date('Y-m-d');

$Fecha = "2017-05-05";
$Cur = $Fecha." 00:00:00";

$QueryListas = mysql_query("SELECT TABLE_NAME FROM INFORMATION_SCHEMA.tables WHERE TABLE_NAME LIKE 'custom%'",$ConViciDial);
while($row = mysql_fetch_array($QueryListas)){
    $TablaCustom = $row[0];
    $QueryCustom = '';
    $QueryTipo1 = mysql_query("SELECT lead_id,RUT,OBSERVACION,FEC_COMP FROM $TablaCustom ",$ConViciDial);
    $QueryTipo2 = mysql_query("SELECT lead_id,RUT,OBSERVACION,FEC_COMPROMISO FROM $TablaCustom ",$ConViciDial);
    $QueryTipo3 = mysql_query("SELECT lead_id,RUT,OBSERVACION,FECHA_PAGO FROM $TablaCustom ",$ConViciDial);

    if($QueryTipo1){
    $QueryCustom = mysql_query("SELECT lead_id,RUT,OBSERVACION,FEC_COMP FROM $TablaCustom ",$ConViciDial);

    }elseif($QueryTipo2){
    $QueryCustom = mysql_query("SELECT lead_id,RUT,OBSERVACION,FEC_COMPROMISO FROM $TablaCustom ",$ConViciDial);

    }elseif($QueryTipo3){
    $QueryCustom = mysql_query("SELECT lead_id,RUT,OBSERVACION,FECHA_PAGO FROM $TablaCustom ",$ConViciDial);

    }else{
    $QueryCustom = mysql_query("SELECT lead_id,RUT,OBSERVACION FROM $TablaCustom ",$ConViciDial);

    }
    while($row = mysql_fetch_array($QueryCustom)){
        $LeadId = $row[0];
        $Rut = $row[1];
        $ObservacionP= $row[2];
        $FechaCompromisoP= $row[3];
        //$MontoCompromiso = $row[4];
       $QueryGrabacion = mysql_query("SELECT filename,location FROM recording_log WHERE lead_id = $LeadId",$ConViciDial);
        while($row = mysql_fetch_array($QueryGrabacion)){
            $GrabacionP = $row[0];
            $LocationP = $row[1];

        }

        $QueryLead = mysql_query("SELECT call_date,status,phone_number,user,length_in_sec,campaign_id,list_id FROM vicidial_log WHERE lead_id = $LeadId AND call_date > '$Cur'",$ConViciDial);
        while($row = mysql_fetch_array($QueryLead)){
            $CallDate = $row[0];
            $Status = $row[1];
            $PhoneNumber = $row[2];
            $Agente = $row[3];
            $DuracionP = $row[4];
            $Campana = $row[5];
            $Lista = $row[6];
            $StatusName = "";
            $IdTipoGestion = "";
            
            $QueryStatus = mysql_query("SELECT status_name,human_answered,Id_TipoContacto,Ponderacion FROM vicidial_campaign_statuses WHERE status = '$Status'",$ConViciDial);
            if(mysql_num_rows($QueryStatus)>0){
                while($row = mysql_fetch_array($QueryStatus)){
                    $StatusName = $row[0];
                    $Human = $row[1];
                    $IdTipoGestion = $row[2];
                    $Ponderacion = $row[3];
                    if($IdTipoGestion==5){
                        $FechaCompromiso = $FechaCompromisoP;
                    }
                    else{
                        $FechaCompromiso = '';
                    }
                    if($Human=='Y'){
                        $Observacion = $ObservacionP;
                        $Duracion = $DuracionP;
                        $Grabacion = $GrabacionP;
                        $Location = $LocationP;
                        shell_exec("wget -nc -P /var/www/html/produccion/Records/Tmp/ $Location");
                    }
                     else{
                        $Observacion='';
                        $Duracion = '';
                        $Grabacion = '';
                        $Location = '';

                    }
                }
            }
            else{
                $QueryStatus = mysql_query("SELECT status_name,human_answered,Id_TipoGestion,Ponderacion FROM vicidial_statuses_homologacion WHERE status = '$Status'",$ConViciDial);
                while($row = mysql_fetch_array($QueryStatus)){
                    $StatusName = $row[0];
                    $Human = $row[1];
                    $IdTipoGestion = $row[2];
                    $Ponderacion = $row[3];
                    if($IdTipoGestion==5){
                        $FechaCompromiso = $FechaCompromisoP;
                    }
                    else{
                        $FechaCompromiso = '';
                    }
                    if($Human=='Y'){
                        $Observacion = $ObservacionP;
                        $Duracion = $DuracionP;
                        $Grabacion = $GrabacionP;
                        $Location = $LocationP;
                        shell_exec("wget -nc -P /var/www/html/produccion/Records/Tmp/ $Location");


                    }
                    else{
                        $Observacion='';
                        $Duracion = '';
                        $Grabacion = '';
                        $Location = '';
                    }
                }
            }
            $FechaGestion = date('Y-m-d',strtotime($CallDate));
            $HoraGestion = date('H:i:s',strtotime($CallDate));
            if($PhoneNumber=='' || $Status=='INCALL'){
                echo "INCALL , NO SE GUARDA GESTION";
            }
            else{
                echo $Insert = "INSERT INTO gestion_ult_trimestre(rut_cliente,fechahora,observacion,fecha_gestion,hora_gestion,lista,cedente,fono_discado,duracion,nombre_ejecutivo,nombre_grabacion,origen,Id_TipoGestion,status,status_name,fec_compromiso,Ponderacion,url_grabacion) VALUES ('$Rut','$CallDate','$Observacion','$FechaGestion','$HoraGestion','$Lista','$Campana','$PhoneNumber','$Duracion','$Agente','$Grabacion','777','$IdTipoGestion','$Status','$StatusName','$FechaCompromiso','$Ponderacion','$Location')";
                mysql_query($Insert,$ConFoco);
            }
        }
    }
}
?>
