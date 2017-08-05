<?php
    include_once("../../../includes/functions/Functions.php");
    include_once("../../../class/reporte/reporte.php");
    include_once("../../../class/db/DB.php");
    
    $Mes = $_POST["Mes"];
    $CantDiasMes = date("t",strtotime($Mes));
    $ArrayRecuperoDiario = array();
    $ArrayRecuperoAcumulado = array();
    for($i=1;$i<=$CantDiasMes;$i++){
        $ArrayTmp = array();
        $ArrayTmp["Dia"] = $i;
        $ArrayTmp["Monto"] = "0";

        array_push($ArrayRecuperoDiario,$ArrayTmp);
        array_push($ArrayRecuperoAcumulado,$ArrayTmp);
    }
    $ReporteClass = new Reporte();
    $Pagos = $ReporteClass->getPagosMes($Mes);
    foreach($ArrayRecuperoDiario as $keyRecupero => $DiaRecupero){
        $Dia = $DiaRecupero["Dia"];
        foreach($Pagos as $Pago){
            $DiaPago = $Pago["Dia"];
            if($Dia == $DiaPago){
                $ArrayRecuperoDiario[$keyRecupero]["Monto"] = $Pago["Monto"];
            }
        }
    }
    $SumRecupero = 0;
    foreach($ArrayRecuperoDiario as $keyRecupero => $DiaRecupero){
        $SumRecupero += $DiaRecupero["Monto"];
        $ArrayRecuperoAcumulado[$keyRecupero]["Monto"] = $SumRecupero;
    }
    $ToReturn = array();
    $ToReturn["Diario"] = $ArrayRecuperoDiario;
    $ToReturn["Acumulado"] = $ArrayRecuperoAcumulado;
    echo json_encode($ToReturn);
?>