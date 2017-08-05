<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("db");
    QueryPHP_IncludeClasses("reclutamiento");
    $ReclutamientoClass = new Reclutamiento();
    $Prueba = $_POST["prueba"];

    $GraphData = $ReclutamientoClass->getGraphData($Prueba);
    //print_r($GraphData);
    echo json_encode($GraphData);
    
?>