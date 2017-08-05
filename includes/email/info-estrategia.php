<?php  include_once("../functions/Functions.php");
    QueryPHP_IncludeClasses("db");
    $db = new Db();

$table = $_POST["table"];
$tableEmail = $_POST["tableEmail"]; // 1 = cedente - 0 = historico
$cedente = $_SESSION["cedente"];

if($table !== ""){

	$query_count = "SELECT COUNT(Rut) AS nrut FROM ".$table;

	$row_count = $db->select($query_count);

	if(count($row_count) > 0){

		$n_rut = $row_count[0]["nrut"];

	} else {
		$n_rut = '0';
	}
	if ($tableEmail == 0){
		$query_emails = "SELECT COUNT(m.correo_electronico) AS emails FROM Mail m , ".$table." q WHERE m.Rut = q.Rut";
	}else{
		if ($tableEmail == 1){
			$query_emails = "SELECT COUNT(m.correo_electronico) AS emails, m.Rut FROM Mail_cedente m , ".$table." q WHERE m.Rut = q.Rut AND Id_Cedente = ".$cedente;
		}	
	}

	$row_emails = $db->select($query_emails);

	if(count($row_emails) > 0){

		$n_emails = $row_emails[0]["emails"];

	} else {
		$n_emails = '0';
	}

	$consulta_pendiente = "SELECT cantidad, offset, actualizacion FROM envio_email WHERE estrategia = '".$table."' AND status = 0";

	$row_pendiente = $db->select($consulta_pendiente);

	if(count($row_pendiente) > 0){
		$cantidad = $row_pendiente[0]["cantidad"];
		$offset = $row_pendiente[0]["offset"];
		$actualizacion = $row_pendiente[0]["actualizacion"];
		$espera = $cantidad - $offset;
		
		$hora_actualizacion = strtotime ( '+30 minute' , strtotime($actualizacion)) ;
		$hora_envio = date ('h:i' , $hora_actualizacion );
	} else {
		$espera = $offset = $hora_envio = ' -';
	}

	$values = array($n_rut,$n_emails,$offset,$espera,$hora_envio);

	echo json_encode($values);

} else {

	$n_rut = $n_emails = '0';
	$espera = $offset = $hora_envio = ' -';
	$values = array($n_rut,$n_emails,$offset,$espera,$hora_envio);

	echo json_encode($values);

}

?>