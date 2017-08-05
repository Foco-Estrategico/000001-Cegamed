<?php 
include("../../class/crm/crm.php");
include_once("../../includes/functions/Functions.php");
QueryPHP_IncludeClasses("db");
$crm = new crm();
echo json_encode($crm->mostrarScript($_POST['idCedente']));
//echo $crm->mostrarScript($_POST['idCedente']);
?>