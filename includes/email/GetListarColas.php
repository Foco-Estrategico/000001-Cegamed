<?php
include_once("../../includes/functions/Functions.php");
include_once("../../class/email/email.php");
QueryPHP_IncludeClasses("db");
$email = new Email();
$colas = $email->getListarColas();
$ToReturn = "<option value='0'>Seleccione</option>";
foreach($colas as $cola){
    if($cola["estrategia"] != ""){
        $ToReturn .= "<option value='".$cola["id"]."'>".$cola["estrategia"]."</option>";
    }
}
echo $ToReturn;
?>