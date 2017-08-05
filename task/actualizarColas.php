<?php
include("../db/db.php");
include("../class/tareas/tareas.php");

$Cola = isset($_POST['Cola']) ? $_POST['Cola'] : "";

$tareas = new Tareas();
$tareas->actualizarCola($Cola);

?>