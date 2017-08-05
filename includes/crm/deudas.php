<?php 
include("../../class/crm/crm.php");
include_once("../../includes/functions/Functions.php");
QueryPHP_IncludeClasses("db");
$crm = new crm();
if(!isset($_POST['pantalla'])){
    $crm->mostrarDeudas($_POST['rut'],$_POST['cedente']);
}else{
    $crm->mostrarDeudas($_POST['rut'],$_POST['cedente'],$_POST['pantalla']);
}
?>    