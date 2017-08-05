<?php
	include("../../class/db/DB.php");
	$operation = new Db();
	$rows = $operation -> select("SELECT Personal.Id_Personal, Personal.Nombre FROM Personal INNER JOIN Usuarios ON Personal.id_usuario = Usuarios.id INNER JOIN Roles ON Usuarios.nivel = Roles.id where Usuarios.nivel = 2 and Activo = 1");
	$lista = "<option value='' >Seleccione...</option>";
	if (count($rows) > 0) {
		$lista .= '<optgroup label="Supervisor">';
		for ($i=0; $i < count($rows) ; $i++) {
			$lista.= '<option value ="'.$rows[$i]['Id_Personal'].'">'.$rows[$i]['Nombre'].'</option>';
		}
		$lista .= '</optgroup>';
	}

	$rows = $operation -> select("SELECT Personal.Id_Personal, Personal.Nombre FROM Personal INNER JOIN Usuarios ON Personal.id_usuario = Usuarios.id INNER JOIN Roles ON Usuarios.nivel = Roles.id where Usuarios.nivel = 6  and Activo = 1");
	if (count($rows) > 0) {
		$lista .= '<optgroup label="Calidad">';
		for ($i=0; $i < count($rows) ; $i++) {
			$lista.= '<option value ="'.$rows[$i]['Id_Personal'].'">'.$rows[$i]['Nombre'].'</option>';
		}
		$lista .= '</optgroup>';
	}

	$rows = $operation -> select("SELECT Personal.Id_Personal, Personal.Nombre FROM Personal INNER JOIN Usuarios ON Personal.id_usuario = Usuarios.id INNER JOIN Roles ON Usuarios.nivel = Roles.id where Usuarios.nivel = 5  and Activo = 1");
	if (count($rows) > 0) {
		$lista .= '<optgroup label="RRHH">';
		for ($i=0; $i < count($rows) ; $i++) {
			$lista.= '<option value ="'.$rows[$i]['Id_Personal'].'">'.$rows[$i]['Nombre'].'</option>';
		}
		$lista .= '</optgroup>';
	}

	$rows = $operation -> select("SELECT Personal.Id_Personal, Personal.Nombre FROM Personal INNER JOIN Usuarios ON Personal.id_usuario = Usuarios.id INNER JOIN Roles ON Usuarios.nivel = Roles.id where Usuarios.nivel = 4  and Activo = 1");
	if (count($rows) > 0) {
		$lista .= '<optgroup label="Ejecutivo">';
		for ($i=0; $i < count($rows) ; $i++) {
			$lista.= '<option value ="'.$rows[$i]['Id_Personal'].'">'.$rows[$i]['Nombre'].'</option>';
		}
		$lista .= '</optgroup>';
	}

	echo $lista;

 ?>