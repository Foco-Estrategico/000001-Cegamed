<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("db");
    QueryPHP_IncludeClasses("reclutamiento");
    $ReclutamientoClass = new Reclutamiento();
    $startDate = $_POST["startDate"];
    $endDate = $_POST["endDate"];
    $Perfil = $_POST["perfil"];
    $Aspirante = $_POST["aspirante"];

    $Calificaciones = $ReclutamientoClass->getPruebasUsuario($startDate,$endDate,$Perfil,$Aspirante);
    echo json_encode($Calificaciones);
?>