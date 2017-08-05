<?php 
include("../../class/crm/crm.php");
include("../../class/db/DB.php");
$crm = new crm();
$crm->prevRut($_POST['rut'],$_POST['prefijo']);
?>    