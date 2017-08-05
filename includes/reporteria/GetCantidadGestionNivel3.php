<?php
    include_once("../../includes/functions/Functions.php");
    include_once("../../class/reporte/reporte.php");
    QueryPHP_IncludeClasses("db");
    $reporte = new Reporte(); 
    $registros = $reporte->getCantidadGestionNivel3($_POST);
    $gestionArray = array();
    foreach($registros as $registro){
        $Array = array();
        $Array['nombre'] = utf8_encode($registro["Respuesta_N3"]);
        $Array['cantidad'] = $registro["cantidad"];
        $Array['idNivel3'] = $registro["id"];
        array_push($gestionArray,$Array);
    }
    echo json_encode($gestionArray);
?>