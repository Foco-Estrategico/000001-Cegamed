<?php
    include("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("tareas");
    QueryPHP_IncludeClasses("db");
    $tareas = new Tareas();
    $Cola = $_POST['Cola'];
    $Value = $_POST['Value'];
    $Colas = $tareas->CambiarStatusColaDiscado($Cola,$Value);
?>