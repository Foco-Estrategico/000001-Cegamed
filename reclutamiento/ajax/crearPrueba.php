<?php
    include_once("../../includes/functions/Functions.php");
    require("../../includes/email/PHPMailer-master/class.phpmailer.php"); 
	require("../../includes/email/PHPMailer-master/class.smtp.php"); 
    QueryPHP_IncludeClasses("db");
    QueryPHP_IncludeClasses("reclutamiento");
    QueryPHP_IncludeClasses("email");
    $ReclutamientoClass = new Reclutamiento();
    $idUsuario = $_POST['idUsuario'];
    $idPerfil = $_POST['idPerfil'];
    $idTest = $_POST['idTest'];

    $ToReturn = $ReclutamientoClass->crearPrueba($idUsuario,$idPerfil,$idTest);
    echo json_encode($ToReturn);
?>