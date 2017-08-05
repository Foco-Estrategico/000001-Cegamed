<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("carga");
    QueryPHP_IncludeClasses("db");
    $CargaClass = new Carga();

    $Table = $_POST["Table"];
    $Field = $_POST["Field"];

    $ToReturn = array();
    $ToReturn = $CargaClass->isDateField($Table,$Field);

    echo json_encode($ToReturn);

?>