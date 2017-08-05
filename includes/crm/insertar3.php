<?php 
include("../../class/crm/crm.php");
include("../../class/db/DB.php");
$crm = new crm();
$crm->insertar3($_POST['nivel1'],$_POST['fecha_gestion'],$_POST['hora_gestion'],$_POST['rut'],$_POST['fono_discado'],$_POST['tipo_gestion'],$_POST['cedente'],$_POST['duracion_llamada'],$_POST['usuario_foco'],$_POST['lista'],$_POST['tiempoLlamada'],$_POST['NombreGrabacion'],$_POST['asignacion'],$_POST['origen']);
?>    