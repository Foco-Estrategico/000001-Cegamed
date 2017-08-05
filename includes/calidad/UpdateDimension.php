<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("calidad");
    QueryPHP_IncludeClasses("db");
    $CalidadClass = new Calidad();
    $idDimension = $_POST['idDimension'];
    $Nombre = $_POST['Nombre'];
    $Ponderacion = $_POST['Ponderacion'];
    $ToReturn = $CalidadClass->UpdateDimension($idDimension,$Nombre,$Ponderacion);
    echo utf8_encode(json_encode($ToReturn));
?>