<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("calidad");
    QueryPHP_IncludeClasses("db");
    $CalidadClass = new Calidad();
    $Nombre = $_POST['Nombre'];
    $Ponderacion = $_POST['Ponderacion'];
    $DescripcionSimple = $_POST['DescripcionSimple'];
    $Corte = $_POST['Corte'];
    $idDimension = $_POST['idDimension'];
    $ToReturn = $CalidadClass->SaveAfirmacion($Nombre,$Ponderacion,$DescripcionSimple,$Corte,$idDimension);
    echo utf8_encode(json_encode($ToReturn));
?>