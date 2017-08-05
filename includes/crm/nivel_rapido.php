<?php 
include("../../class/crm/crm.php");
include("../../class/db/DB.php");
$crm = new crm();
$crm->nivel_rapido($_POST['cedente']);
?>