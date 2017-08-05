<?php
include("../class/db/DB.php");
$DB = new DB();
$FechaGestion  = date('Y-m-d');
echo $SqlRecords = "SELECT * FROM gestion_ult_trimestre WHERE origen = 0 and nombre_grabacion != '' AND fecha_gestion = '$FechaGestion'";
$Records = $DB->select($SqlRecords);
foreach($Records as $Record){
    echo $nombre = $Record['nombre_grabacion'];
    $id = $Record['id_gestion'];
    $nombreExplode = explode("_",$nombre);
    $FechaHora = $nombreExplode[0];
    $Fono = $nombreExplode[1];
    $Lista = $nombreExplode[2];
    $Wav = $nombreExplode[3];
    $ListaFinal = '';
    if($Lista<=9 ){
        $ListaFinal = "00".$Lista;
    }
    elseif($Lista<=99 && $Lista>9){
        $ListaFinal = "0".$Lista;
    }
    else{
        $ListaFinal = $Lista;
    }
    $ruta = "/var/spool/asterisk/monitor/";
    $in = $ruta.$nombre."-in.wav";
    $out = $ruta.$nombre."-out.wav";
    if(file_exists($in) && file_exists($out)){
        echo "Existe";
        $SqlRecordsUpdate = "UPDATE `gestion_ult_trimestre` SET sox=1 WHERE id_gestion = $id";
        $DB->query($SqlRecordsUpdate);
        $Final = $FechaHora."_".$Fono."_".$ListaFinal."_".$Wav;
        $ArchivoFinal = $ruta.$Final."-all.wav";
        $ruta_final = "/var/www/html/produccion/task/monitor/";
        $ruta_final2 = "/var/www/html/produccion/Records/Tmp/";
        shell_exec("sox -m $in $out $ArchivoFinal");
        shell_exec("cp $ArchivoFinal $ruta_final ");
        shell_exec("cp $ArchivoFinal $ruta_final2 ");
        shell_exec("rm $in $out $ArchivoFinal");
    }
    
} 
function listar_archivos($carpeta){
    if(is_dir($carpeta)){
        if($dir = opendir($carpeta)){
            while(($archivo = readdir($dir)) !== false){
                if($archivo != '.' && $archivo != '..' && $archivo != '.htaccess'){
                     echo "ACA ARCHIVO : ".$archivo;
                     $Ruta = "/var/www/html/produccion/Records/";
                     $nombre = $archivo;
                     $Explode = explode("_",$nombre);
                     $Cedente = $Explode[2];
                     $Cedente = $Ruta.$Cedente;
                     $FechaFilter = $Explode[0];
                     $FechaFilter = explode("-",$FechaFilter);
                     $Fecha = $FechaFilter[0];
                     $UsuarioFilter = $Explode[3];
                     $UsuarioFilter = explode("-",$UsuarioFilter);
                     $Usuario = $UsuarioFilter[0];
                     $CedenteFecha = $Cedente."/".$Fecha;
                     $CedenteFechaUsuario = $CedenteFecha."/".$Usuario;
                     echo "Ruta FINAL : ".$CedenteFechaUsuario;
                    if(file_exists($Cedente)){
                        echo "No Hacer Nada";
                    }
                    else{
                        shell_exec("mkdir $Cedente");
                    }

                    if(file_exists($CedenteFecha)){
                        echo "No Hacer Nada";
                    }
                    else{
                        shell_exec("mkdir $CedenteFecha");
                    }
                    if(file_exists($CedenteFechaUsuario)){
                        echo "No Hacer Nada";
                    }
                    else{
                        shell_exec("mkdir $CedenteFechaUsuario");
                    }

                    $RutaB = "/var/www/html/produccion/task/monitor/";
                    $RutaGrabacion = $RutaB.$nombre;
                    echo $RutaGrabacion;
                    shell_exec("cp $RutaGrabacion $CedenteFechaUsuario");
                    shell_exec("rm $RutaGrabacion");
                }
            }
            closedir($dir);
        }
    }
}
listar_archivos('/var/www/html/produccion/task/monitor/');
?>


