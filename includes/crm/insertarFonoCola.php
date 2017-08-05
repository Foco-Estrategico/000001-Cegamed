<?php 
include("../../class/crm/crm.php");
$crm = new crm();
$crm->insertarFonoCola($_POST['idCola'],$_POST['fono'],$_POST['rut']);
?>    
