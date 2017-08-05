<?php
    include("../../includes/functions/Functions.php");
    require '../../class/estrategia/config_tablas.php';
    QueryPHP_IncludeClasses("tareas");
    QueryPHP_IncludeClasses("db");
    $tareas = new Tareas();
    $configTablasClass = new configTablas();
    $ID = $_POST['ID'];
    $Nombre = $_POST['Nombre'];
    $TipoCampo = $_POST['TipoCampo'];    
    $Operacion = $_POST['Operacion'];
    $Tabla = $_POST['Tabla'];
    $Campo = $_POST['Campo'];
    switch($TipoCampo){
        case "1":
            $Tabla = $configTablasClass->getNombreTabla($Tabla);
            $Campo = $configTablasClass->getNombreCampo($Campo);
        break;
    }
    $ToReturn = $tareas->updateColumnaAsignacion($ID,$Nombre,$TipoCampo,$Tabla,$Campo,$Operacion);
    echo json_encode($ToReturn);
?>