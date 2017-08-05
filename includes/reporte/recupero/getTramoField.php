<?php
    include_once("../../../includes/functions/Functions.php");
    include_once("../../../class/reporte/reporte.php");
    include_once("../../../class/db/DB.php");
    $ReporteClass = new Reporte();
    $Tramos = $ReporteClass->getTramoField();
    $ToReturn = "<option value=''>Todos</option>";
    foreach($Tramos as $Tramo){
        $ToReturn .= "<option value='".$Tramo["Tramo"]."'>".utf8_encode($Tramo["Tramo"])."</option>";
    }
    echo $ToReturn;
?>