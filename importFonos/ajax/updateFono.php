<?php
	include("../../class/db/DB.php");
	$operation = new Db();
	echo $result = $operation -> query('DELETE FROM fonos_incorrectos WHERE IdFonosIncorrectos = '.$_POST['id']);
	echo $result = $operation -> query("INSERT INTO fonos_correctos (Rut, Fono) VALUES ('".$_POST['Rut']."', '".$_POST['Fono']."')");
	echo $result;
 ?>