<?php

$conexion = mysql_connect("192.168.1.8" , "root" , "s9q7l5.,777");
mysql_select_db("foco",$conexion);

$sql = mysql_query("SELECT nombre_ejecutivo FROM gestion_ult_trimestre WHERE origen = 0 ");
while($row = mysql_fetch_array($sql)){
    $nombre = $row[0];
    $query_user  = mysql_query("SELECT Id_Personal FROM Personal WHERE Nombre = '$nombre' LIMIT 1");
    while($row = mysql_fetch_array($query_user)){

        $id =  $row[0];
        $query_final=mysql_query("SELECT usuario FROM Usuarios WHERE Id_Personal = $id LIMIT 1");
        while($row = mysql_fetch_array($query_final)){
            echo $UsuarioFinal = $row[0];
            mysql_query("UPDATE gestion_ult_trimestre SET nombre_ejecutivo = '$UsuarioFinal' WHERE nombre_ejecutivo='$nombre'");

        }


    }
}


?>


