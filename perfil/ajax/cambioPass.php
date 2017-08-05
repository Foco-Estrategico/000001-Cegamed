<?php
	include("../../class/db/DB.php");
	session_start();
	$query = 'SELECT clave FROM Usuarios WHERE id ='.$_SESSION['id_usuario'];
	$operation = new Db();
	$data = $operation -> select($query);

	if (password_verify($_POST['pass'], $data[0]['clave'])) {
		$clave = password_hash($_POST['newPass'], PASSWORD_DEFAULT);
		$query = "UPDATE usuarios SET clave = '".$clave."' WHERE id=".$_SESSION['id_usuario'];
		$data = $operation -> query($query);
		echo $data;
	}else{
		echo false;
	}
 ?>