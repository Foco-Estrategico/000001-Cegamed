<?php
    include_once("../../includes/functions/Functions.php");
    include_once("../../class/reporte/reporte.php");
    QueryPHP_IncludeClasses("db");
    $reporte = new Reporte(); 
    //echo json_encode($reporte->getCantidadTipoGestion($_POST)); 
    $registros = $reporte->getCasosPorSegmento($_POST);
    $casosArray = array();
    foreach($registros as $registro){
        $Array = array();
        $Array['nombre'] = $registro["Nombre_Completo"];
        $Array['total'] = $registro["total"]; 
        $Array['cantidadFactura'] = $registro["cantidadFactura"];
        $Array['marca'] = $registro["marca"];
        array_push($casosArray,$Array);
    }
    echo json_encode($casosArray);
?>