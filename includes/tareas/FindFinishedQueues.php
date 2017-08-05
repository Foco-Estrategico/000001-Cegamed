<?php
    include("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("tareas");
    QueryPHP_IncludeClasses("grupos");
    QueryPHP_IncludeClasses("db");
    $tareas = new Tareas();
    $tipoUsuario = $_SESSION['MM_UserGroup'];
    switch($tipoUsuario){
        case '2':
            $Queues = $tareas->FindQueueFinished();
            if(count($Queues) > 0){
                echo json_encode($Queues);
            }
        break;
    }
?>