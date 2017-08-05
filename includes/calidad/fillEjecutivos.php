<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("personal");
    QueryPHP_IncludeClasses("db");
    $PersonalClass = new Personal();
    $idEmpresa = isset($_POST['idEmpresa']) ? $_POST['idEmpresa'] : "";
    $Mandantes = $PersonalClass->getPersonalEjecutivos($idEmpresa);
    $ToReturn = "<option value=''>Todos</option>";
    foreach($Mandantes as $Mandante){
        $ToReturn .= "<option value='".$Mandante['Id_Personal']."'>".$Mandante['Nombre']."</option>";
    }
    echo $ToReturn;
?>