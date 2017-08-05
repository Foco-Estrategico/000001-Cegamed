<?php
	require_once('../../db/db.php');
	$ToReturn = mysql_query("alter table ".$_POST['tabla']." add ".$_POST['nombre']." ".$_POST['tipo']);
	if ($ToReturn) {
		$table = $_POST['tabla'];
		$Separador = strrpos($table, "_");
		$table =  substr($table,0,$Separador);
		switch($table){
			case 'Deuda':
				$ToReturn = mysql_query("alter table Deuda_Historico add ".$_POST['nombre']." ".$_POST['tipo']);
				$ToReturn = mysql_query("alter table ".$table." add ".$_POST['nombre']." ".$_POST['tipo']);
			break;
			case 'Persona':
				$ToReturn = mysql_query("alter table ".$table." add ".$_POST['nombre']." ".$_POST['tipo']);
				$ToReturn = mysql_query("alter table ".$table."_Periodo add ".$_POST['nombre']." ".$_POST['tipo']);
			break;
			case 'pagos_deudas':
				$ToReturn = mysql_query("alter table ".$table." add ".$_POST['nombre']." ".$_POST['tipo']);
			break;
			default:
				$ToReturn = mysql_query("alter table ".$table." add ".$_POST['nombre']." ".$_POST['tipo']);
				$ToReturn = mysql_query("alter table ".$table."_cedente add ".$_POST['nombre']." ".$_POST['tipo']);
			break;
		}
	}
	if($ToReturn){
		$ToReturn = array();
		$ToReturn["result"] = true;
	}else{
		$ToReturn = array();
		$ToReturn["result"] = false;
	}
	echo json_encode($ToReturn);
 ?>