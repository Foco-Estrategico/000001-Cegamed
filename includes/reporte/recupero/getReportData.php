<?php
    include_once("../../../includes/functions/Functions.php");
    include_once("../../../class/reporte/reporte.php");
    include_once("../../../class/db/DB.php");
    $ReporteClass = new Reporte();
    $idCedente = $_POST['idCedente'];
    $idPeriodo = $_POST['idPeriodo'];
    $Cartera = $_POST['Cartera'];
    $Tramo = $_POST['Tramo'];
    $ToReturn = $ReporteClass->getReportData($idCedente,$idPeriodo,$Cartera,$Tramo);

    echo json_encode($ToReturn);
?>