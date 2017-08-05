<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("carga");
    QueryPHP_IncludeClasses("db");
    $CargaClass = new Carga();
    $ToReturn = "<option value=''>Seleccione...</option>";
    $Funciones = $CargaClass->getFuncionesColumnasExcel();
    foreach($Funciones as $Funcion){
        $ToReturn .= "<option value='".$Funcion["Codigo"]."'>".$Funcion["Codigo"]."</option>";
    }
    echo $ToReturn;
?>