<?php
    include_once("../../../includes/functions/Functions.php");
    include_once("../../../class/reporte/reporte.php");
    include_once("../../../class/db/DB.php");
    $ReporteClass = new Reporte();
    $Cedentes = $ReporteClass->getCedentes();
    $ToReturn = "<option value=''>Todos</option>";
    foreach($Cedentes as $Cedente){
        $ToReturn .= "<option value='".$Cedente["idCedente"]."'>".utf8_encode($Cedente["Nombre"])."</option>";
    }
    echo $ToReturn;
?>