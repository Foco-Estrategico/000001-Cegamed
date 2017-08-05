<?php
    include_once("../../includes/functions/Functions.php");
    include_once("../../class/discador/CallDaemon.php");
    include ("../../discador/AGI/phpagi-asmanager.php");
    QueryPHP_IncludeClasses("db");

    $CallDaemon = new CallDaemon();
    $Array = $CallDaemon->Start();
?>   