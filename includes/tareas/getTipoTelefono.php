<?php
    include("../../includes/functions/Functions.php");
    include("../../class/estrategia/estrategias.php");

    QueryPHP_IncludeClasses("db");
    $EstrategiaClass = new Estrategia();

    $TipoTelefonos = $EstrategiaClass->getTipoTelefono();
    $ToReturn = "";
    foreach($TipoTelefonos as $TipoTelefono){
        $ToReturn .= "<option value='".$TipoTelefono["color"]."'>".$TipoTelefono["tipo_var"]." - ".$TipoTelefono["color_nombre"]."</option>";
    }
    echo $ToReturn;
?>