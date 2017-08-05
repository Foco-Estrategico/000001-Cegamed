<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("carga");
    QueryPHP_IncludeClasses("db");
    $CargaClass = new Carga();
    
    $ToReturn = array();
    $dir_subida = '../../facturas/Tmp/'.$_SESSION['mandante'].'/'.$_SESSION['cedente'].'/';
    if (!file_exists($dir_subida)){
        mkdir($dir_subida, 0777, true);
    }
    $cantFiles = count($_FILES['file']['tmp_name']);
    for($i=0;$i<=$cantFiles - 1;$i++){
        $fichero_subido = $dir_subida . basename($_FILES['file']['name'][$i]);
        $PosEspace = strrpos(basename($_FILES['file']['name'][$i])," ");
        if($PosEspace === false){
            if(move_uploaded_file($_FILES['file']['tmp_name'][$i], $fichero_subido)){
                shell_exec("chmod 777 ".$fichero_subido);
            }else{
            }
        }        
    }
    $ficheros = scandir($dir_subida);
    foreach($ficheros as $fichero){
        switch($fichero){
            case ".":
            case "..":
            break;
            default:
                $Factura = str_replace(".pdf","",$fichero);
                $Result = $CargaClass->ExisteFactura($Factura);
                if($Result["result"]){
                    $Rut = $Result["Rut"];
                    $dir_Move = '../../facturas/'.$_SESSION['mandante'].'/'.$_SESSION['cedente'].'/'.$Rut;
                    if(!file_exists($dir_Move)){
                        mkdir($dir_Move, 0777, true);
                    }
                    rename($dir_subida."/".$fichero, $dir_Move."/".$fichero);
                }else{
                    $CargaClass->InsertFacturaInubicable($Factura);
                }
            break;
        }
    }
    //print_r($ficheros);
    /* $Result = $CargaClass->HaveCargaAutomaticaEnCurso();

    if($Result["result"]){
        $ToReturn["result"] = true;
        $ToReturn["comment"] = $Result["comment"];
        $ToReturn["filename"] = $Result["filename"];
        $ToReturn["usuario"] = $Result["usuario"];
    }else{
        $ToReturn["result"] = true;
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

            $ToReturn["comment"] = "Procesando";
            $ToReturn["filename"] = basename($_FILES['file']['name']);
            $ToReturn["usuario"] = $_SESSION['nombreUsuario'];

            shell_exec("chmod 777 ".$fichero_subido);

            //shell_exec("php /var/www/html/produccion/task/CargaAsignaciones/Java.php");


            //shell_exec('java -jar -Xms1g -Xmx6g -XX:+UseConcMarkSweepGC -XX:+CMSIncrementalMode -XX:SurvivorRatio=16 /var/www/html/produccion/task/CargaAsignaciones/CargaAsignacionAutomatica.jar "/var/www/html/produccion/task/CargaAsignaciones/" "'.$_SESSION['mandante'].'/'.$_SESSION['cedente'].'" "'.$_SESSION['id_usuario'].'" > /dev/null 2>&1 &');
            shell_exec('java -jar -Xms1g -Xmx6g -XX:+UseConcMarkSweepGC -XX:+CMSIncrementalMode -XX:SurvivorRatio=16 /var/www/html/produccion/task/CargaAsignaciones/CargaAsignacionAutomatica.jar "'.$_POST['TipoCarga'].'" "/var/www/html/produccion/task/CargaAsignaciones/" "'.$_SESSION['mandante'].'/'.$_SESSION['cedente'].'" "'.$_SESSION['id_usuario'].'" "'.$_POST['MarcaData'].'" > /dev/null 2>&1 &');
            //shell_exec('java -jar -Xms1g -Xmx6g -XX:+UseConcMarkSweepGC -XX:+CMSIncrementalMode -XX:SurvivorRatio=16 D:/Xampp/htdocs/produccion/task/CargaAsignaciones/CargaAsignacionAutomatica.jar "D:/Xampp/htdocs/produccion/task/CargaAsignaciones/" "'.$_SESSION['mandante'].'/'.$_SESSION['cedente'].'" "'.$_SESSION['id_usuario'].'"');
        } else {
            $upload = false;
            $ToReturn["result"] = false;
            $ToReturn["lala"] = basename($_FILES['file']['name']);
        }   
    }
    echo json_encode($ToReturn); */
 ?>
