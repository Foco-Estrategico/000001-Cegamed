<?php
include_once("../../includes/functions/Functions.php");
include_once("../../class/trabajador/trabajador.php");
QueryPHP_IncludeClasses("db");
$trabajador = new Trabajador();
$antiguedades = $trabajador->getListarAntiguedad();
$ToReturn = "<option value='0'>Seleccione</option>";
foreach($antiguedades as $antiguedad){
    if($antiguedad["antiguedad"] != ""){
        $ToReturn .= "<option value='".$antiguedad["id_antiguedad"]."'>".$antiguedad["antiguedad"]."</option>";
    }
}
echo $ToReturn;
?>