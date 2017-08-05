<?php 
include("../../class/crm/crm.php");
include("../../class/db/DB.php");
$crm = new crm();
$crm->nivel4($_POST['id_tipo'],$_POST['cortar_valor'],$_POST['rut']);
?>    