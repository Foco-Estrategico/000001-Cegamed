<?php
    include("../../includes/functions/Functions.php");
    require '../../plugins/PHPExcel-1.8/Classes/PHPExcel.php';
    ini_set('max_execution_time', 2500);
    QueryPHP_IncludeClasses("tareas");
    QueryPHP_IncludeClasses("db");
    
    $tareas = new Tareas();
    $ToReturn = $tareas->DownloadReporteDialAsignacion();
    echo json_encode($ToReturn);
?>