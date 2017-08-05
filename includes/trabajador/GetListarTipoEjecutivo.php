<?php
include_once("../../includes/functions/Functions.php");
include_once("../../class/trabajador/trabajador.php");
QueryPHP_IncludeClasses("db");
$trabajador = new Trabajador();
$ejecutivos = $trabajador->getListarTipoEjecutivo();
$ToReturn = "<option value='0'>Seleccione</option>";
foreach($ejecutivos as $ejecutivo){
    if($ejecutivo["tipoEjecutivo"] != ""){
        $ToReturn .= "<option value='".$ejecutivo["id_tipoEjecutivo"]."'>".$ejecutivo["tipoEjecutivo"]."</option>";
    }
}
echo $ToReturn;
?>