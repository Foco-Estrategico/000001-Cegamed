<?php
include("../../class/reporte/reporteriaClass.php");

$Reporteria = new Reporteria();
$Reporteria->TipoBusqueda($_POST['Tipo']);

?>