<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("carga");
    QueryPHP_IncludeClasses("db");
    $CargaClass = new Carga();

    $TipoArchivo = $_POST['TipoArchivo'];
    $Separador = $_POST['Separador'];
    $Cabecero = $_POST['Cabecero'];

    $ToReturn = array();
    $ToReturn = $CargaClass->addTemplateCarga($TipoArchivo,$Separador,$Cabecero);

    echo json_encode($ToReturn);

?>