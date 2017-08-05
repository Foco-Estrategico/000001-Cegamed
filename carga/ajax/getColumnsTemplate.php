<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("carga");
    QueryPHP_IncludeClasses("db");
    $CargaClass = new Carga();
    
    $Sheet = $_POST["Sheet"];

    $ToReturn = array();
    $ToReturn["Columnas"] = $CargaClass->getColumnsTemplate($Sheet);

    echo json_encode($ToReturn);
?>