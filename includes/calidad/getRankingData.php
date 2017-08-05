<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("calidad");
    QueryPHP_IncludeClasses("db");
    $CalidadClass = new Calidad();
    $Mandante = $_POST['Mandante'];
    $Cedente = $_POST['Cedente'];
    $Periodo = $_POST['Periodo'];
    $Ejecutivos = $CalidadClass->getRankingData($Mandante,$Cedente,$Periodo);
    $Array = array();
    foreach($Ejecutivos as $Ejecutivo){
        $Nombre = utf8_encode($Ejecutivo["Nombre"]);
        $Nota = $Ejecutivo["Nota"];
        $ArrayTmp = array();
        $ArrayTmp["Ejecutivo"] = $Nombre;
        $ArrayTmp["Nota"] = $Nota;
        array_push($Array,$ArrayTmp);
    }
    echo json_encode($Array);
?>