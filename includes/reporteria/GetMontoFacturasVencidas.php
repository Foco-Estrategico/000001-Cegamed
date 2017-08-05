<?php
    include_once("../../includes/functions/Functions.php");
    include_once("../../class/reporte/reporte.php");
    QueryPHP_IncludeClasses("db");
    $reporte = new Reporte(); 
    $registros = $reporte->getMontoFacturasVencidas();
    echo json_encode($registros);
?>