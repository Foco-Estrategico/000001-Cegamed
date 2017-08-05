<?php
	if(!isset($_SESSION)){
        session_start();
    }
    //$dir_subida = '../../task/CargaAsignaciones/Asignaciones/'.$_SESSION['mandante'].'/'.$_SESSION['cedente'].'/';
    if (!file_exists("../../task/CargaAsignaciones/Asignaciones/".$_SESSION['mandante']."/".$_SESSION['cedente'])){
            mkdir("../../task/CargaAsignaciones/Asignaciones/".$_SESSION['mandante']."/".$_SESSION['cedente'], 0777, true);
        }
    $dir_subida = '../../task/CargaAsignaciones/Asignaciones/'.$_SESSION['mandante'].'/'.$_SESSION['cedente'].'/';
    $files = scandir($dir_subida); // Devuelve un vector con todos los archivos y directorios
    $ficherosEliminados = 0;
    foreach($files as $file){
        if (is_file($dir_subida.$file)) {
            if (unlink($dir_subida.$file) ){
                $ficherosEliminados++;
            }
        }
    }
	$fichero_subido = $dir_subida . basename($_FILES['file']['name']);
	if (move_uploaded_file($_FILES['file']['tmp_name'], $fichero_subido)) {
	    $upload = true;

        shell_exec("chmod 777 ".$fichero_subido);

        //shell_exec("php /var/www/html/produccion/task/CargaAsignaciones/Java.php");


        //shell_exec('java -jar -Xms1g -Xmx6g -XX:+UseConcMarkSweepGC -XX:+CMSIncrementalMode -XX:SurvivorRatio=16 /var/www/html/produccion/task/CargaAsignaciones/CargaAsignacionAutomatica.jar "/var/www/html/produccion/task/CargaAsignaciones/" "'.$_SESSION['mandante'].'/'.$_SESSION['cedente'].'" "'.$_SESSION['id_usuario'].'" > /dev/null 2>&1 &');
        shell_exec('java -jar -Xms1g -Xmx6g -XX:+UseConcMarkSweepGC -XX:+CMSIncrementalMode -XX:SurvivorRatio=16 /var/www/html/produccion/task/CargaAsignaciones/CargaAsignacionAutomatica.jar "/var/www/html/produccion/task/CargaAsignaciones/" "'.$_SESSION['mandante'].'/'.$_SESSION['cedente'].'" "'.$_SESSION['id_usuario'].'" > /dev/null 2>&1 &');
        //shell_exec('java -jar -Xms1g -Xmx6g -XX:+UseConcMarkSweepGC -XX:+CMSIncrementalMode -XX:SurvivorRatio=16 D:/Xampp/htdocs/produccion/task/CargaAsignaciones/CargaAsignacionAutomatica.jar "D:/Xampp/htdocs/produccion/task/CargaAsignaciones/" "'.$_SESSION['mandante'].'/'.$_SESSION['cedente'].'" "'.$_SESSION['id_usuario'].'"');
	} else {
	    $upload = false;
	}
 ?>
