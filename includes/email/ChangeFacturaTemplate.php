<?php include_once("../functions/Functions.php");
    QueryPHP_IncludeClasses("db");
    $db = new Db();

	$idTemplate = $_POST["idTemplate"];

	$query_update = "UPDATE EMAIL_Template set factura = '0' where id_cedente='".$_SESSION["cedente"]."'";
    $update = $db->query($query_update);
    $query_update = "UPDATE EMAIL_Template set factura = '1' where id_cedente='".$_SESSION["cedente"]."' and Id='".$idTemplate."'";
	$update = $db->query($query_update);

	if($update == false){
		echo '2';
	} else {
		echo '1';
	}

?>