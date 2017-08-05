<?php
    include("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("tareas");
    QueryPHP_IncludeClasses("db");
    $tareas = new Tareas();
    $idCola = $_POST['cola'];
    $Cola = $tareas->getCola($idCola);
    $ToReturn = array();
    $ToReturn["Cautiva"] = $Cola["cautiva"];
    $ToReturn["idUserCautiva"] = $Cola["idUserCautiva"];
    echo json_encode($ToReturn);
?>