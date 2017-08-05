<?php
include_once("../../includes/functions/Functions.php");
include_once("../../class/trabajador/trabajador.php");
QueryPHP_IncludeClasses("db");
$trabajador = new Trabajador();
$estados = $trabajador->getListarEstadoCivil();
$ToReturn = "<option value='0'>Seleccione</option>";
foreach($estados as $estado){
    if($estado["estado"] != ""){
        $ToReturn .= "<option value='".$estado["id_estado"]."'>".$estado["estado"]."</option>";
    }
}
echo $ToReturn;
?>