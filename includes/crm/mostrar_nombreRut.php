<?php 
    include("../../class/crm/crm.php");
    include("../../class/db/DB.php");
    $crm = new crm();
    $crm->mostrarNombreRut($_POST['rut']);
?>    