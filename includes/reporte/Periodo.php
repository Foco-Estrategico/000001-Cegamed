<?php
include("../../class/reporte/reporteriaClass.php");

$Reporteria = new Reporteria();
$Reporteria->Periodo($_POST['Cartera'],$_POST['Mandante']);

?>