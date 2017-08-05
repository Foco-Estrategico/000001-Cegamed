<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("calidad");
    QueryPHP_IncludeClasses("personal");
    QueryPHP_IncludeClasses("db");
    $CalidadClass = new Calidad();
    $PersonalClass = new Personal();
    $idPersonal = $_POST['idPersonal'];
    $PersonalClass->Username = $idPersonal;
    $idPersonal = $PersonalClass->getPersonalIDFromUsername();
    $CalidadClass->Id_Personal = $idPersonal;
    $Periodos = $CalidadClass->getEvaluacionesByMonthsAndYears();
    $ToReturn = "";
    $ActualMonth = intval(date('m'));
    $ActualYear = intval(date('Y'));
    $ValueDate = date('Ym01');
    if(!HaveEvaluationsThisMonth($Periodos)){
        $ToReturn .= "<option value='".$ValueDate."' selected='selected' >".MonthText($ActualMonth)." ".$ActualYear."</option>";
    }
    foreach($Periodos as $Periodo){
        $Selected = "";
        $Month = strlen($Periodo["Month"]) == 1 ? "0".$Periodo["Month"] : $Periodo["Month"];
        if($Periodo["Year"] == $ActualYear){
            if($Periodo["Month"] == $ActualMonth){
                $Selected = "selected='selected'";
            }
        }
        $ToReturn .= "<option value='".$Periodo["Year"].$Month."01' ".$Selected.">".$Periodo["MonthText"]." ".$Periodo["Year"]."</option>";
    }
    echo $ToReturn;
    function HaveEvaluationsThisMonth($Periodos){
        $ToReturn = false;
        $ActualMonth = intval(date('m'));
        $ActualYear = intval(date('Y'));
        foreach($Periodos as $Periodo){
            if($Periodo["Year"] == $ActualYear){
                if($Periodo["Month"] == $ActualMonth){
                    $ToReturn = true;
                }
            }
        }
        return $ToReturn;
    }
    function MonthText($Month){
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
        return $Months[$Month];
    }
?>