<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("calidad");
    QueryPHP_IncludeClasses("db");
    $CalidadClass = new Calidad();
    $idAfirmacion = $_POST['idAfirmacion'];
    $Nombre = $_POST['Nombre'];
    $Ponderacion = $_POST['Ponderacion'];
    $DescripcionSimple = $_POST['DescripcionSimple'];
    $Corte = $_POST['Corte'];
    $ToReturn = $CalidadClass->UpdateAfirmacion($idAfirmacion,$Nombre,$Ponderacion,$DescripcionSimple,$Corte);
    echo utf8_encode(json_encode($ToReturn));
?>