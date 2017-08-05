<?php
//ASTERISK VICIDIAL
$ConViciDial=mysql_connect('192.168.1.80','root','m9a7r5s3');
mysql_select_db('asterisk',$ConViciDial);
//CONEXION A FOCO
$ConFoco=mysql_connect('192.168.1.8','root','s9q7l5.,777');
mysql_select_db('foco',$ConFoco);
//$Fecha = date('Y-m-d');


$QueryStatus = mysql_query("SELECT status_name,Peso,Id_TipoContacto FROM vicidial_campaign_statuses ",$ConViciDial);
while($row = mysql_fetch_array($QueryStatus)){
    $StatusName = $row[0];
    $Peso = $row[1];
    $TipoContacto = $row[2];
    mysql_query("UPDATE gestion_ult_trimestre SET Peso = $Peso,Id_TipoGestion = $TipoContacto WHERE status_name = '$StatusName'",$ConFoco);
        
}

?>

