<?php 
    include_once("../functions/Functions.php");
    QueryPHP_IncludeClasses("db");
    QueryPHP_IncludeClasses("email");
    include('../../db/connect.php');
    $db = new Db();
    $opciones = new opciones();
    echo $opciones->variables($con);
?>