<?php
    include_once("../../includes/functions/Functions.php");
    include_once("../../class/global/cedente.php");
    QueryPHP_IncludeClasses("db");
    $Cedente = new Cedente(); 
    echo json_encode($Cedente->mostrarMandante($_POST['idMandante']));
?>