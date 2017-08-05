<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("carga");
    QueryPHP_IncludeClasses("db");
    $CargaClass = new Carga();

    $Column = $_POST["Column"];
    $Tabla = $_POST["Tabla"];
    $Campo = $_POST["Campo"];
    $PatronFecha = $_POST["PatronFecha"];
    $ColumnaExcel = $_POST["ColumnaExcel"];
    $PosicionInicio = $_POST["PosicionInicio"];
    $CantCaracteres = $_POST["CantCaracteres"];
    $Funcion = $_POST["Funcion"];
    $Parametro = $_POST["Parametro"];

    $ToReturn = array();
    $ToReturn = $CargaClass->updateColumnCarga($Column,$Tabla,$Campo,$PatronFecha,$ColumnaExcel,$PosicionInicio,$CantCaracteres,$Funcion,$Parametro);

    echo json_encode($ToReturn);

?>