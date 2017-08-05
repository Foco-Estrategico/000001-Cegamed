<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("calidad");
    QueryPHP_IncludeClasses("db");
    $CalidadClass = new Calidad();
    $Periodos = $CalidadClass->getPeriodoCierreEjecutivos();
    $ToReturn = "";


    $Months = array();
    $Months[1] = "Enero";
    $Months[2] = "Febrero";
    $Months[3] = "Marzo";
    $Months[4] = "Abril";
    $Months[5] = "Mayo";
    $Months[6] = "Junio";
    $Months[7] = "Julio";
    $Months[8] = "Agosto";
    $Months[9] = "Septiembre";
    $Months[10] = "Octubre";
    $Months[11] = "Noviembre";
    $Months[12] = "Diciembre";


    $ActualMonth = strlen(date("m")) == 1 ? "0".date("m") : date("m");
    $ActualYear = date("Y");
    $ToReturn .= "<option value='".$ActualYear.$ActualMonth."01' selected='selected'>".$Months[intval(date("m"))]." ".$ActualYear."</option>";
    

    foreach($Periodos as $Periodo){
        $Month = strlen($Periodo["Month"]) == 1 ? "0".$Periodo["Month"] : $Periodo["Month"];
        switch($Periodo["Year"]){
            case $ActualYear:
                switch($Month){
                    case $ActualMonth:
                    break;
                    default:
                        $ToReturn .= "<option value='".$Periodo["Year"].$Month."01'>".$Months[$Periodo["Month"]]." ".$Periodo["Year"]."</option>";
                    break;
                }
            break;
            default:
                $ToReturn .= "<option value='".$Periodo["Year"].$Month."01'>".$Months[$Periodo["Month"]]." ".$Periodo["Year"]."</option>";
            break;
        }
        
    }

    echo $ToReturn;
?>