<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("carga");
    QueryPHP_IncludeClasses("db");
    $CargaClass = new Carga();

    $Sheet = $_POST["Sheet"];
    $NombreHoja = $_POST["NombreHoja"];
    $NumeroHoja = $_POST["NumeroHoja"];
    $TipoCarga = $_POST["TipoCarga"];

    $ToReturn = array();
    $ToReturn = $CargaClass->updateSheetCarga($Sheet,$NombreHoja,$NumeroHoja,$TipoCarga);

    echo json_encode($ToReturn);

?>