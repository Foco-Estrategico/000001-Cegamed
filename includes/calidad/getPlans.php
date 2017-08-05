<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("calidad");
    QueryPHP_IncludeClasses("db");
    $CalidadClass = new Calidad();
    $Ejecutivo = $_POST['Ejecutivo'];
    $Month = $_POST['Month'];
    $ToReturn = $CalidadClass->getPlans($Ejecutivo,$Month);
    echo json_encode($ToReturn);
?>