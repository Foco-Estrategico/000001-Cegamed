<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("db");
    QueryPHP_IncludeClasses("reclutamiento");
    $ReclutamientoClass = new Reclutamiento();

    $idPrueba = $_POST['idPrueba'];
    $idPerfil = $_POST['idPerfil'];
    $idTest = $_POST['idTest'];

    $Prueba = $ReclutamientoClass->updatePrueba($idPrueba,$idPerfil,$idTest);
    $ToReturn = json_encode($Prueba);
    echo $ToReturn;
?>