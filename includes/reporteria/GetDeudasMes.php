<?php
    include_once("../../includes/functions/Functions.php");
    include_once("../../class/reporte/reporte.php");
    QueryPHP_IncludeClasses("db");
    $reporte = new Reporte(); 
    //echo json_encode($reporte->getCantidadTipoGestion($_POST)); 
    $registros = $reporte->getDeudaMes();

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


    $deudaArray = array();
    foreach($registros as $registro){
        $Array = array();
        //$Array['year'] = $registro["year"];
        $Array['fecha'] = $Months[$registro["month"]]." ".$registro["year"]; 
        $Array['monto'] = $registro["monto"];
        array_push($deudaArray,$Array);
    }
    echo json_encode($deudaArray);
?>