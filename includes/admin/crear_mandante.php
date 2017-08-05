<?php
    include_once("../../includes/functions/Functions.php");
    include_once("../../class/global/cedente.php");
    QueryPHP_IncludeClasses("db");
    $Cedente = new Cedente(); 
    echo $Cedente->creaMandante($_POST);
?>