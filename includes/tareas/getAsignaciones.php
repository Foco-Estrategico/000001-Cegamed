<?php
    include("../../includes/functions/Functions.php");
    require '../../plugins/PHPExcel-1.8/Classes/PHPExcel.php';
    
    QueryPHP_IncludeClasses("tareas");
    QueryPHP_IncludeClasses("db");
    $tareas = new Tareas();
    $idCola = $_POST['idCola'];
    $ExisteCola = $tareas->ExisteCola($idCola);
    if($ExisteCola){
        $tareas->actualizarCola($idCola);
        $ExisteAsignacion = $tareas->ExisteAsignacion($idCola);
        if($ExisteAsignacion){
            $tareas->updateAsignaciones("QR_".$_SESSION['cedente']."_".$idCola);
        }
    }else{
        $tareas->activarCola($idCola);
    }
    $Asignaciones = $tareas->getAsignaciones($idCola);
    //$Files = $tareas->getAsignacionesArchivos($idCola);
    $Files = array();
    $Files["Tipo1"] = $tareas->getAsignacionesArchivos($idCola,'1');
    $Files["Tipo2"] = $tareas->getAsignacionesArchivos($idCola,'2');
    //$Files["Tipo3"] = $tareas->getAsignacionesArchivos($idCola);
    $ToReturn = array();
    $ToReturn["Asiganciones"] = $Asignaciones;
    $ToReturn["Archivos"] = $Files;
    echo json_encode($ToReturn);
?>