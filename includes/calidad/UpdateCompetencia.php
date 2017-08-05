<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("calidad");
    QueryPHP_IncludeClasses("db");
    $CalidadClass = new Calidad();
    $idCompetencia = $_POST['idCompetencia'];
    $Nombre = $_POST['Nombre'];
    $Descripcion = $_POST['Descripcion'];
    $Ponderacion = $_POST['Ponderacion'];
    $Tag = $_POST['Tag'];
    $ToReturn = $CalidadClass->UpdateCompetencia($idCompetencia,$Nombre,$Descripcion,$Ponderacion,$Tag);
    echo utf8_encode(json_encode($ToReturn));
?>