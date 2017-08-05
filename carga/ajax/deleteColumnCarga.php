<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("carga");
    QueryPHP_IncludeClasses("db");
    $CargaClass = new Carga();

    $idColumn = $_POST["Column"];

    $ToReturn = array();
    $ToReturn = $CargaClass->deleteColumn($idColumn);

    echo json_encode($ToReturn);

?>