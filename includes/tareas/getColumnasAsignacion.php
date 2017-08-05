<?php
    include("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("tareas");
    QueryPHP_IncludeClasses("db");
    $tareas = new Tareas();
    $Columnas = $tareas->getColumnasAsignacion();
    echo json_encode($Columnas);
?>