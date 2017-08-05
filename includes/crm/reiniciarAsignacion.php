<?php 
include("../../class/crm/crm.php");
include("../../class/db/DB.php");
$crm = new crm();
$crm->ordenarAsignacionContacto($_POST['Prefijo'],$_POST['tipo']);
?>    
