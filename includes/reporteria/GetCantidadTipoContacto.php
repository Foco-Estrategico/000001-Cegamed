<?php
    include_once("../../includes/functions/Functions.php");
    include_once("../../class/reporte/reporte.php");
    QueryPHP_IncludeClasses("db");
    $reporte = new Reporte(); 
    //echo json_encode($reporte->getCantidadTipoGestion($_POST)); 
    $registros = $reporte->getCantidadTipoGestion($_POST);
    $gestionArray = array();
    foreach($registros as $registro){
        $Array = array();
        $Array['nombre'] = utf8_encode($registro["Nombre"]);
        $Array['cantidad'] = $registro["cantidad"]; 
        $Array['idTipoContacto'] = $registro["Id_TipoGestion"];
        array_push($gestionArray,$Array);
    }
    echo json_encode($gestionArray);
?>