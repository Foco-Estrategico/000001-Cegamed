<?php 
include("../../class/crm/crm.php");
include("../../class/db/DB.php");
$crm = new crm();
if(!isset($_POST['pantalla'])){
    $crm->mostrarCorreoRut($_POST['rut']);
}else{
    $crm->mostrarCorreoRut($_POST['rut'],$_POST['pantalla']);
}
?>    