<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("calidad");
    QueryPHP_IncludeClasses("db");
    $CalidadClass = new Calidad();
    $ID = $_POST['ID'];
    $ToReturn = $CalidadClass->deletePlan($ID);
    echo json_encode($ToReturn);
?>