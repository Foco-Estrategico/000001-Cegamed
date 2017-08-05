<?php
	include("../../class/db/DB.php");
	$operation = new Db();
	$rows = $operation -> select("SELECT Rut, Fono FROM fonos_incorrectos WHERE IdFonosIncorrectos = ".$_POST['id']);
	echo  json_encode($rows);
 ?>