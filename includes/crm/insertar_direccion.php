<?php 
include("../../class/crm/crm.php");
include("../../class/db/DB.php");
$crm = new crm();
$crm->insertarDireccion($_POST['rut'],$_POST['direccion_nuevo']);
?>    