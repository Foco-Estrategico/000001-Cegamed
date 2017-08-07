<?php
$host_name = '192.168.1.10';
$pass_word = 's9q7l5.,777';
$user_name = 'root';
$database_name = 'foco';
$conn = mysql_connect($host_name, $user_name, $pass_word) or die ('Error connecting to mysql');
mysql_select_db($database_name);

$Rut;
$i = 0;
$j=0;
$QueryPersonas = mysql_query("SELECT Rut FROM Persona ");

while($row = mysql_fetch_array($QueryPersonas)){
    echo $j;
    $Rut = $row[0];
    $SelectFono = mysql_query("SELECT fono_discado FROM gestion_ult_trimestre WHERE rut_cliente = $Rut");
    while($row = mysql_fetch_array($SelectFono)){
        echo $i;
        $Fono = $row[0];
        $SelecCantidad = mysql_query("SELECT rut_cliente FROM gestion_ult_trimestre WHERE rut_cliente = $Rut AND fono_discado = $Fono and Id_TipoGestion = 5");
        $Cantidad  = mysql_num_rows($SelecCantidad);
        if($Cantidad > 0){
            echo "fono : ".$Fono." Con Compromiso"."<br>";
            mysql_query("UPDATE fono_cob SET color=36 WHERE formato_subtel = $Fono and Rut = $Rut");
        }
        else
        {
            echo "No"."<br>";
        }
        $i++;
    }
    $j++;

}

?>