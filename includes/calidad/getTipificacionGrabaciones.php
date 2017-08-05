<?php
    include_once("../../includes/functions/Functions.php");
    include_once("../../db/db.php");
    QueryPHP_IncludeClasses("calidad");
    QueryPHP_IncludeClasses("db");
    $CalidadClass = new Calidad();
    $CalidadClass->User = $_POST['Ejecutivo'];
    $CalidadClass->startDate = $_POST['startDate'];
    $CalidadClass->endDate = $_POST['endDate'];
    $Tipificaciones = $CalidadClass->getTipificacionGrabaciones();
    $ToReturn = "<option value='' selected='selected'>Todas</option>";
    foreach($Tipificaciones as $Tipificacion){
        $ToReturn .= "<option value='".utf8_encode($Tipificacion["id"])."'>".utf8_encode($Tipificacion["Contacto"])."</option>";
    }
    echo $ToReturn;
?>