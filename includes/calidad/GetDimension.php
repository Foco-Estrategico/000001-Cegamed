<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("calidad");
    QueryPHP_IncludeClasses("db");
    $CalidadClass = new Calidad();
    $idDimension = $_POST['idDimension'];
    $ToReturn = $CalidadClass->GetDimension($idDimension);
    echo utf8_encode(json_encode($ToReturn));
?>