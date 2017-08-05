<?php
    include("../../includes/functions/Functions.php");
    require '../../class/estrategia/config_tablas.php';
    QueryPHP_IncludeClasses("tareas");
    QueryPHP_IncludeClasses("db");
    $tareas = new Tareas();
    $ID = $_POST['ID'];
    $ToReturn = $tareas->getColumnaData($ID);
    echo json_encode($ToReturn);
?>