<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("db");
    QueryPHP_IncludeClasses("reclutamiento");
    $ReclutamientoClass = new Reclutamiento();
    //$pruebas = $ReclutamientoClass->getPruebasUsuario($_POST['idAspirante']);
    $startDate = $_POST["startDate"];
    $endDate = $_POST["endDate"];
    $Perfil = $_POST["perfil"];
    $Aspirante = $_POST["aspirante"];
    $pruebas = $ReclutamientoClass->getCalificacionesAspirantes($startDate,$endDate,$Perfil,$Aspirante);
    echo json_encode($pruebas);
?>