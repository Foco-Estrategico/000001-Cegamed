<?php 
include("../../class/crm/crm.php");
include("../../class/db/DB.php");
$crm = new crm();
$crm->insertarFonos($_POST['rut'],$_POST['fono_discado_nuevo']);
?>    