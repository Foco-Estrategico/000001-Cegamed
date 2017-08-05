<?php
    include_once("../../../includes/functions/Functions.php");
    include_once("../../../class/reporte/reporte.php");
    include_once("../../../class/db/DB.php");
    $ReporteClass = new Reporte();
    $Periodos = $ReporteClass->getPeriodosMandante();
    $ToReturn = "";
    foreach($Periodos as $Periodo){
        $Selected = "";
        if($Periodo["Fecha_Termino"] == "0000-00-00"){
            $Selected = "selected='selected'";
        }
        $ToReturn .= "<option ".$Selected." value='".$Periodo["ID"]."'>".utf8_encode($Periodo["descripcion"])."</option>";
    }
    echo $ToReturn;
?>