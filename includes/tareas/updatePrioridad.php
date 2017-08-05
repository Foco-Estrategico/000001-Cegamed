<?php
    include("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("tareas");
    QueryPHP_IncludeClasses("db");
    $tareas = new Tareas();
    $Value = $_POST['Value'];
    $ID = $_POST['ID'];
    $ToReturn = $tareas->updatePrioridad($Value,$ID);
    echo json_encode($ToReturn);
?>