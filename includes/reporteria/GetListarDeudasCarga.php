<?php
    include_once("../../includes/functions/Functions.php");
    include_once("../../class/reporte/reporte.php");
    QueryPHP_IncludeClasses("db");
    $reporte = new Reporte(); 
    $registros = $reporte->getDatosDeudaCarga(); 
    $deudasArray = array();
    foreach($registros as $registro){
        $Array = array();
        $Array['Nombre'] = utf8_encode($registro["Nombre_Completo"]);
        $Array['Rut'] = $registro["Rut"];  
        $Array['Monto'] = number_format($registro["Monto_Mora"], 0, '', '.');
        $Array['id'] = $registro["Id_deuda"];
        $Array['FechaVencimiento'] = $registro["Fecha_Vencimiento"];
        $Array['numeroFactura'] = $registro["Numero_Factura"];
        array_push($deudasArray,$Array);
    }
    echo json_encode($deudasArray);  
?>