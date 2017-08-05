<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("calidad");
    QueryPHP_IncludeClasses("db");
    $CalidadClass = new Calidad();
    $CalidadClass->Id_Evaluacion = $_POST['Id_Evaluacion'];
    $Afirmaciones = $_POST['Afirmaciones'];
    $CalidadClass->deleteEvaluationDetails();
    $CalidadClass->addEvaluationDetails($Afirmaciones);
?>