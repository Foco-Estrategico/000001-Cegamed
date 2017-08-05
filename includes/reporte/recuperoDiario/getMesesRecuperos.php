<?php
    include_once("../../../includes/functions/Functions.php");
    include_once("../../../class/reporte/reporte.php");
    include_once("../../../class/db/DB.php");
    
    $ReporteClass = new Reporte();
    $Meses = $ReporteClass->getMesesRecupero();
    $ToReturn = "";
    $ActualYear = date("Y");
    $ActualMonth = date("m");
    foreach($Meses as $Mes){
        $Selected = "";
        if($Mes["Year"] == $ActualYear){
            if($Mes["Month"] == $ActualMonth){
                $Selected = "selected = 'selected'";
            }
        }
        $MesDate = strlen($Mes["Month"]) == 1 ? "0".$Mes["Month"] : $Mes["Month"];
        $ToReturn .= "<option ".$Selected." value='".$Mes["Year"].$MesDate."01'>".$Mes["MonthText"]." ".$Mes["Year"]."</option>";
    }
    echo $ToReturn;
?>