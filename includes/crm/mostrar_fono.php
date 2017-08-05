<?php 
include("../../class/crm/crm.php");
include("../../class/db/DB.php");
$crm = new crm();
$crm->mostrarFono($_POST['rut'],$_POST['fono']);
?>    