<?php
include_once("../../includes/functions/Functions.php");
include_once("../../class/trabajador/trabajador.php");
QueryPHP_IncludeClasses("db");
$trabajador = new Trabajador();
$sexos = $trabajador->getListarSexo();
$ToReturn = "<option value='0'>Seleccione</option>";
foreach($sexos as $sexo){
    if($sexo["sexo"] != ""){
        $ToReturn .= "<option value='".$sexo["id_sexo"]."'>".$sexo["sexo"]."</option>";
    }
}
echo $ToReturn;
?>