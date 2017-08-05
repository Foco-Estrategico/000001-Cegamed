<?php
//ASTERISK VICIDIAL
//$ConViciDial=mysql_connect('192.168.1.80','root','m9a7r5s3');
//mysql_select_db('asterisk',$ConViciDial);
//CONEXION A FOCO
$ConFoco=mysql_connect('192.168.1.8','root','s9q7l5.,777');
mysql_select_db('foco',$ConFoco);
//$Fecha = date('Y-m-d');


$QueryFoco = mysql_query("SELECT  n1.id,n1.Respuesta_N1,n2.id,n2.Respuesta_N2,n3.id,n3.Respuesta_N3,n3.Peso,n3.Id_TipoGestion FROM Nivel1 n1 , Nivel2 n2 , Nivel3 n3 WHERE n1.id = n2.Id_Nivel1 AND n2.id = n3.Id_Nivel2",$ConFoco);
while($row = mysql_fetch_array($QueryFoco)){
    $n1 = $row[0];
    $r1 = $row[1];
    $n2 = $row[2];
    $r2 = $row[3];
    $n3 = $row[4];
    $r3 = $row[5];
    $Peso = $row[6];
    $Tipo = $row[7];

    mysql_query("UPDATE gestion_ult_trimestre SET Peso = $Peso , Id_TipoGestion = $Tipo WHERE origen = 0 and resultado = $n1 and resultado_n2 = $n2 and resultado_n3 = $n3",$ConFoco);
        
}

?>

