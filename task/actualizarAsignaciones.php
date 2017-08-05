<?php
    set_time_limit(3600);
    include("../includes/functions/Functions.php");
    require '../plugins/PHPExcel-1.8/Classes/PHPExcel.php';

    //$Cola = isset($_POST['Cola']) ? $_POST['Cola'] : "QR_";
    $Cola = isset($_REQUEST['Cola']) ? $_REQUEST['Cola'] : "QR_";

    Main_IncludeClasses("tareas");
    Main_IncludeClasses("db");
    Main_IncludeClasses("ftp");
    $tareas = new Tareas();
    $tareas->updateAsignaciones($Cola);

?>