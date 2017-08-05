<?php
    include("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("carga");
    QueryPHP_IncludeClasses("db");
    
    $Tabla = $_POST["Tabla"];
    $ID = $_POST["ID"];
    $CargaClass = new Carga();
    $ToReturn = $CargaClass->deleteRowCarga($Tabla,$ID);
    echo json_encode($ToReturn);
?>