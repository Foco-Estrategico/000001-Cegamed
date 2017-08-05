<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("carga");
    QueryPHP_IncludeClasses("db");
    $CargaClass = new Carga();

    $Tabla = $_POST["Tabla"];
    $idSheet = $_POST["idSheet"];

    $ToReturn = array();
    $ToReturn = $CargaClass->deleteColumnsCargaFromTable($Tabla,$idSheet);

    echo json_encode($ToReturn);

?>