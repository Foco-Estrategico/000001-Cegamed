<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("calidad");
    QueryPHP_IncludeClasses("db");
    $CalidadClass = new Calidad();
    $idCompetencia = $_POST["idCompetencia"];
    $Evaluations = $CalidadClass->getDimensiones($idCompetencia);
    echo utf8_encode(json_encode($Evaluations));
?>