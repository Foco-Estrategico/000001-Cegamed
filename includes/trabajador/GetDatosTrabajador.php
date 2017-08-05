<?php    
    include_once("../../includes/functions/Functions.php");
    include_once("../../class/trabajador/trabajador.php");
    QueryPHP_IncludeClasses("db");
    $Trabajador = new Trabajador();   
    $Trabajador->muestraDatosGeneralesTrabajador($_POST['idTrabajador']);
    echo json_encode($Trabajador->muestraDatosGeneralesTrabajador($_POST['idTrabajador']));    

?>