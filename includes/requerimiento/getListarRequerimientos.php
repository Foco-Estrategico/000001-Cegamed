<?php
    include_once("../../includes/functions/Functions.php");
    include_once("../../class/requerimiento/requerimiento.php");
    QueryPHP_IncludeClasses("db");
    $requerimiento = new Requerimiento(); 
    echo json_encode($requerimiento->getRequerimientos());
?>