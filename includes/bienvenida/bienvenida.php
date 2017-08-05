<?php 
include_once("../../includes/functions/Functions.php");
QueryPHP_IncludeClasses("db");
include("../../class/bienvenida/bienvenida.php");

$Bienvenida = new Bienvenida();
$ToReturn = $Bienvenida->CalendarioAgenda();
echo json_encode($ToReturn);
?>    