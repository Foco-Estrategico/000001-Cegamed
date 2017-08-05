<?php
    include_once("../../includes/functions/Functions.php");
    include_once("../../db/db.php");
    QueryPHP_IncludeClasses("calidad");
    QueryPHP_IncludeClasses("db");
    $CalidadClass = new Calidad();
    $CalidadClass->User = $_POST['Ejecutivo'];
    $CalidadClass->startDate = $_POST['startDate'];
    $CalidadClass->endDate = $_POST['endDate'];
    $CalidadClass->Cartera = $_POST['Cartera'];
    $CalidadClass->Tipificacion = $_POST['Tipificacion'];
    echo json_encode($CalidadClass->getRecordListAjax());
?>