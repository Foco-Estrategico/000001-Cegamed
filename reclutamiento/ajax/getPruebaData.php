<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("db");
    QueryPHP_IncludeClasses("reclutamiento");
    $ReclutamientoClass = new Reclutamiento();

    $idPrueba = $_POST['idPrueba'];

    $Prueba = $ReclutamientoClass->getPruebaData($idPrueba);
    $ToReturn = json_encode($Prueba[0]);
    echo $ToReturn;
?>