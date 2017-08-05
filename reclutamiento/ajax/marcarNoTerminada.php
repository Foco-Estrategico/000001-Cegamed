<?php
    include_once("../../includes/functions/Functions.php");
    require("../../includes/email/PHPMailer-master/class.phpmailer.php"); 
	require("../../includes/email/PHPMailer-master/class.smtp.php"); 
    QueryPHP_IncludeClasses("db");
    //QueryPHP_IncludeClasses("email");
    QueryPHP_IncludeClasses("reclutamiento");
    $ReclutamientoClass = new Reclutamiento();
    $Prueba = $ReclutamientoClass->getPruebaActiva();
    $ToReturn = $ReclutamientoClass->marcarNoTerminada($Prueba);
    echo json_encode($ToReturn);
?>