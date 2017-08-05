<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("carga");
    QueryPHP_IncludeClasses("db");
    $CargaClass = new Carga();
    $idFacturas = $_POST["idFacturas"];
    $ToReturn = $CargaClass->deleteFacturasInubicables($idFacturas);
    if($ToReturn["result"]){
        $Facturas = $ToReturn["Facturas"];
        $DirFacturasInubicables = "../../facturas/Tmp/".$_SESSION['mandante']."/".$_SESSION['cedente']."/";
        foreach($Facturas as $Factura){
            if(file_exists($DirFacturasInubicables.$Factura.".pdf")){
                unlink($DirFacturasInubicables.$Factura.".pdf");
            }
        }
    }
    echo json_encode($ToReturn);
?>