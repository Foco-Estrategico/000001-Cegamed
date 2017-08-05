<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("calidad");
    QueryPHP_IncludeClasses("db");
    $CalidadClass = new Calidad();
    $idOpcionAfirmacion = $_POST['idOpcionAfirmacion'];
    $ToReturn = $CalidadClass->GetOpcionAfirmacion($idOpcionAfirmacion);
    echo utf8_encode(json_encode($ToReturn));
?>