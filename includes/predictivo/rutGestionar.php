<?php
    include_once("../../includes/functions/Functions.php");
    include_once("../../class/predictivo/predictivo.php");
    QueryPHP_IncludeClasses("db");
    $predictivo = new Predictivo();
    $valores = $predictivo->rutFonoGestionar();
    echo json_encode($valores);
?>   