<?php
    include("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("tareas");
    QueryPHP_IncludeClasses("db");
    $tareas = new Tareas();

    $idCola = $_POST["cola"];
    $Ejecutivo = $_POST["Ejecutivo"];
    $Cautiva = $_POST["Cautiva"];

    $ToReturn = $tareas->updateColaCautiva($idCola,$Ejecutivo,$Cautiva);

    echo json_encode($ToReturn);
?>