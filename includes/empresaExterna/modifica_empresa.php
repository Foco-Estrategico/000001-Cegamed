<?php
    include_once("../../includes/functions/Functions.php");
    include_once("../../class/empresaExterna/empresaExterna.php");
    QueryPHP_IncludeClasses("db");
    $empresa = new Empresa(); 
    $empresa->modificaEmpresaExterna($_POST['nombre'], $_POST['email'], $_POST['telefono'], $_POST['direccion'], $_POST['idEmpresa']);
?>