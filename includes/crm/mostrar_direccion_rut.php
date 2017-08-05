<?php 
include("../../class/crm/crm.php");
include("../../class/db/DB.php");
$crm = new crm();
if(!isset($_POST['pantalla'])){
    $crm->mostrarDireccionRut($_POST['rut']);
}else{
    $crm->mostrarDireccionRut($_POST['rut'],$_POST['pantalla']);
}
?>    