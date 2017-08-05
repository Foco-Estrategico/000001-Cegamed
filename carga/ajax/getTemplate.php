<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("carga");
    QueryPHP_IncludeClasses("db");
    $CargaClass = new Carga();
    $ToReturn = array();
    $ToReturn["Template"] = array();
    $ToReturn["Sheets"] = array();
    $ToReturn["Template"]["HaveTemplate"] = false;
    $Template = $CargaClass->getTemplate();
    if(count($Template) > 0){
        $Template = $Template[0];
        $ToReturn["Template"]["HaveTemplate"] = true;
        $ToReturn["Template"]["id"] = $Template["id"];
        $ToReturn["Template"]["TipoArchivo"] = $Template["Tipo_Archivo"];
        $ToReturn["Template"]["Separador"] = $Template["Separador_Cabecero"];
        $ToReturn["Template"]["Cabecero"] = $Template["haveHeader"];
        $Sheets = $CargaClass->getSheets();
        $ToReturn["Sheets"] = $Sheets;
    }
    echo json_encode($ToReturn);
?>