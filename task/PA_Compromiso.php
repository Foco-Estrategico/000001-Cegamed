<?php
include("../class/db/DB.php");

$Cero = date('Y-m-d');
$Cero = $Cero." 00:00:00";
$FechaActual = date('Y-m-d H:i:s');
$Manana = date('Y-m-d');
$Manana = strtotime ( '+1 day' , strtotime ( $Manana ) ) ;
$Manana = date ( 'Y-m-d' , $Manana );
$Manana = $Manana." 00:00:00";
$Futuro = date('Y-m-d');
$Futuro = strtotime ( '+2 day' , strtotime ( $Futuro ) ) ;
$Futuro = date ( 'Y-m-d' , $Futuro );
$Futuro = $Futuro." 00:00:00";
$Hoy = date('Y-m-d');
$Hoy = $Hoy." 23:59:00";

$DB = new DB();
$Sql  = "SELECT id,Rut,FechaCompromiso,MontoCompromiso,NumeroFactura FROM Agendamiento_Compromiso";
$Registros = $DB->select($Sql);
foreach($Registros as $Registro){
    $Rut = $Registro['Rut'];
    $id = $Registro['id'];
    $FechaCompromiso = $Registro['FechaCompromiso'];
    $FechaCompromisoFinal = explode(" ",$FechaCompromiso);
    $FechaFinal = $FechaCompromisoFinal[0];
    $MontoCompromiso = $Registro['MontoCompromiso'];
    $NumeroFactura = $Registro['NumeroFactura'];
    $SqlPagos = "SELECT Rut,Fecha_Pago,Monto,Numero_Factura FROM pagos_deudas WHERE Rut = $Rut";
    $Pagos= $DB->select($SqlPagos);
    $FechaPago = '';
    $MontoPago = '';
    $Numero_Factura = '';
    foreach($Pagos as $Pago){
        $FechaPago = $Pago['Fecha_Pago'];
        $MontoPago = $Pago['Monto'];
        $Numero_Factura = $Pago['Numero_Factura'];
    }    
    if($FechaPago == $FechaFinal && $MontoCompromiso >= $MontoPago && $NumeroFactura==$Numero_Factura){
        echo "Pagado , Eliminar";
        mysql_query("UPDATE Agendamiento_Compromiso SET Compromiso = 'Pagado' WHERE id=$id");
    }
    else{
        if($FechaCompromiso<$Cero){
            echo "Roto"."-".$Rut."-".$FechaCompromiso;
            mysql_query("UPDATE Agendamiento_Compromiso SET Compromiso = 'Roto' WHERE id=$id");

        }
        elseif($FechaCompromiso < $Manana && $FechaCompromiso >= $Cero){
            echo "Vence Hoy"."-".$Rut."-".$FechaCompromiso;
            mysql_query("UPDATE Agendamiento_Compromiso SET Compromiso = 'Hoy' WHERE id=$id");

        }
        elseif($FechaCompromiso > $Hoy && $FechaCompromiso <= $Futuro){
            echo "Vence Mañana"."-".$Rut."-".$FechaCompromiso;
            mysql_query("UPDATE Agendamiento_Compromiso SET Compromiso = 'Mañana' WHERE id=$id");

        }
        elseif($FechaCompromiso >= $Manana){
            echo "Futuro"."-".$Rut."-".$FechaCompromiso;
            mysql_query("UPDATE Agendamiento_Compromiso SET Compromiso = 'Futuro' WHERE id=$id");

        }          
    }
}
?>


