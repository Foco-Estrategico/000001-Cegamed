<?php
    include("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("tareas");
    QueryPHP_IncludeClasses("db");
    $tareas = new Tareas();
    $Discador = $_POST['Discador'];
    $tareas->ReiniciarColaDiscado($Discador);
?>