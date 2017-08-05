<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("calidad");
    QueryPHP_IncludeClasses("db");
    $CalidadClass = new Calidad();
    $idAfirmacion = $_POST["idAfirmacion"];
    $Evaluations = $CalidadClass->getOpcionesAfirmaciones($idAfirmacion);
    echo utf8_encode(json_encode($Evaluations));
?>