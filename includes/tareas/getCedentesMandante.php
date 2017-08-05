<?php
    include("../../includes/functions/Functions.php");
    
    QueryPHP_IncludeClasses("global");
    QueryPHP_IncludeClasses("db");
    $cedente = new Cedente();

    $Cedentes = $cedente->getCedentesMandante($_SESSION["mandante"]);
    $ToReturn = "";
    foreach($Cedentes as $Cedente){
        $ToReturn .= "<option value='".$Cedente["idCedente"]."'>".utf8_encode($Cedente["NombreCedente"])."</option>";
    }
    echo $ToReturn;
?>