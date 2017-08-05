<?php
    include_once("../../includes/functions/Functions.php");
    include_once("../../plugins/PHPExcel-1.8/Classes/PHPExcel.php");
    QueryPHP_IncludeClasses("db");
    QueryPHP_IncludeClasses("reclutamiento");
    $ReclutamientoClass = new Reclutamiento();
    $startDate = $_POST["startDate"];
    $endDate = $_POST["endDate"];
    $idPerfil = $_POST["perfil"];
    $Aspirante = $_POST["aspirante"];
    $File = $ReclutamientoClass->getTestReport($startDate,$endDate,$idPerfil,$Aspirante);
    echo json_encode($File);
?>