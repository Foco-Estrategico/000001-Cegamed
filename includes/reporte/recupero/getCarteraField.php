<?php
    include_once("../../../includes/functions/Functions.php");
    include_once("../../../class/reporte/reporte.php");
    include_once("../../../class/db/DB.php");
    $ReporteClass = new Reporte();
    $Carteras = $ReporteClass->getCarteraField();
    $ToReturn = "<option value=''>Todos</option>";
    foreach($Carteras as $Cartera){
        $ToReturn .= "<option value='".$Cartera["Cartera"]."'>".utf8_encode($Cartera["Cartera"])."</option>";
    }
    echo $ToReturn;
?>