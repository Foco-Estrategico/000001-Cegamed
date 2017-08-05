<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("calidad");
    QueryPHP_IncludeClasses("db");
    $CalidadClass = new Calidad();
    $Mandante = $_POST['Mandante'];
    $Cedente = $_POST['Cedente'];
    $Periodos = $CalidadClass->getPeriodosEvaluacionesByMonthsAndYears($Mandante,$Cedente);
    $ToReturn = "<option value=''>Todos</option>";
    foreach($Periodos as $Periodo){
        $ToReturn .= "<option value='".$Periodo['Year'].$Periodo['Month']."01'>".$Periodo["MonthText"]."</option>";
    }
    echo $ToReturn;
?>