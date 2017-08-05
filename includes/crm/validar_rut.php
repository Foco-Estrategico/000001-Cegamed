<?php 
include("../../class/crm/crm.php");
include("../../class/db/DB.php");
$crm = new crm();
$crm->validarRut($_POST['rut'],$_POST['cedente']);
?>    