<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("calidad");
    QueryPHP_IncludeClasses("db");
    $CalidadClass = new Calidad();
    $Nombre = $_POST['Nombre'];
    $Valor = $_POST['Valor'];
    $DescripcionCaracteristica = $_POST['DescripcionCaracteristica'];
    $idAfirmacion = $_POST['idAfirmacion'];
    $ToReturn = $CalidadClass->SaveOpcionAfirmacion($Nombre,$Valor,$DescripcionCaracteristica,$idAfirmacion);
    echo utf8_encode(json_encode($ToReturn));
?>