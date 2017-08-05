<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("db");
    QueryPHP_IncludeClasses("reclutamiento");
    $ReclutamientoClass = new Reclutamiento();
    $Nombres = $_POST['Nombres'];
    $Apellidos = $_POST['Apellidos'];
    $Telefono = $_POST['Telefono'];
    $Correo = $_POST['Correo'];

    $ToReturn = $ReclutamientoClass->crearAspirante($Nombres,$Apellidos,$Telefono,$Correo);
    echo json_encode($ToReturn);
?>