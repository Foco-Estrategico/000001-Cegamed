<?php 
include("../../class/crm/crm.php");
include("../../class/db/DB.php");
$crm = new crm();
$crm->cantRegistros($_POST['rut'],$_POST['prefijo']);
?>    