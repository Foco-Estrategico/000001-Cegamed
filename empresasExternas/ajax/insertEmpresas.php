<?php
	include("../../class/db/DB.php");
	$operation = new Db();
	$result = $operation -> query('INSERT INTO empresa_externa (Nombre, Telefono, Correo, Direccion, IdCedente) VALUES ("'.$_POST['nombre'].'", "'.$_POST['telefono'].'", "'.$_POST['correo'].'", "'.$_POST['direccion'].'", "'.$_SESSION['cedente'].'")');
 	echo $result;
 ?>