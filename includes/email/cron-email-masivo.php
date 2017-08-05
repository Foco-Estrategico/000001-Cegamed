<?php 
require("../../includes/functions/Functions.php");
require("../../includes/email/PHPMailer-master/class.phpmailer.php"); 
require("../../includes/email/PHPMailer-master/class.smtp.php"); 
include("../../class/email/email.php");
include("../../class/email/opciones.php");
include("../../class/db/DB.php");
include("../../class/db/log.php");
$db = new Db();
date_default_timezone_set('America/Santiago');
// horario permitido para envio
$horaInicio = date ( 'Y-m-d').' 09:00:00';
$horaFin = date ( 'Y-m-d').' 23:00:00';

// cuando no trae datos debe estar parado
// verifico si estamos en la hora permitida
$sqlSelec = "SELECT * FROM cron_email WHERE NOW() Between '".$horaInicio."' and '".$horaFin."'";
$cro = $db -> select($sqlSelec);
if (count($cro) == 0){
	// el cron no deberia ejecutarse esta fuera de horario
	$SqlUpdate = "UPDATE cron_email set estatus = '0' WHERE id = 1";
    $db -> query($SqlUpdate);
	// los envios pendientes quedan parados hasta que el usuario los ejecute dentro de la hora permitida
	$SqlUpdateEnvios = "UPDATE envio_email set continuar = '1' WHERE status = 0";
    $db -> query($SqlUpdateEnvios);
}else{
	// pasa por aca porque estamos en el horario permitido 
	// el cron debe ejecutarse solo si no tenemos una alerta (continuar = 1 que es parado)
	$sqlAlerta = "SELECT * FROM envio_email WHERE continuar = '1'";
	$alerta = $db -> select($sqlAlerta);
	if (count($alerta) == 0){
		// el envio se ejecuta no tenemos alertas y estamos en horario permitido
		$SqlUpdate = "UPDATE cron_email set estatus = '1' WHERE id = 1";
    	$db -> query($SqlUpdate);
		// el proceso continua 
		// --------- INICIA ENVIO ----------
				
$hora_actual = date('Y-m-d H:i:s');
/* $hora_actual = strtotime ( '-30 minute' , strtotime ( $fecha ) ) ;
$hora_actual = date ( 'Y-m-d H:i:s' , $hora_actual ); */

$consulta_pendientes = "SELECT * FROM envio_email WHERE status = 0 ORDER BY id ASC LIMIT 1";
$pendientes = $db->select($consulta_pendientes);


if(count($pendientes) > 0){
	

	$row_pendientes = $pendientes[0];

	$id = $row_pendientes["id"];

	$cantidad = $row_pendientes["cantidad"];

	$asunto = $row_pendientes["asunto"];

	$html = $row_pendientes["html"];

	$offset = $row_pendientes["offset"];

	$estrategia = $row_pendientes["estrategia"];

	$hora_ultima_actualizacion = $row_pendientes["actualizacion"];

	$adjuntar = $row_pendientes["adjuntar"];

	$cedente = $row_pendientes["Id_Cedente"];

	$tablaEmail = $row_pendientes["tabla_email"];

	$fechaEnvio = strtotime("+30 minute",strtotime($hora_ultima_actualizacion));

	if(strtotime($fechaEnvio) <= strtotime($hora_actual)){

		//Consultar Variables creadas
		$query_ve = "SELECT variable FROM Variables where id_cedente='".$cedente."'";

		$variables_existentes = $db->select($query_ve);
		$uso_variables = array();

		if(count($variables_existentes) > 0){
			foreach($variables_existentes as $var_e){
				$var = $var_e['variable'];
				$uso = strpos($html, '['.$var.']');
				if($uso !== false){
					$uso_variables[] = $var;
				}
			}
		}

		if ($tablaEmail == 1){ // Tabla Cedente
			$select_correos = "SELECT m.correo_electronico, m.Rut FROM Mail_cedente m , ".$estrategia." q WHERE m.Rut = q.Rut AND Id_Cedente = ".$cedente." LIMIT 500 OFFSET ".$offset;			
		}else{
			if ($tablaEmail == 0){
				$select_correos = "SELECT m.correo_electronico, m.Rut FROM Mail m , ".$estrategia." q WHERE m.Rut = q.Rut LIMIT 500 OFFSET ".$offset;
			}
		}

		//$select_correos = "SELECT m.correo_electronico, m.Rut as rut FROM Mail m , ".$estrategia." q WHERE m.Rut = q.Rut LIMIT 500 OFFSET ".$offset;

		$correos = $db->select($select_correos);
		$n = 0;
		$info = array();
		$adjuntos = array();
		$envio = new Email();

		if(count($correos) > 0){
			foreach($correos as $correo){
				$email = $correo['correo_electronico'];
				$correos_array[] = $email;
				$rut = $correo['Rut'];
				$n++;
				//Obtener valor de cada Variable para cada rut
				foreach ($uso_variables as $var){
					$info[$email][$var] = $envio->get_var_value($rut,$var,$cedente);
				}
				$info[$email]["Rut"] = $rut;
				//Adjuntos
				if($adjuntar == 1){
					$consulta_adjuntos = "SELECT Numero_Factura FROM Deuda WHERE Rut='".$rut."' AND Id_Cedente = '".$cedente."'";

					$con_adj = $db->select($consulta_adjuntos); 

					if(count($con_adj) > 0) {

						$facturas = array();
						foreach($con_adj as $deuda){
							$facturas[] = $deuda['Numero_Factura'];
						}

						$adjuntos[$email] = $facturas;
					}

				} else {
					$adjuntos = false;
				}
			}

			$info['adjuntos'] = $adjuntos;
			$info['variables'] = $uso_variables;

			$envio_result = $envio->SendMail($html,$asunto,$correos_array, $info,$cedente,$rut);
			//Comentada debido a que no qeueremos spamear con correo basura a la gente
			//$envio_result = true; //Variable en true provicional.

			if($envio_result){

				$new_offset = $offset + $n;

				$status = ($new_offset >= $cantidad) ? '1' : '0';

				$date = date('Y-m-d H:i:s');

				$update_actual = "UPDATE envio_email SET offset='".$new_offset."', status='".$status."', actualizacion='".$date."' WHERE id='".$id."'";

				$actual = $db->query($update_actual);
				
				if($status == 1){
					$consulta_siguiente = "SELECT * FROM envio_email WHERE status = 0 and Id_Cedente='".$cedente."' ORDER BY id ASC LIMIT 1";
					$siguiente = $db->select($consulta_siguiente);

					if(count($siguiente) > 0){

						$row_siguiente = $siguiente[0];

						$id = $row_siguiente["id"];

						$update_siguiente = "UPDATE envio_email SET actualizacion='".$date."' WHERE id='".$id."'";
						$act_siguiente = $db->query($update_siguiente);
						
					}

				}

				echo '3';
			}
		}
	} else {
		echo '2';
	}
} else {
	echo '1';
} 
		// --------- FIN ENVIO ----------
	}else{
		// estamos en horario permitido pero tenemos alertas asi que el cron no se ejecuta 
		$SqlUpdate = "UPDATE cron_email set estatus = '0' WHERE id = 1";
    	$db -> query($SqlUpdate);
	}	
}








