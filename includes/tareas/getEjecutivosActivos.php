<?php
    include("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("tareas");
    QueryPHP_IncludeClasses("db");
    $tareas = new Tareas();
    $Ejecutivos = $tareas->getEjecutivosActivos();
    $ToReturn = "";
    foreach($Ejecutivos as $Ejecutivo){
        $ToReturn .= "<option value='".$Ejecutivo["idUsuario"]."'>".utf8_encode($Ejecutivo["Nombre"])."</option>";
    }
    echo $ToReturn;
?>