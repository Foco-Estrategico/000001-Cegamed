<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("global");
    QueryPHP_IncludeClasses("db");
    $CedenteClass = new Cedente();
    $Mandantes = $CedenteClass->getMandantes();
    foreach($Mandantes as $Mandante){
        $ToReturn .= "<option value='".$Mandante['id']."'>".$Mandante['nombre']."</option>";
    }
    echo $ToReturn;
?>