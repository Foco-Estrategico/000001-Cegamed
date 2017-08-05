<?php
	require_once('../../db/db.php');
	$sql=mysql_query("SHOW COLUMNS FROM ".$_POST['tabla']);
	$option = '<option value="">Seleccione...</option>';
	while($row=mysql_fetch_array($sql)){
		$option.= ' <option>'.$row[0].'</option>';
	}
	echo $option;
	/*require_once('../../db/db.php');
	require_once('../../class/db/DB.php');
	if(!isset($_SESSION)){
		session_start();
	}
	$db = new DB();
	//$sql=mysql_query("SHOW COLUMNS FROM ".$_POST['tabla']);
	//$sql=mysql_query("SHOW COLUMNS FROM ".$_POST['tabla']);
	$Tabla = str_replace("_tmp","",$_POST['tabla']);
	$sql = "select SIS_Columnas_Estrategias.columna from SIS_Tablas inner join SIS_Columnas_Estrategias on SIS_Columnas_Estrategias.id_tabla = SIS_Tablas.id where SIS_Tablas.nombre = '".$Tabla."' and FIND_IN_SET('".$_SESSION['cedente']."',SIS_Columnas_Estrategias.Id_Cedente) order by SIS_Columnas_Estrategias.columna";
	$option = '<option value="">Seleccione...</option>';
	$Columnas = $db->select($sql);
	foreach($Columnas as $Columna){
		$option.= '<option>'.$Columna["columna"].'</option>';
	}
	echo $option;*/
 ?>