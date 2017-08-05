<?php
    include("../../includes/functions/Functions.php");
    require '../../plugins/PHPExcel-1.8/Classes/PHPExcel.php';
    //ini_set('max_execution_time', 2500);
    QueryPHP_IncludeClasses("tareas");
    QueryPHP_IncludeClasses("db");
    $tareas = new Tareas();

    $Cedente = $_POST['Cedente'];
    $startDate = $_POST["startDate"];
    $endDate = $_POST["endDate"];
    $ToReturn = $tareas->descargarGestiones($Cedente,$startDate,$endDate);
    echo json_encode($ToReturn);
?>