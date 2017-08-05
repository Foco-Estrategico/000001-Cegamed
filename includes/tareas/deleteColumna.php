<?php
    include("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("tareas");
    QueryPHP_IncludeClasses("db");
    $tareas = new Tareas();
    $ID = $_POST['ID'];
    $ToReturn = $tareas->deleteColumna($ID);
    echo json_encode($ToReturn);
?>