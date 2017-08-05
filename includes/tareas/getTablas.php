<?php
    include("../../includes/functions/Functions.php");
    require '../../class/estrategia/config_tablas.php';

    QueryPHP_IncludeClasses("db");
    $configTablasClass = new configTablas();
    $Tablas = $configTablasClass->getListar_tablas($_SESSION['cedente']);
    $ToReturn = "";
    $Tablas = array_sort($Tablas,"nombre");
    foreach($Tablas as $Tabla){
        $ToReturn .= "<option value='".$Tabla["Actions"]."'>".$Tabla["nombre"]."</option>";
    }
    echo $ToReturn;
?>