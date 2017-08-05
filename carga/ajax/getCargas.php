<?php
    include("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("carga");
    QueryPHP_IncludeClasses("db");
    
    $TipoCarga = $_POST["TipoCarga"];
    $CargaClass = new Carga();
    $Data = $CargaClass->getCargas($TipoCarga);
    $Data = utf8_ArrayConverter($Data);
    echo utf8_encode(json_encode($Data));
?>