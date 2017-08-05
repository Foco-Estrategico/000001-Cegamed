<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("db");
    QueryPHP_IncludeClasses("reclutamiento");
    $ReclutamientoClass = new Reclutamiento();
    $idPrueba = $_POST['idPrueba'];

    $ToReturn = $ReclutamientoClass->deletePrueba($idPrueba);
    echo json_encode($ToReturn);
?>