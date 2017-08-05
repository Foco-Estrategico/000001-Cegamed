<?php
include("../../class/reporte/reporteriaClass.php");

$Reporteria = new Reporteria();
$Reporteria->MostrarGestiones($_POST['Tipo'],$_POST['Periodo'],$_POST['Mandante'],$_POST['Cartera']);

?>