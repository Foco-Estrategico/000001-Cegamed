<?php 
include("../../class/crm/crm.php");
include("../../class/db/DB.php");
$crm = new crm();
$crm->insertar1($_POST['nivel1'],$_POST['nivel2'],$_POST['nivel3'],$_POST['comentario'],$_POST['fecha_gestion'],$_POST['hora_gestion'],$_POST['rut'],$_POST['fono_discado'],$_POST['tipo_gestion'],$_POST['cedente'],$_POST['usuario_foco'],$_POST['lista'],$_POST['fecha_compromiso'],$_POST['monto_compromiso'],$_POST['tiempoLlamada'],$_POST['NombreGrabacion'],$_POST['asignacion'],$_POST['origen'],$_POST['facturas'],$_POST['fechaAgendamiento'],$_POST['horaAgendamiento'],$_POST['Hablar']);
?>    