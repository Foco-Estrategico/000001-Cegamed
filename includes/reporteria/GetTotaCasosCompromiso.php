<?php
    include_once("../../includes/functions/Functions.php");
    include_once("../../class/reporte/reporte.php");
    QueryPHP_IncludeClasses("db");
    $reporte = new Reporte(); 
    //echo json_encode($reporte->getCantidadTipoGestion($_POST)); 
    $registros = $reporte->getTotalCasosCompromiso($_POST);
    echo json_encode($registros);
?>