<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("db");
    QueryPHP_IncludeClasses("reclutamiento");
    $ReclutamientoClass = new Reclutamiento();
    $idUsuario = $_POST['idUsuario'];

    $ToReturn = $ReclutamientoClass->deleteAspirante($idUsuario);
    echo json_encode($ToReturn);
?>