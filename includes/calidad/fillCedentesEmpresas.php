<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("global");
    QueryPHP_IncludeClasses("db");
    $CedenteClass = new Cedente();
    $Mandante = $_POST['Mandante'];
    $Cedentes = $CedenteClass->getCedentesMandante($Mandante);
    $ToReturn = "<option value=''>Todos</option>";
    foreach($Cedentes as $Cedente){
        $ToReturn .= "<option value='".$Cedente['idCedente']."'>".utf8_encode($Cedente['NombreCedente'])."</option>";
    }
    echo $ToReturn;
?>