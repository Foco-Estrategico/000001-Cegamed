<?php 
include("../../class/crm/crm.php");
include("../../class/db/DB.php");
$crm = new crm();
$crm->actualizarCorreo($_POST['id_mail'],$_POST['mail'],$_POST['nombre'],$_POST['cargo'],$_POST['obs']);
?>    