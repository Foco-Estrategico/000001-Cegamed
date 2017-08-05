<?php
include_once("../../includes/functions/Functions.php");
include_once("../../class/trabajador/trabajador.php");
QueryPHP_IncludeClasses("db");
$trabajador = new Trabajador();
$motivos = $trabajador->getListarMotivoEgreso();
$ToReturn = "<option value='0'>Seleccione</option>";
foreach($motivos as $motivo){
    if($motivo["estatusEgreso"] != ""){
        $ToReturn .= "<option value='".$motivo["idEstatusEgreso"]."'>".$motivo["estatusEgreso"]."</option>";
    }
}
echo $ToReturn;
?>