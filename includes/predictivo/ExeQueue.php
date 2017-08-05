<?php
    include_once("../../includes/functions/Functions.php");
    include_once("../../class/discador/discador.php");
    include ("../../discador/AGI/phpagi-asmanager.php");
    QueryPHP_IncludeClasses("db");

    $IdArg = $argv[1];
    $Discador = new Discador($IdArg);
    $Array = $Discador->Start();
?>   