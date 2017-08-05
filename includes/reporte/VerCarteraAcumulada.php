<?php
include("../../class/reporte/reporteriaClass.php");

$Reporteria = new Reporteria();
$Reporteria->MostrarGestionesAcumuladas($_POST['FechaInicio'],$_POST['FechaTermino'],$_POST['Tipo']);

?>