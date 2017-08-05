<?php
    include("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("tareas");
    QueryPHP_IncludeClasses("personal");
    QueryPHP_IncludeClasses("grupos");
    QueryPHP_IncludeClasses("db");
    $tareas = new Tareas();
    $Colas = $tareas->getColas();
    $ToReturn = "";
    foreach($Colas as $Cola){
        $Tabla = $Cola["tabla"];
        $ArrayAsignacion = explode("_",$Tabla);
        $TipoEntidad = $ArrayAsignacion[3];
        $idEntidad = $ArrayAsignacion[4];
        $Foco = $ArrayAsignacion[7];
        $Nombre = "";
        switch($TipoEntidad){
            case 'E':
            case 'S':
            break;
            case 'EE':
            break;
            case 'G':
                $GrupoClass = new Grupos();
                $Grupo = $GrupoClass->getGroup($idEntidad);
                $Nombre = utf8_encode($Grupo["Nombre"]);
            break;
        }
        $ToReturn .= "<option value='".$Tabla."'>".$Nombre."</option>";
    }
    echo $ToReturn;
?>