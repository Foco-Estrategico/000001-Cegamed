<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("db");
    QueryPHP_IncludeClasses("reclutamiento");
    $ReclutamientoClass = new Reclutamiento();

    $Aspirantes = $ReclutamientoClass->getAspiranteTableList();
    $Array = array();
    foreach($Aspirantes as $Aspirante){
        $ArrayTmp = array();
        $ArrayTmp["Nombre"] = $Aspirante["Nombre"];
        $ArrayTmp["Correo"] = $Aspirante["Correo"];
        $ArrayTmp["Telefono"] = $Aspirante["Telefono"];
        $ArrayTmp["Acciones"] = $Aspirante["IdUsuarioReclutamiento"];
        array_push($Array,$ArrayTmp);
    }
    echo json_encode($Array);
?>