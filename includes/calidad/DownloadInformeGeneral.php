<?php
    include_once("../../includes/functions/Functions.php");
    include_once("../../plugins/PHPExcel-1.8/Classes/PHPExcel.php");
    QueryPHP_IncludeClasses("calidad");
    QueryPHP_IncludeClasses("db");
    $CalidadClass = new Calidad();
    $Month = $_POST['Month'];
    $ToReturn = $CalidadClass->DownloadInformeGeneral($Month);
    echo json_encode($ToReturn);
?>