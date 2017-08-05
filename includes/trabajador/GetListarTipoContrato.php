<?php
include_once("../../includes/functions/Functions.php");
include_once("../../class/trabajador/trabajador.php");
QueryPHP_IncludeClasses("db");
$trabajador = new Trabajador();
$contratos = $trabajador->getListarTipoContrato();
$ToReturn = "<option value='0'>Seleccione</option>";
foreach($contratos as $contrato){
    if($contrato["contrato"] != ""){
        $ToReturn .= "<option value='".$contrato["id_contrato"]."'>".$contrato["contrato"]."</option>";
    }
}
echo $ToReturn;
?>