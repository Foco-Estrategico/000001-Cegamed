<?php
    include_once("../../includes/functions/Functions.php");
    include_once("../../class/reporte/reporte.php");
    QueryPHP_IncludeClasses("db");
    $reporte = new Reporte(); 
    //echo json_encode($reporte->getCantidadTipoGestion($_POST)); 
    $registros = $reporte->getTotalPorSegmento();
    /*$totalesArray = array();
    foreach($registros as $registro){
        $Array = array();
        $Array['total'] = $registro["total"]; 
        $Array['segmento'] = $registro["Segmento"];
        array_push($totalesArray,$Array);
    }
    echo json_encode($totalesArray);*/
    echo json_encode($registros);
?>