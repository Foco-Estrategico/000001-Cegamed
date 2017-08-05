<?php
	include("../../class/db/DB.php");
	$operation = new Db();
	$result = $operation -> query('DELETE FROM fonos_incorrectos WHERE IdFonosIncorrectos = '.$_POST['id']);
 	echo $result;
 ?>