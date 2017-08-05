<?php
    include_once("../../includes/functions/Functions.php");
    include_once("../../db/db.php");
    QueryPHP_IncludeClasses("calidad");
    QueryPHP_IncludeClasses("personal");
    QueryPHP_IncludeClasses("db");
    $CalidadClass = new Calidad();
    $CalidadClass->User = $_POST['Ejecutivo'];
    /*$CalidadClass->startDate = $_POST['startDate'];
    $CalidadClass->endDate = $_POST['endDate'];*/
    $Month = $_POST['Month'];
    $CalidadClass->Id_Cedente = $_POST['IdCedente'];
    echo json_encode($CalidadClass->getCierres($Month));
?>