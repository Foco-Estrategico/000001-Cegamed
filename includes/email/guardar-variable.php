<?php include_once("../functions/Functions.php");
    QueryPHP_IncludeClasses("db");
    $db = new Db();

	$nombre = $_POST["nombre"];
	$tabla = $_POST["tabla"];
	$tipo = $_POST["tipo"];
	$campos = $_POST["campos"];
	$operacion = $tipo == 'operacion' ? $_POST["operacion"] : '';

	$consultar = "SELECT id FROM Variables WHERE variable = '".$nombre."' and id_cedente='".$_SESSION['cedente']."'";

	$existe = $db->select($consultar);

	if(count($existe) > 0){
		echo '3';
	} else {

		$query_guardar = "INSERT INTO Variables (variable, tabla, campo, operacion,id_cedente) VALUES('".$nombre."', '".$tabla."', '".$campos."', '".$operacion."','".$_SESSION['cedente']."')";

		$guardar = $db->query($query_guardar);

		if($guardar == false){
			echo '2';
		} else {
			echo '1';
		}
	}