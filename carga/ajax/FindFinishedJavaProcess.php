<?php
    include("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("carga");
    QueryPHP_IncludeClasses("db");
    $CargaClass = new Carga();
    $tipoUsuario = $_SESSION['MM_UserGroup'];
    switch($tipoUsuario){
        case '2':
            $Process = $CargaClass->FindFinishedJavaProcess();
            if(count($Process) > 0){
                echo json_encode($Process);
            }
        break;
    }
?>