<?php
    include("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("tareas");
    QueryPHP_IncludeClasses("db");
    $tareas = new Tareas();
    $Discador = $_POST['Discador'];
    $ToReturn = $tareas->EliminarColaDiscador($Discador);
    echo json_encode($ToReturn);
?>