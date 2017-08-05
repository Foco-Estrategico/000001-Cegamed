<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("carga");
    QueryPHP_IncludeClasses("db");
    $CargaClass = new Carga();

    $idSheet = $_POST["Sheet"];

    $ToReturn = array();
    $ToReturn = $CargaClass->deleteSheet($idSheet);

    echo json_encode($ToReturn);

?>