<?php 

class Email
{
	/*/Versión 1
	public function SendMail($html,$subject,$email_list, $info){ 
		$ToReturn = FALSE;
		$mail = new PHPMailer();  
		$mail->IsSMTP();
		$mail->SMTPAuth = true;  
		//$mail->SMTPSecure = "ssl";   
		$mail->Host = "mail.cobranding.cl"; 
		//$mail->SMTPDebug = 1;  
		$mail->Port = 25;  
		$mail->Username = "redes@cobranding.cl";  
		$mail->Password = "M9a7r5s3A";  
		$mail->From = "redes@cobranding.cl";   
		$mail->FromName = "Foco";  
		$mail->Subject = $subject; 
		$mail->SMTPOptions = array(
			'ssl' => array(
				'verify_peer' => false,
				'verify_peer_name' => false,
				'allow_self_signed' => true
			)
		);

		$adjuntos = $info['adjuntos']; 
		$variables = $info['variables']; 

		if(is_array($email_list)){
			foreach($email_list as $email){ 				
				if( $email != ""){  					
					if($adjuntos){
						foreach ($adjuntos[$email] as $adjunto) {
							$archivo = '../../facturas/'.$adjunto.'.pdf';
							if(file_exists($archivo)){
								$mail->addAttachment($archivo);   
							}
						}
					}

					$find = array('[correo]');
					$replace = array($email);
					
					foreach ($variables as $var){
						$find[]='['.$var.']';
						$replace[] = $info[$email][$var];
					}
					$content = str_replace($find, $replace, $html);

					$mail->MsgHTML($content);   

			 		$mail->AddAddress('andres.estereomkt@gmail.com'); 
					//$mail->AddAddress($email); 
			   		$mail->send();
			   		$mail->ClearAllRecipients();  
				}
			}
			$ToReturn = TRUE;
		} else { 
			$mail->MsgHTML($html); 
			if( $email_list != ""){   
			 	$mail->AddAddress($email_list);   
			}  
			if(!$mail->Send()){   
			  	echo "Error al enviar, causa: " .$mail->ErrorInfo;  
			  	$ToReturn = FALSE;
			}else{   
			  	$ToReturn = TRUE;
		  	} 
		}
		return $ToReturn;

	}

	*/
	//Versión 2
	public function SendMail($html,$subject,$email_list,$info,$cedente){ 
		//include('../../includes/functions/Functions.php');
		include('../../db/connect.php');
		include('../../class/global/cedente.php');
		if(!class_exists('DB')){
			include('../../class/db/DB.php');
		}
		//include('opciones.php'); 
		$CedenteClass = new Cedente();
		$config = new opciones; 
		$Mandante = $CedenteClass->getMandanteFromCedente($cedente);
		$Conf = $config->configvalues($con,$cedente);

		$ToReturn = false;
		$mail = new PHPMailer();  
		if($Conf["ProtocolSMTP"] != ""){
			$mail->IsSMTP();
			$mail->SMTPAuth = true;
			$mail->SMTPOptions = array(
				'ssl' => array(
					'verify_peer' => false,
					'verify_peer_name' => false,
					'allow_self_signed' => true
				)
			);
		}
		if($Conf["SecureSSL"] != ""){
			$mail->SMTPSecure = "ssl";
		}

		if($Conf["SecureTLS"] != ""){
			$mail->SMTPSecure = "TLS";
		}
		
		$mail->Host = $Conf["Host"]; 
		//$mail->SMTPDebug = 1;  
		$mail->Port = $Conf["Port"];  
		$mail->Username = $Conf["Email"];  
		$mail->Password = $Conf["Pass"];  
		$mail->From = $Conf["FromEmail"];   
		$mail->FromName = $Conf["FromName"];  
		$mail->Subject = $subject;  
		$mail->IsHTML(true);  

		$adjuntos = $info['adjuntos']; 
		$variables = $info['variables']; 

		if(is_array($email_list)){
			foreach($email_list as $email){ 				
				if( $email != ""){
					$find = array('[correo]');
					$replace = array($email);
					
					foreach ($variables as $var){
						$find[]='['.$var.']';
						$replace[] = $info[$email][$var];
					}
					$content = str_replace($find, $replace, $html);

					$mail->MsgHTML($content);   

			 		//$mail->AddAddress('jurbina@cobranding.cl'); 
					if(isset($adjuntos[$email])){
						foreach ($adjuntos[$email] as $adjunto) {
							$archivo = '../../facturas/'.$Mandante["id"]."/".$cedente."/".$info[$email]["Rut"]."/".$adjunto.'.pdf';
							if(file_exists($archivo)){
								$mail->addAttachment($archivo);  
							}
						}
						//$mail->AddAddress('jurbina@cobranding.cl'); 
						$mail->AddAddress($email); 
						$mail->send();
						$mail->ClearAllRecipients();
						$mail->clearAttachments();
					}else{
						//$mail->AddAddress('jurbina@cobranding.cl'); 
						$mail->AddAddress($email); 
						$mail->send();
						$mail->ClearAllRecipients();
					}
				}
			}
			$ToReturn = true;
		} else { 
			$mail->MsgHTML($html); 
			if( $email_list != ""){   
			 	$mail->AddAddress($email_list);   
				//$mail->AddAddress('jurbina@cobranding.cl');
			}  
			if(!$mail->Send()){   
			  	echo "Error al enviar, causa: " .$mail->ErrorInfo;  
			  	$ToReturn = false;
			}else{   
			  	$ToReturn = true;
		  	} 
		}
		return $ToReturn;	
	}

	//Backup
	/*public function SendMail($html,$subject,$email_list,$info){ 
		$ToReturn = FALSE;
		$mail = new PHPMailer();  
		$mail->IsSMTP();
		$mail->SMTPAuth = true;  
		//$mail->SMTPSecure = "ssl";   // enviar
		$mail->Host = "mail.cobranding.cl"; 
		//$mail->SMTPDebug = 1;  
		$mail->Port = 25;  
		$mail->Username = "redes@cobranding.cl";  
		$mail->Password = "M9a7r5s3A";  
		$mail->From = "redes@cobranding.cl";   
		$mail->FromName = "eMAIL foCO";  
		$mail->SMTPOptions = array(
			'ssl' => array(
				'verify_peer' => false,
				'verify_peer_name' => false,
				'allow_self_signed' => true
			)
		);
		$mail->Subject = $subject;  
		$mail->IsHTML(true);  

		$adjuntos = $info['adjuntos']; 
		$variables = $info['variables']; 

		if(is_array($email_list)){
			foreach($email_list as $email){ 				
				if( $email != ""){
					$find = array('[correo]');
					$replace = array($email);
					
					foreach ($variables as $var){
						$find[]='['.$var.']';
						$replace[] = $info[$email][$var];
					}
					$content = str_replace($find, $replace, $html);

					$mail->MsgHTML($content);   

			 		//$mail->AddAddress('mmelissa@cobranding.cl'); 
					if(isset($adjuntos[$email])){
						foreach ($adjuntos[$email] as $adjunto) {
							$archivo = '../../facturas/'.$adjunto.'.pdf';
							if(file_exists($archivo)){
								$mail->addAttachment($archivo);  
							}
						}
							$mail->AddAddress($email); 
							$mail->send();
							$mail->ClearAllRecipients();
							$mail->clearAttachments();
					}else{
						$mail->AddAddress($email); 
						$mail->send();
						$mail->ClearAllRecipients();
					}
				}
			}
			$ToReturn = TRUE;
		} else { 
			$mail->MsgHTML($html); 
			if( $email_list != ""){   
			 	$mail->AddAddress($email_list);   
				 //$mail->AddAddress('mmelissa@cobranding.cl'); 
			}  
			if(!$mail->Send()){   
			  	echo "Error al enviar, causa: " .$mail->ErrorInfo;  
			  	$ToReturn = FALSE;
			}else{   
			  	$ToReturn = TRUE;
		  	} 
		}
		return $ToReturn;	
	}*/

	public function SendTest($html,$subject,$email_list){ 		
		$ToReturn = FALSE;
		$mail = new PHPMailer();  
		/*$mail->IsSMTP();
		$mail->SMTPAuth = true;  
		//$mail->SMTPSecure = "ssl";   
		$mail->Host = "mail.cobranding.cl"; 
		//$mail->SMTPDebug = 1;  
		$mail->Port = 25;  
		$mail->Username = "redes@cobranding.cl";  
		$mail->Password = "M9a7r5s3A";  
		$mail->From = "redes@cobranding.cl";   
		$mail->FromName = "eMAIL foCO";   
		$mail->SMTPOptions = array(
			'ssl' => array(
				'verify_peer' => false,
				'verify_peer_name' => false,
				'allow_self_signed' => true
			)
		);*/

		include('../../includes/functions/Functions.php');
		include('../../db/connect.php');
		include('opciones.php');

		$config = new opciones; 
		$Conf = $config->configvalues($con);

		if($Conf["ProtocolSMTP"] != ""){
			$mail->IsSMTP();
			$mail->SMTPAuth = true;
			$mail->SMTPOptions = array(
				'ssl' => array(
					'verify_peer' => false,
					'verify_peer_name' => false,
					'allow_self_signed' => true
				)
			);
		}
		if($Conf["SecureSSL"] != ""){
			$mail->SMTPSecure = "ssl";
		}

		if($Conf["SecureTLS"] != ""){
			$mail->SMTPSecure = "TLS";
		}
		
		$mail->Host = $Conf["Host"]; 
		//$mail->SMTPDebug = 1;  
		$mail->Port = $Conf["Port"];  
		$mail->Username = $Conf["Email"];  
		$mail->Password = $Conf["Pass"];  
		$mail->From = $Conf["FromEmail"];   
		$mail->FromName = $Conf["FromName"];  

		$mail->Subject = $subject; 
		$mail->IsHTML(true); 
		$mail->MsgHTML($html);

		if(is_array($email_list)){
			foreach($email_list as $email){ 				
				if( $email != ""){   
					$mail->AddAddress($email); 
			   		$mail->send();
			   		$mail->ClearAllRecipients();  
				}
			}
			$ToReturn = TRUE;
		} else { 
			if( $email_list != ""){   
			 	$mail->AddAddress($email_list);   
			}  
			if(!$mail->Send()){   
			  	echo "Error al enviar, causa: " .$mail->ErrorInfo;  
			  	$ToReturn = FALSE;
			}else{   
			  	$ToReturn = TRUE;
		  	} 
		}
		return $ToReturn;
	
	}
	public function SendNotification($html,$subject,$email_list,$FromName = "eMAIL foCO"){ 		
		$ToReturn = FALSE;
		$mail = new PHPMailer();  
		$mail->IsSMTP();
		$mail->SMTPAuth = true;  
		//$mail->SMTPSecure = "ssl";   
		$mail->Host = "mail.cobranding.cl"; 
		//$mail->SMTPDebug = 1;  
		$mail->Port = 25;  
		$mail->Username = "redes@cobranding.cl";  
		$mail->Password = "M9a7r5s3A";  
		$mail->From = "redes@cobranding.cl";  		
		$mail->FromName = $FromName;  
		$mail->Subject = $subject; 
		$mail->IsHTML(true);
		$mail->MsgHTML($html); 
		$mail->SMTPOptions = array(
			'ssl' => array(
				'verify_peer' => false,
				'verify_peer_name' => false,
				'allow_self_signed' => true
			)
		);

		if(is_array($email_list)){
			foreach($email_list as $email){ 				
				if( $email != ""){   
					$mail->AddAddress($email); 
			   		$mail->send();
			   		$mail->ClearAllRecipients();  
				}
			}
			$ToReturn = TRUE;
		} else { 
			if( $email_list != ""){   
			 	$mail->AddAddress($email_list);   
			}  
			if(!$mail->Send()){   
			  	echo "Error al enviar, causa: " .$mail->ErrorInfo;  
			  	$ToReturn = FALSE;
			}else{   
			  	$ToReturn = TRUE;
		  	} 
		}
		return $ToReturn;
	
	}

	public function get_var_value($rut,$var,$cedente){

    	$db = new Db();

		$return = false;

		$fields_variable = "SELECT * FROM Variables WHERE variable= '".$var."' and id_cedente='".$cedente."'";

//if($rut == "21166628"){
//echo $fields_variable."\n";
//}

		$row = $db->select($fields_variable);

		if(count($row)>0){

			$row_var = $row[0];

			$tabla = $row_var['tabla'];
			$campos = $row_var['campo'];
			$operacion = $row_var['operacion'];
			$array_campos = explode(',', $campos);
			$cedente = ($tabla == 'Deuda') ? " AND Id_Cedente = '".$cedente."'" : "";

			if($operacion == ''){

				$consulta_valores = "SELECT ".$campos." FROM ".$tabla." WHERE Rut='".$rut."'".$cedente;

			} else{
				$consulta_valores = "SELECT ".$operacion."(".$campos.") AS ".$campos." FROM ".$tabla." WHERE Rut='".$rut."'".$cedente;
			}
			
			$valores = $db->select($consulta_valores);
			
			if(count($array_campos) > 1){
				$tabla = '<table width="700" style="border-spacing: 0px;">
				<thead>
					<tr style="background-color: #5fa2dd; color: #FFFFFF; text-align: center;">';
				foreach ($array_campos as $campo) {
					$tabla .= '<th>'.ucfirst(str_replace('_',' ',$campo)).'</th>';
				}

				$tabla .= '</tr>
				</thead>
				<tbody>';
				if(count($valores) > 0){
					foreach($valores as $valor){
						$tabla .= '<tr>';
						foreach ($array_campos as $campo) {
							$tabla .=  '<td style="border-bottom: 1px solid #CCCCCC;text-align: center;">'.$valor[$campo].'</td>';
						}
						$tabla .= '</tr>';
					}
				}
				$tabla .= '</tbody>
				</table>';

				$return = $tabla;

			} elseif(count($array_campos) == 1){

				$valores = $valores[0];

				$return = $valores[$campos];
			} 

			return $return;

		}

		return $return;
	}
	public function gen_code(){

    	$db = new Db();
		$exist = true;
		$char = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    	$lon = strlen($char) - 1;
    	$return = false;
    
		do{
			$code = '';
			for($i=0;$i<6;$i++){
				$code .= substr($char, rand(0, $lon), 1);
			}

			$query_exist = "SELECT * FROM Confirmacion WHERE codigo = '".$code."'";

			$result = $db->select($query_exist);

			if(count($result) > 0){
				$exist = true;
			} else{
				$exist = false;
			}

		} while ($exist);

		$return = $code;

		return $return;
	}

	public function verificacionCron(){
		$db = new Db();
		$isValid = 2; // 1=activo y 2=inactivo   
		// verifico si el cron esta activo
		$consulta_cron = "SELECT * FROM cron_email WHERE estatus = 1 and id = 1";
		$cron = $db->select($consulta_cron);
		if(count($cron) > 0){
			$isValid = 1;
		}
		return $isValid;   
	}

	public function verificacionAlertaEnvio(){
		$db = new Db();
		//verifico si existe cola de envio
		$consulta_envio = "SELECT DISTINCT id_usuario FROM envio_email WHERE status = 0";
		$envio = $db->select($consulta_envio);
		$colaUsuarios = array();
		if(count($envio) > 0){			
		    foreach($envio as $cola){
				$idUsuario = $cola['id_usuario'];
        		$consultaUsuario = "SELECT nombre, id FROM Usuarios WHERE id = ".$idUsuario."";
        		$usua = $db->select($consultaUsuario);
          		foreach($usua as $usuario){
					$Array = array();
            		$Array[] = $usuario['nombre'];
            		array_push($colaUsuarios, $Array);
          		}
            }
		}
    	return $colaUsuarios;
	}

	public function getListarColas(){
    	$db = new Db();
    	$colasArray = array();
    	$Sql = "SELECT estrategia, id FROM envio_email WHERE id_usuario = ".$_SESSION['id_usuario']." AND status = 0";
    	$colas = $db -> select($Sql);
    	foreach($colas as $cola){
      		$Array = array();
      		$Array['estrategia'] = $cola["estrategia"];
      		$Array['id'] = $cola["id"];
      		array_push($colasArray,$Array);
    	}
   		return $colasArray;
  	}
	 

	public function cancelarColaEnvio($idCola){
    	$db = new Db();
  		$SqlUpdate = "UPDATE envio_email set status = '2', fechaProceso = NOW(), continuar = '0'  WHERE id ='".$idCola."'";
    	$db -> query($SqlUpdate);
    }  

	public function continuarEnvioCola($idCola){
		$isValid = 2;
    	$db = new Db();
  		$SqlUpdate = "UPDATE envio_email set continuar = '0' WHERE id ='".$idCola."'";
    	$db -> query($SqlUpdate);
		// verifico si tengo mas envios en cola parados
		$Sql = "SELECT * FROM envio_email WHERE status = 0, fechaProceso = NOW() AND continuar = 1";
    	$colas = $db -> select($Sql);
		if(count($colas) == 0){		
			// si no existen colas paradas activo el cron
			$SqlUpdate = "UPDATE cron_email set estatus = 1 WHERE id = 1";
    		$db -> query($SqlUpdate);
			$isValid = 1;
		}
		return $isValid;   	
    } 
	
	 
	
}