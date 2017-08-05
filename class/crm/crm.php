<?php
include("../../db/db.php");
/* include("../../mail/class.phpmailer.php");
include("../../mail/class.smtp.php"); */

include("template.php");
class crm
{

	function __construct(){
		if(!isset($_SESSION)){
			session_start();
		}
	}
	public function Pantalla($id,$rut,$cedente,$fono,$usuario)
	{
		$this->id=$id;
		$this->rut=$rut;
		$this->cedente=$cedente;
		$this->fono=$fono;
		$this->usuario=$usuario;
		session_start();
		$_SESSION['id_dial'] = $this->id;
		$_SESSION['MM_UserGroup'] = '4';
		$_SESSION['rut_dial'] = $this->rut;
		$_SESSION['cedente_dial'] = $this->cedente;
		$_SESSION['fono_dial'] = $this->fono;
		$_SESSION['MM_Username'] = $this->usuario;
		header('Location: index.php');

		session_start();

	}
/* public function mostrarCedente()
{
	echo "<select class='select1' id='seleccione_cedente' name='seleccione_cedente'>";
	$result=mysql_query("SELECT Id_Cedente,Nombre_Cedente FROM Cedente");
	echo '<option value="0">Seleccione</option>';
	while($row=mysql_fetch_array($result))
	{
		echo "<option value='$row[0]'>$row[1]</option>";
	}
	echo "</select>";
}
public function mostrarCedente2()
{
	echo "<select class='select1' id='seleccione_cedente2' name='seleccione_cedente2'>";
	$result=mysql_query("SELECT Id_Cedente,Nombre_Cedente FROM Cedente");
	echo '<option value="0">Seleccione</option>';
	while($row=mysql_fetch_array($result))
	{
		echo "<option value='$row[0]'>$row[1]</option>";
	}
	echo "</select>";
} */
 public function mostrarEstrategia($id)
{
	$db = new DB();
	$this->id=$id;
	$rows = $db->select("SELECT id,nombre FROM SIS_Estrategias WHERE Id_Cedente = $this->id ");
	echo "<select  id='seleccione_estrategia' class='select1' name='seleccione_estrategia' >";
	echo "<option value='0'>Seleccione</option>";
	foreach($rows as $row)
	{
		echo "<option value='".$row["id"]."'>".$row["nombre"]."</option>";

	}
	echo "</select>";
} 

	public function mostrarColaDiscador()
	{
		$db = new DB();
		$dbDiscador = new DB("discador");
		$sql = "SELECT Asterisk_Discador_Cola.id, Asterisk_Discador_Cola.Cola, Asterisk_All_Queues.Queue FROM Asterisk_Discador_Cola inner join Asterisk_All_Queues on Asterisk_All_Queues.id_discador = Asterisk_Discador_Cola.id WHERE Status = 1 and Id_Cedente='".$_SESSION['cedente']."' group by Asterisk_Discador_Cola.Cola";
		$resu = $dbDiscador->select($sql);

		echo "<select  id='seleccione_tipo_busqueda' class='select1' name='seleccione_tipo_busqueda'>";
        echo "<option value='0'>Seleccione</option>";
		foreach($resu as $fila){
			$idCola = $fila["id"];
			$cola = $fila["Cola"];
			$array = explode('_',$cola);
			$tipo = $array[3];
			$id = $array[4];
			$Queue = $fila["Queue"];


			switch ($tipo){
				case 'G':
					 $sql2 = "SELECT Nombre FROM grupos WHERE IdGrupo = '".$id."'";
					 $resu2 = $db->select($sql2);
					 $nombre = $resu2[0]["Nombre"];
				break;
				case 'E':
				case 'S':
					$sql3 ="SELECT Nombre FROM Personal WHERE Id_Personal = '".$id."'";
					$resu3 = $db->select($sql3);
					$nombre = $resu3[0]["Nombre"];
				break;
			}

			echo "<option value='".$idCola."'>".$nombre." - ".$Queue."</option>";
		}
		echo "</select>";

    }

	public function unPausePredictivo(){
		$dbDiscador = new DB("discador");
		$Anexo = "SIP/".$_SESSION['anexo_foco'];
		$row2 = $dbDiscador->select("SELECT Queue FROM Asterisk_Agentes WHERE Agente = '$Anexo' limit 1");
		$queues = $row2[0]["Queue"];
		shell_exec("php /var/www/html/produccion/discador/AGI/Unpause.php '$queues' '$Anexo'");
	}

	public function predictivoRut(){
		$dbDiscador = new DB("discador");
		$anexo = $_SESSION['anexo_foco'];
		$query = $dbDiscador->select("SELECT * FROM Asterisk_Bridge WHERE Anexo = '$anexo'");
   		$cantidad = count($query);
		$fono = "";
		$rut = "";
		$boolean = true;
		if ($cantidad > 0){

      		$row = $dbDiscador->select("SELECT Rut, Fono FROM Asterisk_Bridge WHERE Anexo = '$anexo' limit 1");
       		$cantidad = count($row);

			$rut = $row[0]["Rut"];
			$fono = $row[0]["Fono"];
			$Anexo = "SIP/".$_SESSION['anexo_foco'];

			$row2 = $dbDiscador->select("SELECT Queue FROM Asterisk_Agentes WHERE Agente = '$Anexo' limit 1");
			$queues = $row2[0]["Queue"];



			shell_exec("php /var/www/html/produccion/discador/AGI/Pause.php '$queues' '$Anexo'");


   		}
		   $array = array('uno'=>$rut,'dos'=>$fono, 'cantidad'=>$cantidad);
		   echo json_encode($array);


	}

	public function insertarDatosCola($idCola){
		$dbDiscador = new DB("discador");
		if($_SESSION['anexo_foco'] == 0){
			echo "1"; // el usuario no tiene anexo
		}else{
			$row = $dbDiscador->select("SELECT Queue FROM Asterisk_All_Queues WHERE id_discador = $idCola");
			$queues = $row[0]["Queue"];
			$Anexo = "SIP/".$_SESSION['anexo_foco'];			
			shell_exec("php /var/www/html/produccion/discador/AGI/EntrarCola.php '$Anexo' '$queues'");

			$ValidarAnexo = $dbDiscador->select("SELECT * FROM Asterisk_Agentes WHERE Agente = '$Anexo'");
			if(count($ValidarAnexo)>0){
				$dbDiscador->query("UPDATE Asterisk_Agentes SET Agente = '$Anexo',Queue = '$queues' WHERE Agente = '$Anexo' ");
			}
			else{
				$dbDiscador->query("INSERT INTO Asterisk_Agentes(Agente,Queue) VALUES ('$Anexo','$queues')");
			}
			echo "2";
		}

	}

	public function eliminarAnexo($idCola){
		$db = new DB("discador");
		$anexo = "SIP/".$_SESSION['anexo_foco'];

		$sql = $db->query("DELETE FROM Asterisk_Agentes WHERE Agente = '$anexo' ");

		$row = $db->select("SELECT Queue FROM Asterisk_All_Queues WHERE id_discador = $idCola");
		$queues = $row[0]["Queue"];
		shell_exec("php /var/www/html/produccion/discador/AGI/SalirCola.php '$anexo' '$queues'");


	}

    public function mostrarCola($id)
	{
		$db = new DB();
		$this->id=$id;
        $rows = $db->select("SELECT id,cola FROM SIS_Querys_Estrategias WHERE id_estrategia = $this->id  AND terminal = 1 AND discador=1");
        echo "<select  id='seleccione_cola' class='select1' name='seleccione_cola' >";
        echo "<option value='0'>Seleccione</option>";
        foreach($rows as $row)
        {
        	echo "<option value='".$row["id"]."'>".$row["cola"]."</option>";

        }
        echo "</select>";
    }
    public function mostrarRut($Prefijo)
	{
		$db = new DB();


		$this->actualizaAsignacion($Prefijo);
		$SqlPrimerRut = "SELECT * FROM ".$Prefijo." WHERE orden = 1";
		$resu = $db->select($SqlPrimerRut);
		$sql2 = "SELECT * FROM ".$Prefijo."";
		$resu2 = $db->select($sql2);
		$cantidadRut = count($resu2);
		$rut = $resu[0]["Rut"];
		$siete = "1 de ".$cantidadRut." Rut";
		/*$SqlRutSinGestion = "SELECT * FROM ".$this->Prefijo." WHERE estado = 0 ORDER BY id ASC LIMIT 2";
		$resu = $db->select($SqlRutSinGestion);
		if (count($resu)>0){
			$rut = $resu[0]["Rut"];
			//$rutNext = $resu[1]["Rut"];
		}else{
			// busco los sin contacto
			$SqlRutSinContacto = "SELECT * FROM ".$this->Prefijo." WHERE estado = 2 ORDER BY id ASC LIMIT 1";
			$resu = $db->select($SqlRutSinContacto);
			if (count($resu)>0){
				$rut = $resu[0]["Rut"];
			}else{
				// busco los contactados
				$SqlRutConContacto = "SELECT * FROM ".$this->Prefijo." WHERE estado = 1 ORDER BY id ASC LIMIT 1";
				$resu = $db->select($SqlRutConContacto);
				$rut = $resu[0]["Rut"];
			}

		} */

		$rows = $db->select("SELECT Nombre_Completo FROM Persona WHERE Rut = $rut LIMIT 1");
        foreach($rows as $row)
        {
        	$nombre = utf8_encode($row["Nombre_Completo"]);
        }


		/*$q2 = mysql_query("SELECT Rut FROM $this->Prefijo LIMIT 1");
        while($row = mysql_fetch_array($q2))
        {
        	$rut = $row[0];

        	$qn = mysql_query("SELECT Nombre_Completo FROM Persona WHERE Rut = $rut LIMIT 1");
        	while($row = mysql_fetch_array($qn))
        	{
        		$nombre = $row[0];
        	}
        } */
        $uno =  "<input type='text' value='$rut' class='form-control' readonly='readonly'>";
        $cinco= "Rut : ".$rut;
		$seis = $this->getProgressAsignacion($Prefijo);
        $array = array('uno' => $uno, 'dos' => $rut, 'tres' => $nombre, 'cuatro' => $Prefijo, 'cinco' => $cinco, 'seis' => $seis, 'siete' => $siete);
		echo json_encode($array);

    }

	function ordenarAsignacionContacto($Asignacion, $tipo){
		$db = new DB();
		$Pass = true;
		switch ($tipo){
			case 1:
			// Sin gestion mostrara 0 1 2
			$sql2 = "SELECT id FROM ".$Asignacion." ORDER BY fechaGestion ASC";
			$resultado2 = $db->select($sql2);
			break;
			case 2:
			// Sin Contacto
			$sql2 = "SELECT id FROM ".$Asignacion." ORDER BY estado ASC, fechaGestion ASC";
			$resultado2 = $db->select($sql2);
			break;
			case 3:
			// contactados 3
			$sql2 = "SELECT id FROM ".$Asignacion." ORDER BY estado DESC, fechaGestion ASC";
			$resultado2 = $db->select($sql2);
			break;
			default:
				$Pass = false;
			break;
		}
		if($Pass){
			$contador = 0;
			foreach($resultado2 as $fila2){
				$contador = $contador + 1;
				$id = $fila2["id"];
				$SqlUpdate = "UPDATE ".$Asignacion." set orden = '".$contador."' WHERE id='".$id."'";
				$db -> query($SqlUpdate);
			}

			$SqlPrimerRut = "SELECT * FROM ".$Asignacion." WHERE orden = 1";
			$resu = $db->select($SqlPrimerRut);
			$sql2 = "SELECT * FROM ".$Asignacion."";
			$resu2 = $db->select($sql2);
			$cantidadRut = count($resu2);
			$rut = $resu[0]["Rut"];
			$siete = "1 de ".$cantidadRut." Rut";

			$qn = mysql_query("SELECT Nombre_Completo FROM Persona WHERE Rut = $rut LIMIT 1");
			while($row = mysql_fetch_array($qn))
			{
				$nombre = utf8_encode($row[0]);
			}


			/*$q2 = mysql_query("SELECT Rut FROM $this->Prefijo LIMIT 1");
			while($row = mysql_fetch_array($q2))
			{
				$rut = $row[0];

				$qn = mysql_query("SELECT Nombre_Completo FROM Persona WHERE Rut = $rut LIMIT 1");
				while($row = mysql_fetch_array($qn))
				{
					$nombre = $row[0];
				}
			} */
			$uno =  "<input type='text' value='$rut' class='form-control' readonly='readonly'>";
			$cinco= "Rut : ".$rut;
			$seis = $this->getProgressAsignacion($Asignacion);
			$array = array('uno' => $uno, 'dos' => $rut, 'tres' => $nombre, 'cuatro' => $Asignacion, 'cinco' => $cinco, 'seis' => $seis, 'siete' => $siete);
			echo json_encode($array);
		}
	}


	function getProgressAsignacion($Asignacion){
		$db = new DB();
		$ToReturn = "";
		$SqlTotal = "select count(*) as Total
					from
						".$Asignacion;
		$Total = $db->select($SqlTotal);
		$Total = $Total[0]["Total"];
		$SqlGestionado = "select count(*) as Gestionado
					from
						".$Asignacion."
					where
						estado <> '0'";
		$Gestionado = $db->select($SqlGestionado);
		$Gestionado = $Gestionado[0]["Gestionado"];
		$ToReturn = $Gestionado == 0 ? 0 : ($Gestionado * 100) / $Total;
		$ToReturn = round($ToReturn);
		return $ToReturn;
	}
    public function nextRut($rut,$prefijo,$ordenViejo)
	{
		$db = new DB();
		$this->rut=$rut;
		//$this->prefijo=$prefijo;

		$SqlCantidadRut = "SELECT count(*) as cantidad FROM ".$prefijo."";
		$cant = $db->select($SqlCantidadRut);
		$cantidadRut  = $cant[0]["cantidad"];

		$EncontroRut = false;
		while($EncontroRut == false){
			$ordenViejo = $ordenViejo + 1;
			if ($ordenViejo > $cantidadRut){
				$ordenViejo = 1;
				//$this->actualizaAsignacion($prefijo);
				$this->ordenarAsignacion($prefijo);
			}
			$SqlRut = "SELECT Rut FROM ".$prefijo." WHERE orden = '".$ordenViejo."'";
			$resu = $db->select($SqlRut);
			if (count($resu) > 0){
				$nuevo_rut = $resu[0]["Rut"];
				$EncontroRut = true;
				$ocho = $ordenViejo;
			}
		}

		$siete = $ordenViejo." de ".$cantidadRut." Rut";
		$Sqlnombre = "SELECT Nombre_Completo FROM Persona WHERE Rut = '".$nuevo_rut."' LIMIT 1";
		$nom = $db->select($Sqlnombre);
		$nuevo_nombre = utf8_encode($nom[0]["Nombre_Completo"]);
		$seis = $this->getProgressAsignacion($prefijo);

		$uno =  "<input id='next_rut' type='text' value='$nuevo_rut' class='form-control' readonly='readonly'>";
		$cinco= "Rut : ".$nuevo_rut;
        $array = array('uno' => $uno, 'dos' => $nuevo_rut, 'tres' => $nuevo_nombre, 'cuatro' => $prefijo, 'cinco' => $cinco, 'seis' => $seis, 'siete' => $siete, 'ocho' => $ocho);
		echo json_encode($array);

	}
	public function mostrarNombreRut($rut){
		$dbDiscador = new DB("discador");
		$qn = $dbDiscador->select("SELECT Nombre_Completo FROM Persona WHERE Rut = $rut LIMIT 1");
        
		echo $qn[0]["Nombre_Completo"]."akkakaka";
	}
	public function prevRut($rut,$prefijo)
	{
		$db = new DB();
		$this->rut=$rut;
		$this->rut=$rut;
		//$this->prefijo=$prefijo;

		$SqlCantidadRut = "SELECT id FROM ".$prefijo."";
		$cant = $db->select($SqlCantidadRut);
		$cantidadRut= count($cant);

		$SqlRut = "SELECT orden FROM ".$prefijo." WHERE Rut = '".$this->rut."'";
		$resu = $db->select($SqlRut);
		$orden = $resu[0]["orden"];
		$orden = $orden - 1;
		if ($orden == 0){
			$orden = $cantidadRut;
		}
		$SqlRutNext = "SELECT Rut FROM ".$prefijo." WHERE orden = '".$orden."'";
		$resu = $db->select($SqlRutNext);
		$nuevo_rut = $resu[0]["Rut"];

		$Sqlnombre = "SELECT Nombre_Completo FROM Persona WHERE Rut = '".$nuevo_rut."' LIMIT 1";
		$nom = $db->select($Sqlnombre);
		$nuevo_nombre = $nom[0]["Nombre_Completo"];
		$seis = $this->getProgressAsignacion($prefijo);
		$siete = $orden." de ".$cantidadRut." Rut";
		/*$this->prefijo=$prefijo;
		$cr = mysql_query("SELECT id FROM $prefijo ");
		$cant = mysql_num_rows($cr);
		$nr = mysql_query("SELECT id FROM $prefijo WHERE Rut = $this->rut LIMIT 1");
		while($row = mysql_fetch_array($nr))
		{

			$id_rutp = $row[0]-1;
			if($id_rutp==0)
			{
				$id_rut = $cant;
			}
			else
			{
				$id_rut = $row[0]-1;
			}
		}
		$nrn = mysql_query("SELECT Rut FROM  $prefijo WHERE id = $id_rut LIMIT 1");
		while($row = mysql_fetch_array($nrn))
		{
			$nuevo_rut = $row[0];
			$qn = mysql_query("SELECT Nombre_Completo FROM Persona WHERE Rut = $nuevo_rut LIMIT 1");
        	while($row = mysql_fetch_array($qn))
        	{
        		$nuevo_nombre = $row[0];
        	}
		} */
		$uno =  "<input type='text' value='$nuevo_rut' class='form-control' readonly='readonly'>";
		$cinco= "Rut : ".$nuevo_rut;
        $array = array('uno' => $uno, 'dos' => $nuevo_rut, 'tres' => $nuevo_nombre, 'cuatro' => $prefijo, 'cinco' => $cinco, 'seis' => $seis, 'siete' => $siete);
		echo json_encode($array);

	}
/* public function deudaRut($rut)
{
	$this->rut=$rut;
	echo "<select class='select1' id='seleccione_cedente' name='seleccione_cedente'";
	$result=mysql_query("SELECT Producto FROM Deuda WHERE Rut = $rut");
	while($row=mysql_fetch_array($result))
	{
		echo "<option value='$row[0]'>$row[1]</option>";
	}
	echo "</select>";
} */
	public function cantRegistros($rut,$prefijo)
	{
		$db = new DB();
		$this->rut=$rut;
		$this->prefijo=$prefijo;
		$qn = $db->select("SELECT Rut FROM  $this->prefijo ");
		$num = count($qn);
		$rows = $db->select("SELECT id FROM  $this->prefijo WHERE Rut = $this->rut");
	    foreach($rows as $row)
        {
        	$id = $row["id"];
        }
        $valor = $id." de ".$num;
        echo "<input type='text' value='$valor' disabled='disabled'  class='form-control'>";

	}
	public function marcarFactura($rut,$cedente,$id_deuda,$id)
	{
		$this->rut=$rut;
		$this->cedente=$cedente;
		$this->id_deuda=$id_deuda;
		$this->id=$id;
		if($this->id ==1)
		{

			session_start();
			$_SESSION['mfacturas'][] = $this->id_deuda;
			$mfacturas = $_SESSION['mfacturas'];
			echo "Factura Adjunta".print_r($mfacturas);
			session_start();
		}
		else
		{
			session_start();
			$clavem = array_search($this->id_deuda, $_SESSION['mfacturas']);
			unset($_SESSION['mfacturas'][$clavem]);
			echo "Factura Removida".$clavem;
			session_start();
		}


	}
	public function marcarMail($id_mail,$id)
	{
		$this->id_mail=$id_mail;
		$this->id=$id;
		if($this->id ==1)
		{

				session_start();
				$_SESSION['correos'][] = $this->id_mail;
				$correos = $_SESSION['correos'];
				echo "Email Activado".print_r($correos);
				session_start();
		}
		else
		{
				session_start();
				$clave = array_search($this->id_mail, $_SESSION['correos']);
				unset($_SESSION['correos'][$clave]);
				echo "Email Desactivado".$clave;
				session_start();
		}
	}

	public function marcarMailcc($id_mail,$id)
	{
		$this->id_mail=$id_mail;
		$this->id=$id;
		if($this->id ==1)
		{

				session_start();
				$_SESSION['correos_cc'][] = $this->id_mail;
				$correos_cc = $_SESSION['correos_cc'];
				echo "Email Activado".print_r($correos_cc);
				session_start();
		}
		else
		{
				session_start();
				$clave_cc = array_search($this->id_mail, $_SESSION['correos_cc']);
				unset($_SESSION['correos_cc'][$clave_cc]);
				echo "Email Desactivado".$clave_cc;
				session_start();
		}
	}

	public function actualizarCorreo($id_mail,$mail,$nombre,$cargo,$obs)
	{
		$db = new DB();
		$this->id_mail=$id_mail;
		$this->mail=$mail;
		$this->nombre=$nombre;
		$this->cargo=$cargo;
		$this->obs=$obs;

		$q = "UPDATE Mail SET correo_electronico='$this->mail',Nombre='$this->nombre',Cargo='$this->cargo',Observacion ='$this->obs'  WHERE id_mail = $this->id_mail";
		$db->query($q);


	}


	public function enviarMail($cedente,$rut)
	{
		session_start();
		$mailArray = $_SESSION['correos'];
		$mailArraycc = $_SESSION['correos_cc'];
		$facturaArray = $_SESSION['mfacturas'];
		$contarf = count($facturaArray);
		$contarm = count($mailArray);
		if($contarm == 0)
		{
			echo 2;
		}
		else if($contarf == 0)
		{
			echo 3;
		}
		else
		{


			$this->cedente=$cedente;
			$this->rut=$rut;
			if($this->cedente == 48)
			{
				$template = new Template();
				$template->Claro($this->rut,$this->cedente,$mailArray,$facturaArray,$mailArraycc);
			}
			else
			{
				echo 1;
			}
			session_start();
		}

	}
	public function mostrarFonos($rut,$prefijo)
	{
		$this->rut=$rut;
		$this->prefijo=$prefijo;
		$this->mostrandoFonos($this->rut);
	}
	public function mostrarGestionRut($rut)
	{
		$db = new DB();
		$this->rut=$rut;
		$q = $db->select("SELECT rut_cliente,fecha_gestion,resultado,fono_discado,nombre_ejecutivo,cedente,fec_compromiso,monto_comp,Id_TipoGestion,observacion FROM  gestion_ult_trimestre WHERE rut_cliente = $this->rut ");
		if(count($q)==0)
		{
			echo "Rut no registra Gestiones !";
		}
		else
		{
			echo '<div class="table-responsive">';
	        echo '<table id="demo-dt-selection" class="table table-striped table-bordered" cellspacing="0" width="100%">';
	        echo '<thead>';
	        echo '<tr><tr>';
	        echo '<th class="text-sm">Fecha Gestión</th>';
	        echo '<th class="text-sm">Fono Discado</th>';
	        //echo '<th class="text-sm">Nombre Ejecutivo</th>';
	        echo '<th class="text-sm">Respuesta</th>';
	        echo '<th class="text-sm">Sub Respuesta</th>';
	        echo '<th class="text-sm">Sub Respuesta</th>';
	        //echo '<th class="text-sm">Cedente</th>';
	        echo '<th class="text-sm">Fecha Compromiso</th>';
	        echo '<th class="text-sm">Monto Compromiso</th>';
	        echo '<th class="text-sm">Observación</th>';
	        //echo '<th class="text-sm">Tipo Gestión</th></tr>';
	        echo '</thead><tbody>';
		    $query1 = $db->select("SELECT rut_cliente,fecha_gestion,resultado,fono_discado,nombre_ejecutivo,cedente,fec_compromiso,monto_comp,Id_TipoGestion,origen,resultado,resultado_n2,resultado_n3,observacion FROM  gestion_ult_trimestre WHERE rut_cliente = $this->rut  AND Id_TipoGestion IN (1 ,2 ,5) ORDER BY fecha_gestion DESC LIMIT 20");
		    $i = 1;
			foreach($query1 as $q1)
	        {
	        	$v1 = $q1["rut_cliente"];
	        	$v2 = $q1["fecha_gestion"];
	        	$v3 = $q1["resultado"];
	        	$v4 = $q1["fono_discado"];
	        	//$v5 = $q1[4];
	        	$v6 = $q1["cedente"];
	        	$v7 = $q1["fec_compromiso"];
	        	$v8 = $q1["monto_comp"];
	        	$v9 = $q1["Id_TipoGestion"];
	        	$v10 = $q1["observacion"];
	        	$origen = $q1["origen"];
	        	$r1 = $q1["resultado"];
	        	$r2 = $q1["resultado_n2"];
	        	$r3= $q1["resultado_n3"];
	        	if($origen==1)
	        	{
		        	if($v7=='' OR $v7=='0000-00-00')
		        	{
		        		$v7 = '---';
		        		$v8 = '---';
		        	}
		        	else
		        	{
		        		$v7 = $v7;
		        		$v8 = $v8;
		        	}

		        	$query2 = $db->select("SELECT Nombre_Cedente FROM  Cedente WHERE Id_Cedente = $v6");
				    foreach($query2 as $q2)
			        {
			        	$va1 = $q2["Nombre_Cedente"];
			        }
			        $query3 = $db->select("SELECT Gestion_Nivel_1 FROM  respuesta_gestion WHERE Id_Respuesta = $v3");
				    foreach($query3 as $q3)
			        {
			        	$va2 = $q3["Gestion_Nivel_1"];
			        }
			        $query4 = $db->select("SELECT Nombre FROM  Tipo_Contacto WHERE Id_TipoContacto = $v9");
				    foreach($query4 as $q4)
			        {
			        	$va3 = $q4["Nombre"];
			        }
		        	$query5 = $db->select("SELECT Gestion_Nivel_1 FROM  respuesta_gestion WHERE Id_Respuesta = $r1");
				    foreach($query5 as $q5)
			        {
			        	$res1 = $q5["Gestion_Nivel_1"];
			        }


		        	echo "<tr id='$i'>";
				    echo "<td class='text-sm'>$v2</td>";
				    echo "<td class='text-sm'><center> $v4</center></td>";
				    //echo "<td class='text-sm'><center> $v5</center></td>";
				    echo "<td class='text-sm'><center> $res1</center></td>";
				    echo "<td class='text-sm'><center>---</center></td>";
				    echo "<td class='text-sm'><center>---</center></td>";
				    //echo "<td class='text-sm'><center>$va1</center></td>";
				    echo "<td class='text-sm'><center>$v7</center></td>";
				    echo "<td class='text-sm'><center>$v8</center></td>";
				    echo "<td class='text-sm'><center>$v10</center></td>";
				    //echo "<td class='text-sm'><center>$va3</center></td>";
				    echo '</tr>';
				}
				else
				{

		        	if($v7=='' OR $v7=='0000-00-00')
		        	{
		        		$v7 = '---';
		        		$v8 = '---';
		        	}
		        	else
		        	{
		        		$v7 = $v7;
		        		$v8 = $v8;
		        	}

		        	$query2 = $db->select("SELECT Nombre_Cedente FROM  Cedente WHERE Id_Cedente = $v6");
				    foreach($query2 as $q2)
			        {
			        	$va1 = $q2["Nombre_Cedente"];
			        }
			        $query3 = $db->select("SELECT Respuesta_N1 FROM  Nivel1 WHERE id = $r1");
				    foreach($query3 as $q3)
			        {
			        	$re1 = utf8_encode($q3["Respuesta_N1"]);
			        }
			        $query5 = $db->select("SELECT Respuesta_N2 FROM  Nivel2 WHERE id = $r2");
				    foreach($query5 as $q5)
			        {
			        	$re2 = utf8_encode($q5["Respuesta_N2"]);
			        }
			        $query6 = $db->select("SELECT Respuesta_N3 FROM  Nivel3 WHERE id = $r3");
				    foreach($query6 as $q6)
			        {
			        	$re3 = utf8_encode($q6["Respuesta_N3"]);
			        }
			        $query4 = $db->select("SELECT Nombre FROM  Tipo_Contacto WHERE Id_TipoContacto = $v9");
				    foreach($query4 as $q4)
			        {
			        	$va3 = $q4["Nombre"];
			        }
				    echo "<tr id='$i'>";
				    echo "<td class='text-sm'>$v2</td>";
				    echo "<td class='text-sm'><center> $v4</center></td>";
				    //echo "<td class='text-sm'><center> $v5</center></td>";
				    echo "<td class='text-sm'><center> $re1</center></td>";
				    echo "<td class='text-sm'><center> $re2</center></td>";
				    echo "<td class='text-sm'><center> $re3</center></td>";
				    //echo "<td class='text-sm'><center> $va1</center></td>";
				    echo "<td class='text-sm'><center>$v7 </center></td>";
				    echo "<td class='text-sm'><center>$v8 </center></td>";
				    //echo "<td class='text-sm'><center>$va3 </center></td>";
				    echo "<td class='text-sm'><center>$v10</center></td>";
				    echo '</tr>';
				}
				$i++;
	    	}
	    	echo '</tbody></table></div>';
    	}

	}
	public function mostrarGestionCorreo($rut)
	{
		$db = new DB();
		$this->rut=$rut;
		$q = $db->select("SELECT * FROM  gestion_correo WHERE rut_cliente = $this->rut");
		if(count($q)==0)
		{
			echo "Rut no registra Gestiones de Correo!";
		}
		else
		{
			echo '<div class="table-responsive">';
	        echo '<table id="demo-dt-basic" class="table table-striped table-bordered" cellspacing="0" width="100%">';
	        echo '<thead>';
	        echo '<tr><tr>';
	        echo '<th class="text-sm">Fecha Gestión</th>';
	        echo '<th class="text-sm">Hora Gestión</th>';
			echo '<th class="text-sm">Correo</th>';
			echo '<th class="text-sm">Factura</th>';
	        echo '</thead><tbody>';
		    $query1 = $db->select("SELECT fecha_gestion, hora_gestion, correos, facturas FROM  gestion_correo WHERE rut_cliente = $this->rut ORDER BY fecha_gestion DESC LIMIT 20");
		    $i = 1;
			foreach($query1 as $q1)
	        {
	        	$fechaGestion = $q1["fecha_gestion"];
	        	$horaGestion = $q1["hora_gestion"];
	        	$correos = $q1["correos"];
	        	$facturas = $q1["facturas"];

	        	echo "<tr id='$i'>";
				echo "<td class='text-sm'>$fechaGestion</td>";
				echo "<td class='text-sm'>$horaGestion</td>";
				echo "<td class='text-sm'><center>$correos</center></td>";
				echo "<td class='text-sm'>$facturas</td>";
				echo '</tr>';
				
			}
			$i++;
	    	echo '</tbody></table></div>';
    	}

	}
	public function mostrarGestionTotal($rut)
	{
		$db = new DB();
		$this->rut=$rut;
		$q = $db->select("SELECT rut_cliente,fecha_gestion,resultado,fono_discado,nombre_ejecutivo,cedente,fec_compromiso,monto_comp,Id_TipoGestion, factura FROM  gestion_ult_trimestre WHERE rut_cliente = $this->rut");
		if(count($q)==0)
		{
			echo "Rut no registra Gestiones !";
		}
		else
		{
			echo '<div class="table-responsive">';
	        echo '<table id="demo-dt-basic" class="table table-striped table-bordered" cellspacing="0" width="100%">';
	        echo '<thead>';
	        echo '<tr><tr>';
	        echo '<th class="text-sm">Fecha Gestión</th>';
	        echo '<th class="text-sm">Fono Discado</th>';
			echo '<th class="text-sm">Status Name</th>';
	       // echo '<th class="text-sm">Nombre Ejecutivo</th>';
	        echo '<th class="text-sm">Respuesta</th>';
	        echo '<th class="text-sm">Sub Respuesta</th>';
	        echo '<th class="text-sm">Sub Respuesta</th>';
	        //echo '<th class="text-sm">Cedente</th>';
	        echo '<th class="text-sm">Fecha Compromiso</th>';
	        echo '<th class="text-sm">Monto Compromiso</th>';
			echo '<th class="text-sm">Nº Factura</th>';
	        //echo '<th class="text-sm">Tipo Gestión</th></tr>';
	         echo '<th class="text-sm">Observación</th></tr>';
	        echo '</thead><tbody>';
		    $query1 = $db->select("SELECT rut_cliente,fecha_gestion,resultado,fono_discado,nombre_ejecutivo,cedente,fec_compromiso,monto_comp,Id_TipoGestion,origen,n1,n2,n3,observacion, status_name, factura FROM  gestion_ult_trimestre WHERE rut_cliente = $this->rut   ORDER BY fechahora DESC LIMIT 20");
		    $i = 1;
			foreach($query1 as $q1)
	        {
	        	$v1 = $q1["rut_cliente"];
	        	$v2 = $q1["fecha_gestion"];
	        	$v3 = $q1["resultado"];
	        	$v4 = $q1["fono_discado"];
	        	$v5 = $q1["nombre_ejecutivo"];
	        	$v6 = $q1["cedente"];
	        	$v7 = $q1["fec_compromiso"];
	        	$v8 = $q1["monto_comp"];
	        	$v9 = $q1["Id_TipoGestion"];
	        	$v10 = $q1["observacion"];
	        	$origen = $q1["origen"];
	        	$r1 = $q1["n1"];
	        	$r2 = $q1["n2"];
	        	$r3= $q1["n3"];
				$statusName= $q1["status_name"];
				$factura= $q1["factura"];
	        	if($origen==1)
	        	{
		        	if($v7=='' OR $v7=='0000-00-00')
		        	{
		        		$v7 = '---';
		        		$v8 = '---';
		        	}
		        	else
		        	{
		        		$v7 = $v7;
		        		$v8 = $v8;
		        	}
					$va1 = "";
					$va2 = "";
					$va3 = "";
					$res1 = "";

		        	$query2 = $db->select("SELECT Nombre_Cedente FROM  Cedente WHERE Id_Cedente = '$v6'");
				    foreach($query2 as $q2)
			        {
			        	$va1 = $q2["Nombre_Cedente"];
			        }
			        $query3 = $db->select("SELECT Gestion_Nivel_1 FROM  respuesta_gestion WHERE Id_Respuesta = '$v3'");
				    foreach($query3 as $q3)
			        {
			        	$va2 = $q3["Gestion_Nivel_1"];
			        }
			        $query4 = $db->select("SELECT Nombre FROM  Tipo_Contacto WHERE Id_TipoContacto = '$v9'");
				    foreach($query4 as $q4)
			        {
			        	$va3 = $q4["Nombre"];
			        }
		        	$query5 = $db->select("SELECT Gestion_Nivel_1 FROM  respuesta_gestion WHERE Id_Respuesta = '$r1'");
				    foreach($query5 as $q5)
			        {
			        	$res1 = $q5["Gestion_Nivel_1"];
			        }


		        	echo "<tr id='$i'>";
				    echo "<td class='text-sm'>$v2</td>";
				    echo "<td class='text-sm'><center> $v4</center></td>";
				    //echo "<td class='text-sm'><center> $v5</center></td>";
				    echo "<td class='text-sm'><center> $res1</center></td>";
				    echo "<td class='text-sm'><center>---</center></td>";
				    echo "<td class='text-sm'><center>---</center></td>";
				    //echo "<td class='text-sm'><center>$va1</center></td>";
				    echo "<td class='text-sm'><center>$v7</center></td>";
				    echo "<td class='text-sm'><center>$v8</center></td>";
				    //echo "<td class='text-sm'><center>$va3</center></td>";
				    echo "<td class='text-sm'><center>$v10</center></td>";
				    echo '</tr>';
				}
				else
				{

		        	if($v7=='' OR $v7=='0000-00-00')
		        	{
		        		$v7 = '---';
		        		$v8 = '---';
		        	}
		        	else
		        	{
		        		$v7 = $v7;
		        		$v8 = $v8;
		        	}
					$va1 = "";
					$va2 = "";
					$va3 = "";
					$res1 = "";
		        	$query2 = $db->select("SELECT Nombre_Cedente FROM  Cedente WHERE Id_Cedente = '$v6'");
				    foreach($query2 as $q2)
			        {
			        	$va1 = $q2["Nombre_Cedente"];
			        }
			       /* $query3 = $db->select("SELECT Respuesta_N1 FROM  Nivel1 WHERE id = '$r1'");
				    foreach($query3 as $q3)
			        {
			        	$re1 = utf8_encode($q3["Respuesta_N1"]);
			        }
			        $query5 = $db->select("SELECT Respuesta_N2 FROM  Nivel2 WHERE id = '$r2'");
				    foreach($query5 as $q5)
			        {
			        	$re2 = utf8_encode($q5["Respuesta_N2"]);
			        }
			        $query6 = $db->select("SELECT Respuesta_N3 FROM  Nivel3 WHERE id = '$r3'");
				    foreach($query6 as $q6)
			        {
			        	$re3 = utf8_encode($q6["Respuesta_N3"]);
			        } */
			        $query4 = $db->select("SELECT Nombre FROM  Tipo_Contacto WHERE Id_TipoContacto ='$v9'");
				    foreach($query4 as $q4)
			        {
			        	$va3 = $q4["Nombre"];
			        }
				    echo "<tr id='$i'>";
				    echo "<td class='text-sm'>$v2</td>";
				    echo "<td class='text-sm'><center> $v4</center></td>";
					echo "<td class='text-sm'><center>$statusName</center></td>";
				    //echo "<td class='text-sm'><center> $v5</center></td>";
				    echo "<td class='text-sm'><center> $r1</center></td>";
				    echo "<td class='text-sm'><center> $r2</center></td>";
				    echo "<td class='text-sm'><center> $r3</center></td>";
				    //echo "<td class='text-sm'><center> $va1</center></td>";
				    echo "<td class='text-sm'><center>$v7 </center></td>";
				    echo "<td class='text-sm'><center>$v8 </center></td>";
				    //echo "<td class='text-sm'><center> </center></td>";
				    echo "<td class='text-sm'><center>$factura</center></td>";
					  echo "<td class='text-sm'><center>$v10</center></td>";
				    echo '</tr>';
				}

	    	}
			$i++;
	    	echo '</tbody></table></div>';
    	}

	}
	public function mostrarGestionDiaria($rut)
	{
		$db = new DB();
		$this->rut=$rut;
		$q = $db->select("SELECT rut_cliente,fecha_gestion,resultado,fono_discado,nombre_ejecutivo,cedente,fec_compromiso,monto_comp,Id_TipoGestion, factura FROM  gestion_ult_trimestre WHERE rut_cliente = $this->rut");
		if(count($q)==0)
		{
			echo "Rut no registra Gestiones !";
		}
		else
		{
			echo '<div class="table-responsive">';
	        echo '<table id="demo-dt-basic" class="table table-striped table-bordered" cellspacing="0" width="100%">';
	        echo '<thead>';
	        echo '<tr><tr>';
	        echo '<th class="text-sm">Fecha Gestión</th>';
	        echo '<th class="text-sm">Fono Discado</th>';
			echo '<th class="text-sm">Status Name</th>';
	       // echo '<th class="text-sm">Nombre Ejecutivo</th>';
	        echo '<th class="text-sm">Respuesta</th>';
	        echo '<th class="text-sm">Sub Respuesta</th>';
	        echo '<th class="text-sm">Sub Respuesta</th>';
	        //echo '<th class="text-sm">Cedente</th>';
	        echo '<th class="text-sm">Fecha Compromiso</th>';
	        echo '<th class="text-sm">Monto Compromiso</th>';
	        echo '<th class="text-sm">Nº Factura</th>';
	         echo '<th class="text-sm">Observación</th></tr>';
	        echo '</thead><tbody>';
		    $query1 = $db->select("SELECT rut_cliente,fecha_gestion,resultado,fono_discado,nombre_ejecutivo,cedente,fec_compromiso,monto_comp,Id_TipoGestion,origen,n1,n2,n3,observacion,status_name, factura FROM  gestion_ult_trimestre WHERE rut_cliente = $this->rut   ORDER BY fechahora DESC LIMIT 20");
		    $i = 1;
			foreach($query1 as $q1)
	        {
	        	$v1 = $q1["rut_cliente"];
	        	$v2 = $q1["fecha_gestion"];
	        	$v3 = $q1["resultado"];
	        	$v4 = $q1["fono_discado"];
	        	$v5 = $q1["nombre_ejecutivo"];
	        	$v6 = $q1["cedente"];
	        	$v7 = $q1["fec_compromiso"];
	        	$v8 = $q1["monto_comp"];
	        	$v9 = $q1["Id_TipoGestion"];
	        	$v10 = $q1["observacion"];
	        	$origen = $q1["origen"];
	        	$r1 = $q1["n1"];
	        	$r2 = $q1["n2"];
	        	$r3= $q1["n3"];
				$statusName= $q1["status_name"];
				$factura= $q1["factura"];
	        	if($origen==1)
	        	{
		        	if($v7=='' OR $v7=='0000-00-00')
		        	{
		        		$v7 = '---';
		        		$v8 = '---';
		        	}
		        	else
		        	{
		        		$v7 = $v7;
		        		$v8 = $v8;
		        	}

		        	$query2 = $db->select("SELECT Nombre_Cedente FROM  Cedente WHERE Id_Cedente = $v6");
				    foreach($query2 as $q2)
			        {
			        	$va1 = $q2["Nombre_Cedente"];
			        }
			        $query3 = $db->select("SELECT Gestion_Nivel_1 FROM  respuesta_gestion WHERE Id_Respuesta = $v3");
				    foreach($query3 as $q3)
			        {
			        	$va2 = $q3["Gestion_Nivel_1"];
			        }
			        $query4 = $db->select("SELECT Nombre FROM  Tipo_Contacto WHERE Id_TipoContacto = $v9");
				    foreach($query4 as $q4)
			        {
			        	$va3 = $q4["Nombre"];
			        }
		        	/*$query5 = $db->select("SELECT Gestion_Nivel_1 FROM  respuesta_gestion WHERE Id_Respuesta = $r1");
				    foreach($query5 as $q5)
			        {
			        	$res1 = $q5["Gestion_Nivel_1"];
			        }*/


		        	echo "<tr id='$i'>";
				    echo "<td class='text-sm'>$v2</td>";
				    echo "<td class='text-sm'><center> $v4</center></td>";
				    //echo "<td class='text-sm'><center> $v5</center></td>";
				    echo "<td class='text-sm'><center> $statusName</center></td>";
				    echo "<td class='text-sm'><center>---</center></td>";
				    echo "<td class='text-sm'><center>---</center></td>";
				    //echo "<td class='text-sm'><center>$va1</center></td>";
				    echo "<td class='text-sm'><center>$v7</center></td>";
				    echo "<td class='text-sm'><center>$v8</center></td>";
				    //echo "<td class='text-sm'><center>$va3</center></td>";
				    echo "<td class='text-sm'><center>$v10</center></td>";
				    echo '</tr>';
				}
				else
				{

		        	if($v7=='' OR $v7=='0000-00-00')
		        	{
		        		$v7 = '---';
		        		$v8 = '---';
		        	}
		        	else
		        	{
		        		$v7 = $v7;
		        		$v8 = $v8;
		        	}

		        	$query2 = $db->select("SELECT Nombre_Cedente FROM  Cedente WHERE Id_Cedente = $v6");
				    foreach($query2 as $q2)
			        {
			        	$va1 = $q2["Nombre_Cedente"];
			        }
			        /*$query3 = $db->select("SELECT Respuesta_N1 FROM  Nivel1 WHERE id = $r1");
				    foreach($query3 as $q3)
			        {
			        	$re1 = utf8_encode($q3["Respuesta_N1"]);
			        }
			        $query5 = $db->select("SELECT Respuesta_N2 FROM  Nivel2 WHERE id = $r2");
				    foreach($query5 as $q5)
			        {
			        	$re2 = utf8_encode($q5["Respuesta_N2"]);
			        }
			        $query6 = $db->select("SELECT Respuesta_N2 FROM  Nivel3 WHERE id = $r3");
				    foreach($query6 as $q6)
			        {
			        	$re3 = utf8_encode($q6["Respuesta_N2"]);
			        } */
			        $query4 = $db->select("SELECT Nombre FROM  Tipo_Contacto WHERE Id_TipoContacto = $v9");
				    foreach($query4 as $q4)
			        {
			        	$va3 = $q4["Nombre"];
			        }
				    echo "<tr id='$i'>";
				    echo "<td class='text-sm'>$v2</td>";
				    echo "<td class='text-sm'><center> $v4</center></td>";
					echo "<td class='text-sm'><center> $statusName</center></td>";
				    //echo "<td class='text-sm'><center> $v5</center></td>";
				    echo "<td class='text-sm'><center> $r1</center></td>";
				    echo "<td class='text-sm'><center> $r2</center></td>";
				    echo "<td class='text-sm'><center> $r3</center></td>";
				    //echo "<td class='text-sm'><center> $va1</center></td>";
				    echo "<td class='text-sm'><center>$v7 </center></td>";
				    echo "<td class='text-sm'><center>$v8 </center></td>";
				    echo "<td class='text-sm'><center>$factura</center></td>";
				    echo "<td class='text-sm'><center>$v10</center></td>";
				    echo '</tr>';
				}
				$i++;
	    	}
	    	echo '</tbody></table></div>';
    	}

	}
	public function mostrarPagosRut($rut)
	{
		$db = new DB();
		$this->rut=$rut;
		$q = $db->select("SELECT Rut, Fecha_Pago,Monto_Pago,Id_Cedente FROM  Pagos WHERE Rut = $this->rut");
		if(count($q)==0)
		{
			echo "Rut no registra Pagos !";
		}
		else
		{
			echo '<div class="table-responsive">';
		    echo '<table id="demo-dt-basic" class="table table-striped table-bordered" cellspacing="0" width="100%">';
		    echo '<thead>';
		    echo '<tr><tr>';
		    echo '<th class="text-sm"><center>Rut</center></th>';
		    echo '<th class="text-sm">Fecha Pago</th>';
		    echo '<th class="text-sm">Monto Pago</th>';
		    echo '<th class="text-sm">Numero Factura</th></tr>';
		    echo '</thead><tbody>';
		    $rows = $db->select("SELECT Rut, Fecha_Pago,Monto_Pago,Numero_Operacion FROM  Pagos WHERE Rut = $this->rut ");
		    foreach($rows as $row)
		    {
		    	$v1 = $row["Rut"];
		    	$v2 = $row["Fecha_Pago"];
		    	$v3 = $row["Monto_Pago"];
		    	$v4 = $row["Numero_Operacion"];
			    echo "<tr id='$i'>";
			    echo "<td class='text-sm'>$v1</td>";
			    echo "<td class='text-sm'>$v2</td>";
			    echo "<td class='text-sm'>$v3</td>";
			    echo "<td class='text-sm'>$v4</td>";
			    echo '</tr>';
			}
			echo '</tbody></table></div>';
    	}

	}

	public function mostrandoFonos($datos)
	{
		
		$this->rut=$datos['rut'];
		$idCola = $datos['cola']; 
		$colores = '';
		$db = new DB();
		$sqlColores = "SELECT color FROM SIS_Querys_Estrategias WHERE id = '".$idCola."'";
		$resuColores = $db->select($sqlColores);
		$colores = $resuColores[0]['color'];


		echo '<div class="table-responsive">';
        echo '<table id="tablaTelefonos" class="table table-striped table-bordered" cellspacing="0" width="100%">';
        echo '<thead>';
        echo '<tr><tr>';
        echo '<th class="text-sm"><center>Color</center></th>';
        echo '<th class="text-sm">Comentario</th>';
        echo '<th class="text-sm">Numero</th>';
        echo '<th class="text-sm">Fecha Carga</th>';
		echo '<th class="text-sm"><center>Llamada</center></th>';
        //echo '<th class="text-sm">Origen</th>';
        //echo '<th class="text-sm"><center>Fono Gestión</center></th>';
        echo '<th class="text-sm"><center>Llamar</center></th></tr>';
        echo '</thead><tbody>';

        //$qc = mysql_query("SELECT formato_subtel,color,fecha_carga,cedente FROM fono_cob WHERE Rut = $this->rut order by color Desc  LIMIT 10");
		//$rows = $db->select("SELECT f.formato_subtel as fono ,f.color as color,f.fecha_carga as fechaCarga,f.cedente as Cedente, c.mundo as Mundo, c.prioridad as Prioridad, f.id_fono as id FROM fono_cob f, SIS_Categoria_Fonos c WHERE f.Rut = $this->rut and f.color = c.color and c.mundo = 1 and f.vigente = 1 and CHARACTER_LENGTH(formato_subtel)=9 order by c.prioridad ASC LIMIT 10");

		// si colores viene vacio da error

		if ($colores == ''){
			$rows = $db->select("SELECT f.formato_subtel as fono,f.color as color,f.fecha_carga as fechaCarga,f.cedente as Cedente, c.mundo as Mundo, c.prioridad as Prioridad, f.id_fono as id FROM fono_cob f, SIS_Categoria_Fonos c WHERE f.Rut = $this->rut and f.color = c.color and  c.mundo = 1 and f.vigente = 1 and CHARACTER_LENGTH(formato_subtel)=9 order by c.prioridad ASC LIMIT 10");
		}else{
			$rows = $db->select("SELECT f.formato_subtel as fono,f.color as color,f.fecha_carga as fechaCarga,f.cedente as Cedente, c.mundo as Mundo, c.prioridad as Prioridad, f.id_fono as id FROM fono_cob f, SIS_Categoria_Fonos c WHERE f.Rut = $this->rut and f.color = c.color and c.color IN($colores) and  c.mundo = 1 and f.vigente = 1 and CHARACTER_LENGTH(formato_subtel)=9 order by c.prioridad ASC LIMIT 10");
			if (count($rows)==0){
				$rows = $db->select("SELECT f.formato_subtel as fono,f.color as color,f.fecha_carga as fechaCarga,f.cedente as Cedente, c.mundo as Mundo, c.prioridad as Prioridad, f.id_fono as id FROM fono_cob f, SIS_Categoria_Fonos c WHERE f.Rut = $this->rut and f.color = c.color and  c.mundo = 1 and f.vigente = 1 and CHARACTER_LENGTH(formato_subtel)=9 order by c.prioridad ASC LIMIT 10");
			}
		}

		

		
   		$i=1;
   		foreach($rows as $row)
    	{
    		$f1 = $row["fono"];
    		$c = $row["color"];
    		$g1 = $row["fechaCarga"];
    		$g2 = $row["Cedente"];
    		if($g2=='')
    		{
    			$g2 = "Cobranding";
    		}
    		else
    		{
    			$g2 = $g2;
    		}
    		$colores = $db->select("SELECT color,comentario  FROM SIS_Colores WHERE id = $c  ");

       		foreach($colores as $color)
        	{

			   	$color1 = $color["color"];
			   	$comentario = $color["comentario"];
			    echo "<tr id='$i'>";
			    echo "<td class='text-sm'><center><i class='fa fa-flag fa-lg icon-lg' style='color:$color1'></i> </center></td>";
			    echo "<td class='text-sm'> $comentario </td>";
			    echo "<td class='text-sm'><input type='hidden' id='telefono$i' value='$f1' name='telefono$i'>$f1</td>";
			    echo "<td class='text-sm'>$g1</td>";
				echo "<td class='text-sm'><center><input type='checkbox' disabled  class='fono_gestion' name='llamado$i' value='llamado$i' id='llamado$i' ></center></td>";
			    //echo "<td class='text-sm'>$g2</td>";
			   // echo "<td class='text-sm'><center><input type='checkbox' class='fono_gestion' name='fg$i' value='fg$i' id='fg$i' ></center></td>";
			    echo "<td class='text-sm'><center><button id='fono$i' class='btn btn-success btn-icon icon-lg fa fa-phone Llamar'  value='Llamar'> </button> </center></td>";

			    echo '</tr>';
			    $i++;
			}
	    }
        echo '</tbody></table></div>';
	}


	public function mostrarFono($rut,$fono)
	{
		$db = new DB();
		$this->rut=$rut;
		echo '<div class="table-responsive">';
        echo '<table id="tablaTelefonos" class="table table-striped table-bordered" cellspacing="0" width="100%">';
        echo '<thead>';
        echo '<tr><tr>';
        echo '<th class="text-sm"><center>Color</center></th>';
        echo '<th class="text-sm">Comentario</th>';
        echo '<th class="text-sm">Numero</th>';
        echo '<th class="text-sm">Fecha Carga</th>';
		echo '<th class="text-sm"><center>Llamada</center></th>';
        //echo '<th class="text-sm">Origen</th>';
        //echo '<th class="text-sm"><center>Fono Gestión</center></th>';
        echo '<th class="text-sm"><center>Llamar</center></th></tr>';
        echo '</thead><tbody>';

        //$qc = mysql_query("SELECT formato_subtel,color,fecha_carga,cedente FROM fono_cob WHERE Rut = $this->rut order by color Desc  LIMIT 10");
		$rows = $db->select("SELECT f.formato_subtel as fono,f.color as color,f.fecha_carga as fechaCarga,f.cedente as cedente, c.mundo, c.prioridad, f.id_fono FROM fono_cob f, SIS_Categoria_Fonos c WHERE f.formato_subtel = $fono and  f.Rut = $this->rut and f.color = c.color and c.mundo = 1 and f.vigente = 1 LIMIT 1");
   		$i=1;
   		foreach($rows as $row)
    	{
    		$f1 = $row["fono"];
    		$c = $row["color"];
    		$g1 = $row["fechaCarga"];
    		$g2 = $row["cedente"];
    		if($g2=='')
    		{
    			$g2 = "Cobranding";
    		}
    		else
    		{
    			$g2 = $g2;
    		}
    		$colores = mysql_query("SELECT color,comentario  FROM SIS_Colores WHERE id = $c  ");

       		foreach($colores as $color)
        	{

			   	$color1 = $color["color"];
			   	$comentario = $color["comentario"];
			    echo "<tr id='$i'>";
			    echo "<td class='text-sm'><center><i class='fa fa-flag fa-lg icon-lg' style='color:$color1'></i> </center></td>";
			    echo "<td class='text-sm'>$comentario</td>";
			    echo "<td class='text-sm'><input type='hidden' id='telefono$i' value='$f1' name='telefono$i'>$f1</td>";
			    echo "<td class='text-sm'>$g1</td>";
				echo "<td class='text-sm'><center><input type='checkbox' disabled  class='fono_gestion' name='llamado$i' value='llamado$i' id='llamado$i' ></center></td>";
			    //echo "<td class='text-sm'>$g2</td>";
			   // echo "<td class='text-sm'><center><input type='checkbox' class='fono_gestion' name='fg$i' value='fg$i' id='fg$i' ></center></td>";
			    echo "<td class='text-sm'><center><button id='fono$i' class='btn btn-danger btn-icon icon-lg fa fa-phone CortarPredictivo'  value='Cortar'> </button> </center></td>";

			    echo '</tr>';
			    $i++;
			}
	    }
        echo '</tbody></table></div>';
	}

	public function insertarFonos($rut,$fono_discado_nuevo)
	{
		$db = new DB();
		$this->rut=$rut;
		$this->fono_discado_nuevo=$fono_discado_nuevo;
		$fecha_carga = date("Y-m-d");
		$db->query("INSERT INTO fono_cob(Rut,formato_subtel,color,formato_dial,numero_telefono,fecha_carga,cedente) VALUES ('$this->rut','$this->fono_discado_nuevo',100,'$this->fono_discado_nuevo','$this->fono_discado_nuevo','$fecha_carga','foco' ) ON DUPLICATE KEY UPDATE color = 100, fecha_carga = '$fecha_carga', cedente = 'foco'");
		$this->mostrandoFonos($this->rut);
	}

	public function insertarFonoCola($idCola, $fono, $rut){
		$db = new DB();
		$sql = "SELECT Asterisk_Discador_Cola.Cola as cola, Asterisk_All_Queues.Queue as queue FROM Asterisk_Discador_Cola inner join Asterisk_All_Queues on Asterisk_All_Queues.id_discador = Asterisk_Discador_Cola.id WHERE Asterisk_Discador_Cola.id = '".$idCola."'";
		$resultado = $db->select($sql);
		$cola = $resultado[0]['cola'];
		$queue = $resultado[0]['queue'];
		$colaDR = "DR_".$queue.$cola;
		$Sql = "INSERT INTO ".$colaDR."(Fono,Rut,Cedente) VALUES ('".$fono."','".$rut."','".$_SESSION['cedente']."')";
		$db -> query($Sql);
		// $Sql = "UPDATE ".$Asignacion." set orden = '".$contador."' WHERE id='".$id."'";
	}


	public function eliminarBridge(){
		$db = new DB();
		$anexo = $_SESSION['anexo_foco'];
		$sql = "DELETE FROM Asterisk_Bridge WHERE Anexo = '".$anexo."'";
		$db -> query($sql);
	}

	public function insertarDireccion($rut,$direccion_nuevo)
	{
		$db = new DB();
		$this->rut=$rut;
		$this->direccion_nuevo=$direccion_nuevo;
		$db->query("INSERT INTO Direcciones(Rut,Direccion) VALUES ('$this->rut','$this->direccion_nuevo')");
		echo '<div class="table-responsive">';
        echo '<table id="demo-dt-basic" class="table table-striped table-bordered" cellspacing="0" width="100%">';
        echo '<thead>';
        echo '<tr><tr>';
        echo '<th class="text-sm"><center>Direccion</center></th></tr>';
        echo '</thead><tbody>';
        $rows = $db->select("SELECT Direccion  FROM  Direcciones WHERE Rut = $this->rut ");
   		$i = 1;
		foreach($rows as $row)
    	{
    		$d1 = $row["Direccion"];
		    echo "<tr id='$i'>";
		    echo "<td class='text-sm'>$d1</td>";
		    echo '</tr>';
			$i++;
	    }
        echo '</tbody></table></div>';

	}
	public function validarRut($rut,$cedente)
	{
		$db = new DB();
		$rowsCedente = $db->select("SELECT Rut,Id_Cedente FROM Persona WHERE Rut = $rut AND  FIND_IN_SET($cedente,Id_Cedente)");
		$rows = $db->select("SELECT Nombre_Completo FROM Persona WHERE Rut = $rut");
		foreach($rows as $row)
		{
			$nombre = $row['Nombre_Completo'];
		}
		if(count($rowsCedente)==0)
		{
			$array = array('uno' => 0, 'dos' => utf8_encode($nombre));

		}
		else
		{
			$array = array('uno' => 1, 'dos' => utf8_encode($nombre));
		}

		echo json_encode($array);

	}
	public function verCargo()
	{
		$db = new DB();
		echo '<div class="row">';
		echo '<div class="col-md-12">';
		echo '<form class="form-horizontal">';
		echo '<div class="form-group">';
		echo '<label class="col-md-4 control-label" for="name">Nombre</label>';
		echo '<div class="col-md-4" lateral>';
		echo '<input id="nombre" name="nombre" type="text" class="form-control input-md lateral2"/>';
		echo '</div>';
		echo '</div>';
		echo '<div class="form-group">';
		echo '<label class="col-md-4 control-label" for="name">Nuevo Correo</label>';
		echo '<div class="col-md-4">';
		echo '<input id="correo_nuevo" name="name" type="text" placeholder="" class="form-control input-md" >';
		echo '</div>';
		echo '</div>';
		echo '<div class="form-group">';
		echo '<label class="col-md-4 control-label" for="name">Cargo</label>';
		echo '<div class="col-md-4 ">';
		echo "<select class='select1 col-md-4 lateral' id='cargo' name='cargo' >";
        $rows=$db->select("SELECT id,Cargo FROM Mail_Cargo");
       	echo "<option value='0'>Seleccione</option>";
        foreach($rows as $row)
        {
        	echo "<option value='".$row["id"]."'>"; echo utf8_encode($row["Cargo"]); echo "</option>";
        }
        echo "</select>";
		echo '</div>';
		echo '</div>';
		echo '<div class="form-group">';
		echo '<label class="col-md-4 control-label" for="name">Tipo Uso</label>';
		echo '<div class="col-md-4">';
		echo '<select class="selectpicker" multiple title="Seleccione los items..."  name="uso" id="uso" data-width="80%">';
        $rows=$db->select("SELECT id,Uso FROM Mail_Uso");
        foreach($rows as $row)
        {

           echo "<option value='".$row["id"]."'>"; echo utf8_encode($row["Uso"]); echo "</option>";

        }
        echo '</select>';
		echo '</div>';
		echo '</div>';
		echo '</form>';
		echo '</div>';
		echo '</div>';
	}
	public function verCargo2()
	{
		$db = new DB();
		echo '<div class="row">';
		echo '<div class="col-md-12">';
		echo '<form class="form-horizontal">';
		echo '<div class="form-group">';
		echo '<label class="col-md-4 control-label" for="name">Nombre</label>';
		echo '<div class="col-md-4" lateral>';
		echo '<input id="nombre_cc" name="nombre_cc" type="text" class="form-control input-md lateral2"/>';
		echo '</div>';
		echo '</div>';
		echo '<div class="form-group">';
		echo '<label class="col-md-4 control-label" for="name">Nuevo Correo</label>';
		echo '<div class="col-md-4">';
		echo '<input id="correo_nuevo_cc" name="name" type="text" placeholder="" class="form-control input-md" >';
		echo '</div>';
		echo '</div>';
		echo '<div class="form-group">';
		echo '<label class="col-md-4 control-label" for="name">Cargo</label>';
		echo '<div class="col-md-4 ">';
		echo "<select class='select1 col-md-4 lateral' id='cargo_cc' name='cargo' >";
		$rows=$db->select("SELECT id,Cargo FROM Mail_Cargo");
       	echo "<option value='0'>Seleccione</option>";
        foreach($rows as $row)
        {
        	echo "<option value='".$row["id"]."'>"; echo utf8_encode($row["Cargo"]); echo "</option>";
        }
        echo "</select>";
		echo '</div>';
		echo '</div>';
		echo '<div class="form-group">';
		echo '<label class="col-md-4 control-label" for="name">Tipo Uso</label>';
		echo '<div class="col-md-4">';
		echo '<select class="selectpicker" multiple title="Seleccione los items..."  name="uso" id="uso_cc" data-width="80%">';
        $rows=$db->select("SELECT id,Uso FROM Mail_Uso");
        foreach($rows as $row)
        {

           echo "<option value='".$row["id"]."'>"; echo utf8_encode($row["Uso"]); echo "</option>";

        }
        echo '</select>';
		echo '</div>';
		echo '</div>';
		echo '</form>';
		echo '</div>';
		echo '</div>';
	}
	public function insertarCorreo($rut,$correo_nuevo,$cargo,$uso,$nombre)
	{
		$db = new DB();
		$this->rut=$rut;
		$this->correo_nuevo=$correo_nuevo;
		$this->cargo=$cargo;
		$this->uso=$uso;
		$this->nombre=$nombre;

		$db->query("INSERT INTO Mail(rut,correo_electronico,Cargo,Tipo_Uso,Nombre) VALUES ('$this->rut','$this->correo_nuevo','$this->cargo','$this->uso','$this->nombre')");
		$this->mostrarCorreoRut($this->rut);

	}
	public function insertarCorreocc($rut,$correo_nuevo,$cargo,$uso,$nombre)
	{
		$db = new DB();
		$this->rut=$rut;
		$this->correo_nuevo=$correo_nuevo;
		$this->cargo=$cargo;
		$this->uso=$uso;
		$this->nombre=$nombre;

		$db->query("INSERT INTO Mail_CC(rut,correo_electronico,Cargo,Tipo_Uso,Nombre) VALUES ('$this->rut','$this->correo_nuevo','$this->cargo','$this->uso','$this->nombre')");
		$this->mostrarCorreoRutcc($this->rut);

	}
	public function mostrarDireccionRut($rut,$pantalla = "crm")
	{
		switch($pantalla){
			case "crm":
				$db = new DB();
			break;
			case "predictivo":
				$db = new DB("discador");
			break;
		}
		$this->rut=$rut;
		$q = $db->select("SELECT Direccion FROM  Direcciones WHERE Rut = $this->rut ");
		if(count($q)==0)
		{
			echo "Rut no registra Direcciones , Haga Click en el Boton <b>+ </b>Para Agregar una.";
		}
		else
		{
			echo '<div class="table-responsive">';
	        echo '<table id="demo-dt-basic" class="table table-striped table-bordered" cellspacing="0" width="100%">';
	        echo '<thead>';
	        echo '<tr><tr>';

	        echo '<th class="text-sm">Direccion</th></tr>';
	        echo '</thead><tbody>';
		    $q1 = $db->select("SELECT Direccion FROM  Direcciones WHERE Rut = $this->rut ");
		    $i = 1;
			foreach($q1 as $row)
	        {
	        	$v1 = $row["Direccion"];

			    echo "<tr id='$i'>";
			    echo "<td class='text-sm'>$v1</td>";
			    echo '</tr>';
				$i++;
	    	}
	    	echo '</tbody></table></div>';
    	}

	}

	public function mostrarCorreoRut($rut,$pantalla = "crm")
	{
		switch($pantalla){
			case "crm":
				$db = new DB();
			break;
			case "predictivo":
				$db = new DB("discador");
			break;
		}
		$this->rut=$rut;
		$q = $db->select("SELECT correo_electronico FROM  Mail WHERE rut = $this->rut ");
		if(count($q)==0)
		{
			echo "Rut no registra Correos Electrónicos , Haga Click en el Boton <b>+ </b>Para Agregar uno nuevo.";
		}
		else
		{
			echo '<div class="table-responsive">';
	        echo '<table id="demo-dt-basic" class="table table-striped table-bordered" cellspacing="0" width="100%">';
	        echo '<thead>';
	        echo '<tr><tr>';
			switch($_SESSION['tipoSistema']){
				case "1":
				case "2":
					echo '<th class="text-sm"></th>';
				break;
			}
	        echo '<th class="text-sm">Correo</th>';
	        echo '<th class="text-sm">Nombre</th>';
	        echo '<th class="text-sm">Observación</th>';
	        echo '<th class="text-sm"><center>Cargo</center></th>';
	        echo '<th class="text-sm"><center>Tipo Uso</center></th>';
	        //echo '<!--<th class="text-sm"><center>Enviar</center></th>-->';
	        echo '</tr>';
	        echo '</thead><tbody>';
	        $i=1;
	        $query1 = $db->select("SELECT correo_electronico,Cargo,Tipo_Uso,id_mail,Nombre,Observacion FROM  Mail WHERE rut = $this->rut ");
	   		foreach($query1 as $q1)
	    	{
	    		$d1 = $q1["correo_electronico"];
	    		$d2 = $q1["Cargo"];
	    		$d3 = $q1["Tipo_Uso"];
	    		$d4 = $q1["id_mail"];
	    		$d5 = $q1["Nombre"];
	    		$d6 = $q1["Observacion"];
			    echo "<tr id='$i' class='$d4'>";
				switch($_SESSION['tipoSistema']){
					case "1":
					case "2":
						echo '<td class="text-sm" style="text-align: center;"><label class="form-checkbox form-normal form-primary inputCheckCorreo" style="margin: 10px 0px;"><input type="checkbox"></label></td>';
					break;
				}
			    echo "<td class='text-sm'><input type='text' class='correo_cambiar text6 NombreCorreo' value='$d1' id='correo$i'></td>";
			    if($d2 != ""){
					$query2 = $db->select("SELECT Cargo FROM  Mail_Cargo WHERE id = $d2");
					foreach($query2 as $q2)
					{
						$c1 = $q2["Cargo"];
					}
				}else{
					$c1 = "";
				}
				if($d3 != ""){
					$query3 = $db->select("SELECT Uso FROM  Mail_Uso WHERE id = $d3");
					foreach($query3 as $q3)
					{
						$c2 = $q3["Uso"];
					}
				}else{
					$c2 = "";
				}
			    echo "<td class='text-sm'><center><input type='text' class='correo_cambiar text6' value='$d5' id='nombre$i'></center></td>";
			    echo "<td class='text-sm'><center><input type='text' class='correo_cambiar text6' value='$d6' id='obs$i'></center></td>";
			    echo "<td class='text-sm'><center>$c1</center></td>";
			    echo "<td class='text-sm'><center>$c2</center></td>";
			    //echo "<!--<td class='text-sm'><center><input type='checkbox' class='adjuntar' name='l$i' value='l$i' id='l$i' ></center></td>-->";
			    echo '</tr>';
			    $i++;
		    }
	        echo '</tbody></table></div><!--<button class="btn btn-primary adjuntar_boton" disabled = "disabled"  id="enviar_factura">Enviar</button>-->';
    	}

	}
	public function mostrarCorreoRutcc($rut)
	{
		$db = new DB();
		$this->rut=$rut;
		$q = $db->select("SELECT correo_electronico FROM  Mail_CC ");
		if(count($q)==0)
		{
			echo "Rut no registra Correos Electrónicos , Haga Click en el Boton <b>+ </b>Para Agregar uno nuevo.";
		}
		else
		{
			echo '<div class="table-responsive">';
	        echo '<table id="demo-dt-basic" class="table table-striped table-bordered" cellspacing="0" width="100%">';
	        echo '<thead>';
	        echo '<tr><tr>';
	        echo '<th class="text-sm">Correo</th>';
	        echo '<th class="text-sm">Nombre</th>';
	        echo '<th class="text-sm">Observación</th>';
	        echo '<th class="text-sm"><center>Cargo</center></th>';
	        echo '<th class="text-sm"><center>Tipo Uso</center></th>';
	        echo '<th class="text-sm"><center>Enviar</center></th>';
	        echo '</tr>';
	        echo '</thead><tbody>';
	        $k=1;
	        $rows = $db->select("SELECT correo_electronico,Cargo,Tipo_Uso,id_mail,Nombre,Observacion FROM  Mail_CC ");
	   		foreach($rows as $row)
	    	{
	    		$d1 = $row["correo_electronico"];
	    		$d2 = $row["Cargo"];
	    		$d3 = $row["Tipo_Uso"];
	    		$d4 = $row["id_mail"];
	    		$d5 = $row["Nombre"];
	    		$d6 = $row["Observacion"];
			    echo "<tr id='$k' class='$d4'>";
			    echo "<td class='text-sm'><input type='text' class='correo_cambiar_cc text6' value='$d1' id='correo_cc$k'></td>";
			    $rowsCargo = $db->select("SELECT Cargo FROM  Mail_Cargo WHERE id = $d2");
				foreach($rowsCargo as $rowCargo)
			    {
			       	$c1 = $rowCargo["Cargo"];
			    }
			    $rowsUso = $db->select("SELECT Uso FROM  Mail_Uso WHERE id = $d3");
				foreach($rowsUso as $rowUso)
			    {
			       	$c2 = $rowUso["Uso"];

			    }
			    echo "<td class='text-sm'><center><input type='text' class='correo_cambiar_cc text6' value='$d5' id='nombre_cc$k'></center></td>";
			    echo "<td class='text-sm'><center><input type='text' class='correo_cambiar_cc text6' value='$d6' id='obs_cc$k'></center></td>";
			    echo "<td class='text-sm'><center>$c1</center></td>";
			    echo "<td class='text-sm'><center>$c2</center></td>";
			    echo "<td class='text-sm'><center><input type='checkbox' class='adjuntar_cc' name='l_cc$k' value='l_cc$k' id='l_cc$k' ></center></td>";
			    echo '</tr>';
			    $k++;
		    }
	        echo '</tbody></table></div>';
    	}

	}
	public function mostrarDirecciones($rut)
	{
		$db = new DB();
		$this->rut=$rut;
		echo '<div class="table-responsive">';
        echo '<table id="demo-dt-basic" class="table table-striped table-bordered" cellspacing="0" width="100%">';
        echo '<thead>';
        echo '<tr><tr>';
        echo '<th class="text-sm">Direccion</th>';
        echo '<th class="text-sm"><center></center></th>';
        echo '<th class="text-sm"><center></center></th></tr>';
        echo '</thead><tbody>';
	    $rows = $db->select("SELECT Direccion FROM Direcciones  WHERE Rut = $this->rut");
	    $i = 1;
		foreach($rows as $row)
	   	{
	        $d = $row["Direccion"];
            echo "<tr id='$i'>";
            echo "<td class='text-sm'>$d</td>";
            echo "<td class='text-sm'><center></center></td>";
            echo "<td class='text-sm'><center></center></td></td>";
            echo '</tr>';
			$i++;
		}
        echo '</tbody></table></div>';

	}
	public function nivel_rapido($cedente)
	{
		$db = new DB();
		$this->cedente=$cedente;
		echo "<select class='select1' id='respuesta' name='respuesta'>";
        $rows=$db->select("SELECT n3.Respuesta_N3 as Respuesta_Rapida, n3.id as respuesta_n3
							 FROM Nivel3 n3, Respuesta_Rapida r
							 WHERE r.Respuesta_Nivel3 = n3.id and FIND_IN_SET($this->cedente,r.Id_Cedente)");
       	echo "<option value='0'>Seleccione</option>";
        foreach($rows as $row)
        {
        	echo "<option value='".$row["respuesta_n3"]."'>"; echo utf8_encode($row["Respuesta_Rapida"]); echo "</option>";
        }
        echo "</select>";
	}
	public function nivel1($datos)
	{
		$db = new DB();
		$this->cedente=$datos['cedente'];

		if ($_SESSION['inbound'] == 0){ // no muestro los inbound
			$rows=$db->select("SELECT Id,Respuesta_N1 FROM Nivel1 WHERE FIND_IN_SET($this->cedente,Id_Cedente) AND Respuesta_N1 != 'INBOUND' ");
		}else{
			if (isset($datos['busqueda'])) { // si entra aca estoy en manual
				if ($datos['busqueda'] == 2){ // si entra aca estoy buscando por rut 
					$rows=$db->select("SELECT Id,Respuesta_N1 FROM Nivel1 WHERE FIND_IN_SET($this->cedente,Id_Cedente) ");
				}else{
					$rows=$db->select("SELECT Id,Respuesta_N1 FROM Nivel1 WHERE FIND_IN_SET($this->cedente,Id_Cedente) AND Respuesta_N1 != 'INBOUND' ");
				}

			}else{
				// predictivo
				$rows=$db->select("SELECT Id,Respuesta_N1 FROM Nivel1 WHERE FIND_IN_SET($this->cedente,Id_Cedente) AND Respuesta_N1 != 'INBOUND' ");
			}
		}
		

		echo "<select class='select1' id='seleccione_nivel1' name='seleccione_nivel1'>";        
       	echo "<option value='0'>Seleccione</option>";
        foreach($rows as $row)
        {
        	echo "<option value='".$row["Id"]."'>"; echo utf8_encode($row["Respuesta_N1"]); echo "</option>";
        }
        echo "</select>";
	}
	public function nivel2($nivel2)
	{
		$db = new DB();
		$this->nivel2=$nivel2;
		echo "<select class='select1' id='seleccione_nivel2' name='seleccione_nivel2'>";
        $rows=$db->select("SELECT Id,Respuesta_N2 FROM Nivel2 WHERE Id_Nivel1 = $this->nivel2 ");
       	echo '<option value="0">Seleccione</option>';
        foreach($rows as $row)
        {
        	echo "<option value='".$row["Id"]."'>"; echo utf8_encode($row["Respuesta_N2"]); echo  "</option>";

        }
        echo "</select>";

	}
	public function nivel3($nivel3)
	{
		$db = new DB();
		$this->nivel3=$nivel3;

		echo "<select class='select1' id='seleccione_nivel3' name='seleccione_nivel3'>";
        $rows=$db->select("SELECT id,Respuesta_N3 FROM Nivel3 WHERE $this->nivel3 = Id_Nivel2 ");
       	echo '<option value="0">Seleccione</option>';
        foreach($rows as $row)
        {
        	echo "<option value='".$row["id"]."'>"; echo utf8_encode($row["Respuesta_N3"]); echo "</option>";
        }
        echo "</select>";

        $rows=$db->select("SELECT id,Id_TipoGestion FROM Nivel3 WHERE  $this->nivel3 = Id_Nivel2 ");
        foreach($rows as $row)
        {
        	echo "<input type='hidden' id='tipo_gestion' name='tipo_gestion' value='".$row["id"]."'>";
        	echo "<input type='hidden' id='tipo_gestion_final' name='tipo_gestion_final' value='".$row["Id_TipoGestion"]."'>";
        }
	}
	public function nivel4($id_tipo,$cortar_valor,$rut)
	{
		$db = new DB();
		$this->id_tipo=$id_tipo;
		$this->cortar_valor=$cortar_valor;
		if($this->id_tipo == 5) // quite 37
		{
			if ($_SESSION['tipoFactura'] == 1){
					echo '<div class="col-sm-4">';
					echo '<div class="form-group" id="date-range">';
					echo '<label class="control-label">Fecha Compromiso</label>';
					//echo '<div class="col-sm-4" id="date-range">';
					echo '<div class="input-daterange input-group" id="datepicker">';
							echo '<input id="fecha_compromiso" name="fecha_compromiso" placeholder="2017-07-17" class="select1 form-control">';
					echo '</div>';
					echo '</div>';
					echo '</div>';
					//echo '</div>';


					echo '<div class="col-sm-4">';
					echo '<div class="form-group">';
					echo '<label class="control-label">Monto Compromiso</label>';
					echo '<input type="number" class="select1" id="monto_compromiso" name="monto_compromiso" >';
					echo '</div>';
					echo '</div>';
					echo '<div class="col-sm-4">';
					echo '<div class="form-group">';
					echo '<label class="control-label">Facturas</label>';
					echo "<select class='selectpicker' data-width='100%' multiple id='facturas' name='facturas'>";
					$rows=$db->select("SELECT Numero_Factura FROM Deuda WHERE Rut = '".$rut."' AND Id_Cedente = '".$_SESSION['cedente']."'");
					foreach($rows as $row)
					{
						echo "<option value='".$row["Numero_Factura"]."'>"; echo utf8_encode($row["Numero_Factura"]); echo "</option>";
					}
					echo "</select>";
					echo '</div>';
					echo '</div>';

					echo '<div class="col-sm-4">';
					echo '<div class="form-group" id="date-range">';
					echo '<label class="control-label">Fecha de Agendamiento</label>';
					echo '<div class="input-daterange input-group" id="datepicker">';
							echo '<input id="fecha_agendamiento" name="fecha_agendamiento" placeholder="2017-07-17" class="select1 form-control">';
					echo '</div>';
					echo '</div>';
					echo '</div>';
					echo '<div class="col-sm-4">';
					echo '<div class="form-group">';
					echo '<label class="control-label">Hora de agendamiento</label>';
					echo "<select class='selectpicker' data-width='100%' id='hora_agendamiento' name='hora_agendamiento'>";
						echo "<option value='0'>Seleccione</option>";
						echo "<option value='09:00:00'>09:00:00</option>";
						echo "<option value='10:00:00'>10:00:00</option>";
						echo "<option value='11:00:00'>11:00:00</option>";
						echo "<option value='12:00:00'>12:00:00</option>";
						echo "<option value='13:00:00'>13:00:00</option>";
						echo "<option value='14:00:00'>14:00:00</option>";
						echo "<option value='15:00:00'>15:00:00</option>";
						echo "<option value='16:00:00'>16:00:00</option>";
						echo "<option value='17:00:00'>17:00:00</option>";
						echo "<option value='18:00:00'>18:00:00</option>";
						echo "<option value='19:00:00'>19:00:00</option>";
					echo "</select>";
					echo '</div>';
					echo '</div>';

					echo '<div class="col-sm-8">';
					echo '<div class="form-group">';
					echo '<label class="control-label">Observación</label>';
					echo '<textarea id="comentario" name="comentario" class="select1" ></textarea>';
					echo '</div>';
					echo '</div>';
					echo '<div class="col-sm-4">';
					echo '<div class="form-group">';
					echo '<label class="control-label">Guardar Gestión</label>';
					if($this->cortar_valor == 1)
					{
						echo '<input type="submit" class="btn btn-primary btn-block" value="Guardar"  id="guardar">';
					}
					else
					{
						echo '<input type="submit" class="btn btn-primary btn-block" value="Guardar"  id="guardar">';
					}
					echo '</div>';
					echo '</div>';
						
			}else{
					echo '<div class="row">';
					echo '<div class="col-sm-4">';
					echo '<div class="form-group" id="date-range">';
					echo '<label class="control-label">Fecha Compromiso</label>';
					//echo '<div class="col-sm-4" id="date-range">';
					echo '<div class="input-daterange input-group" id="datepicker">';
							echo '<input id="fecha_compromiso" name="fecha_compromiso" placeholder="2017-05-17" class="select1 form-control">';
					echo '</div>';
					echo '</div>';
					echo '</div>';

					echo '<div class="col-sm-4">';
					echo '<div class="form-group">';
					echo '<label class="control-label">Monto Compromiso</label>';
					echo '<input type="number" class="select1" id="monto_compromiso" name="monto_compromiso" >';
					echo '</div>';
					echo '</div>';
					echo '</div>';
					/*echo '<div class="col-sm-4">';
					echo '<div class="form-group">';
					echo '</div>';
					echo '</div>';*/
					
					echo '<div class="row">';
					echo '<div class="col-sm-4">';
					echo '<div class="form-group" id="date-range">';
					echo '<label class="control-label">Fecha de Agendamiento</label>';
					echo '<div class="input-daterange input-group" id="datepicker">';
							echo '<input id="fecha_agendamiento" name="fecha_agendamiento" placeholder="2017-07-17" class="select1 form-control">';
					echo '</div>';
					echo '</div>';
					echo '</div>';
					echo '<div class="col-sm-4">';
					echo '<div class="form-group">';
					echo '<label class="control-label">Hora de agendamiento</label>';
					echo "<select class='selectpicker' data-width='100%' id='hora_agendamiento' name='hora_agendamiento'>";
						echo "<option value='0'>Seleccione</option>";
						echo "<option value='09:00:00'>09:00:00</option>";
						echo "<option value='10:00:00'>10:00:00</option>";
						echo "<option value='11:00:00'>11:00:00</option>";
						echo "<option value='12:00:00'>12:00:00</option>";
						echo "<option value='13:00:00'>13:00:00</option>";
						echo "<option value='14:00:00'>14:00:00</option>";
						echo "<option value='15:00:00'>15:00:00</option>";
						echo "<option value='16:00:00'>16:00:00</option>";
						echo "<option value='17:00:00'>17:00:00</option>";
						echo "<option value='18:00:00'>18:00:00</option>";
						echo "<option value='19:00:00'>19:00:00</option>";
					echo "</select>";
					echo '</div>';
					echo '</div>';
					echo '</div>';

					echo '<div class="row">';
					echo '<div class="col-sm-8">';
					echo '<div class="form-group">';
					echo '<label class="control-label">Observación</label>';
					echo '<textarea id="comentario" name="comentario" class="select1" ></textarea>';
					echo '</div>';
					echo '</div>';
					echo '<div class="col-sm-4">';
					echo '<div class="form-group">';
					echo '<label class="control-label">Guardar Gestión</label>';
					if($this->cortar_valor == 1)
					{
						echo '<input type="submit" class="btn btn-primary btn-block" value="Guardar"  id="guardar">';
					}
					else
					{
						echo '<input type="submit" class="btn btn-primary btn-block" value="Guardar"  id="guardar">';
					}
					echo '</div>';
					echo '</div>';
					echo '</div>';
				}	

		}
		else if($this->id_tipo == 136)
		{

	        echo '<div class="col-sm-4">';
            echo '<div class="form-group">';
            echo '<label class="control-label">Fecha Retiro</label>';
            echo '<div id="demo-dp-txtinput">';
            echo '<div class="input-group date">';
            echo '<input type="date" class="form-control" name="fecha_compromiso" id="fecha_compromiso">';
            echo '<span class="input-group-addon"><i class="fa fa-calendar "></i></span>';
            echo '</div>';
            echo '</div>';
	        echo '</div>';
	        echo '</div>';
	        echo '<div class="col-sm-12">';
	        echo '<div class="form-group">';
	        echo '<label class="control-label">Observación</label>';
	        echo '<textarea id="comentario" name="comentario" class="select1" ></textarea>';
	        echo '</div>';
	        echo '</div>';
	        echo '<div class="col-sm-4">';
	        echo '<div class="form-group">';
	        echo '<label class="control-label">Guardar Gestión</label>';
	        if($this->cortar_valor == 1)
	        {
	        	echo '<input type="submit" class="btn btn-primary btn-block" value="Guardar"  id="guardar">';
	        }
	        else
	        {
	        	echo '<input type="submit" class="btn btn-primary btn-block" value="Guardar"  id="guardar">';
	        }
	        echo '</div>';
	        echo '</div>';
		}
		else
		{

	        echo '<div class="col-sm-4">';
			echo '<div class="form-group" id="date-range">';
			echo '<label class="control-label">Fecha de Agendamiento</label>';
			echo '<div class="input-daterange input-group" id="datepicker">';
					echo '<input id="fecha_agendamiento" name="fecha_agendamiento" placeholder="2017-07-17" class="select1 form-control">';
			echo '</div>';
			echo '</div>';
			echo '</div>';
			echo '<div class="col-sm-4">';
			echo '<div class="form-group">';
			echo '<label class="control-label">Hora de agendamiento</label>';
			echo "<select class='selectpicker' data-width='100%' id='hora_agendamiento' name='hora_agendamiento'>";
				echo "<option value='0'>Seleccione</option>";
				echo "<option value='09:00:00'>09:00:00</option>";
				echo "<option value='10:00:00'>10:00:00</option>";
				echo "<option value='11:00:00'>11:00:00</option>";
				echo "<option value='12:00:00'>12:00:00</option>";
				echo "<option value='13:00:00'>13:00:00</option>";
				echo "<option value='14:00:00'>14:00:00</option>";
				echo "<option value='15:00:00'>15:00:00</option>";
				echo "<option value='16:00:00'>16:00:00</option>";
				echo "<option value='17:00:00'>17:00:00</option>";
				echo "<option value='18:00:00'>18:00:00</option>";
				echo "<option value='19:00:00'>19:00:00</option>";
			echo "</select>";
			echo '</div>';
			echo '</div>';

			echo '<div class="col-sm-8">';
	        echo '<div class="form-group">';
	        echo '<label class="control-label">Observación</label>';
	        echo '<textarea id="comentario" name="comentario" class="select1" ></textarea>';
	        echo '</div>';
	        echo '</div>';
	        echo '<div class="col-sm-4">';
	        echo '<div class="form-group">';
	        echo '<label class="control-label">Guardar Gestión</label>';
	        if($this->cortar_valor == 1)
	        {
	        	echo '<input type="submit" class="btn btn-primary btn-block" value="Guardar"  id="guardar">';
	        }
	        else
	        {
	        	echo '<input type="submit" class="btn btn-primary btn-block" value="Guardar"  id="guardar">';
	        }
	        echo '</div>';
	        echo '</div>';
		}

	}

	public function datosNivel($idNivel,$nivel){
		$db = new DB();
		switch ($nivel) {
    		case 1:
        	$sql = "SELECT Respuesta_N1 FROM Nivel1 WHERE Id = '$idNivel'";
			break;
    		case 2:
        	$sql = "SELECT Respuesta_N2 FROM Nivel2 WHERE id = '$idNivel'";
			break;
    		case 3:
        	$sql = "SELECT Respuesta_N3, P1, P2, P3, P4, Id_TipoGestion, Ponderacion,Peso FROM Nivel3 WHERE id = '$idNivel'";
			break;
		}

		$row = $db->select($sql);
		return $row[0];
	}

	public function sumarSegundoFecha($fecha){
		$fecha = date($fecha); 	
		$fecha = strtotime($fecha) + 1;		
		return date('Y-m-d H:i:s',$fecha);
	}

	public function colorFono($fono, $rut, $tipoGestion){
		$db = new DB();
		$sql = "SELECT f.color, c.prioridad, c.tipo_contacto FROM fono_cob as f, SIS_Categoria_Fonos as c WHERE f.formato_subtel = '".$fono."' and f.Rut = '".$rut."' and c.color = f.color";
		$result = $db -> select($sql);
		// prioridad actual del fono
		$prioridadFono = $result[0]["prioridad"];

		$sqlNuevoContacto = "SELECT prioridad, color FROM SIS_Categoria_Fonos WHERE tipo_contacto = '".$tipoGestion."'";
		$resultContacto = $db -> select($sqlNuevoContacto);
		// prioridad del tipo gestion
		$prioridadGestion = $resultContacto[0]["prioridad"];

		if ($prioridadFono > $prioridadGestion){
			// cambio color
			$updateColorFono = "UPDATE fono_cob SET color = '".$resultContacto[0]['color']."' WHERE formato_subtel = '".$fono."' and Rut = '".$rut."'";
			$db -> query($updateColorFono);
		}
	}

	public function insertar1($nivel1,$nivel2,$nivel3,$comentario,$fecha_gestion,$hora_gestion,$rut,$fono_discado,$tipo_gestion,$cedente,$usuario_foco,$lista,$fechaCompromiso,$montoCompromiso,$tiempoLlamada,$NombreGrabacion,$asignacion,$origen,$facturas,$fechaAgendamiento,$horaAgendamiento,$Habla)
	{
		$db = new DB();
		$this->usuario_foco=$usuario_foco;
		$new_user = "Foco - ".$this->usuario_foco;
		$this->nivel1=$nivel1;
		$this->nivel2=$nivel2;
		$this->nivel3=$nivel3;
		$this->comentario=$comentario;
		$this->fecha_gestion=$fecha_gestion;
		$this->hora_gestion=$hora_gestion;
		$this->rut=$rut;
		$this->fono_discado=$fono_discado;
		$this->tipo_gestion=$tipo_gestion;
		$this->cedente=$cedente;
		$this->lista=$lista;
		$this->fechaCompromiso=$fechaCompromiso;
		$this->montoCompromiso=$montoCompromiso;
		$fechahora = $this->fecha_gestion." ".$this->hora_gestion;
		$fechaAgenda = $fechaAgendamiento." ".$horaAgendamiento;
		if($Habla!=''){
			$Habla = str_replace("undefined", "", $Habla);
			$db ->query("INSERT INTO Transcripciones(Rut, Fecha, Hora,Transcripcion,Usuario) VALUES ('$rut','$fecha_gestion','$hora_gestion','$Habla','$usuario_foco')");
		}
		

		$rowNivel1 = $this->datosNivel($this->nivel1,1);
		$rowNivel2 = $this->datosNivel($this->nivel2,2);
		$rowNivel3 = $this->datosNivel($this->nivel3,3);

		if ($_SESSION['tipoFactura'] == 1){
			// Entro aca si el cedente es de tipo factura
			$Arrayfacturas = explode(",",$facturas);   
            foreach($Arrayfacturas as $numFactura){

				$fechaCom = $this->fechaCompromiso." ".$this->hora_gestion;
				
				$this->hora_gestion = $this->sumarSegundoFecha($this->hora_gestion);

				$db->query("INSERT INTO gestion_ult_semestre(resultado, resultado_n2, resultado_n3, observacion,fecha_gestion,hora_gestion,rut_cliente,fechahora,fono_discado,lista,nombre_ejecutivo,Id_TipoGestion,cedente,n1,n2,n3,p1,p2,p3,p4,ponderacion,fec_compromiso,monto_comp,duracion,Peso,nombre_grabacion,Origen,factura,fechaAgendamiento) VALUES ('$this->nivel1','$this->nivel2','$this->nivel3','$this->comentario','$this->fecha_gestion','$this->hora_gestion','$this->rut','$fechahora','$this->fono_discado','$this->lista','$new_user','".$rowNivel3["Id_TipoGestion"]."','$this->cedente','".$rowNivel1["Respuesta_N1"]."','".$rowNivel2["Respuesta_N2"]."','".$rowNivel3["Respuesta_N3"]."','".$rowNivel3["P1"]."','".$rowNivel3["P2"]."','".$rowNivel3["P3"]."','".$rowNivel3["P4"]."','".$rowNivel3["Ponderacion"]."','$this->fechaCompromiso','$this->montoCompromiso','$tiempoLlamada','".$rowNivel3["Peso"]."','$NombreGrabacion','$origen','$numFactura','$fechaAgenda')");		

				$db->query("INSERT INTO gestion_ult_trimestre(resultado, resultado_n2, resultado_n3, observacion,fecha_gestion,hora_gestion,rut_cliente,fechahora,fono_discado,lista,nombre_ejecutivo,Id_TipoGestion,cedente,n1,n2,n3,p1,p2,p3,p4,Ponderacion,fec_compromiso,monto_comp,duracion,Peso,nombre_grabacion,origen,factura,fechaAgendamiento) VALUES ('$this->nivel1','$this->nivel2','$this->nivel3','$this->comentario','$this->fecha_gestion','$this->hora_gestion','$this->rut','$fechahora','$this->fono_discado','$this->lista','$new_user','".$rowNivel3["Id_TipoGestion"]."','$this->cedente','".$rowNivel1["Respuesta_N1"]."','".$rowNivel2["Respuesta_N2"]."','".$rowNivel3["Respuesta_N3"]."','".$rowNivel3["P1"]."','".$rowNivel3["P2"]."','".$rowNivel3["P3"]."','".$rowNivel3["P4"]."','".$rowNivel3["Ponderacion"]."','$this->fechaCompromiso','$this->montoCompromiso','$tiempoLlamada','".$rowNivel3["Peso"]."','$NombreGrabacion','$origen','$numFactura','$fechaAgenda')");
				if ($this->tipo_gestion == 5){
					$db->query("INSERT INTO Agendamiento_Compromiso(Rut, FechaCompromiso, MontoCompromiso, NumeroFactura) VALUES ('$this->rut','$fechaCom','$this->montoCompromiso','$numFactura')");
				}
			}
		}else{
			$db->query("INSERT INTO gestion_ult_semestre(resultado, resultado_n2, resultado_n3, observacion,fecha_gestion,hora_gestion,rut_cliente,fechahora,fono_discado,lista,nombre_ejecutivo,Id_TipoGestion,cedente,n1,n2,n3,p1,p2,p3,p4,ponderacion,fec_compromiso,monto_comp,duracion,Peso,nombre_grabacion,Origen,fechaAgendamiento) VALUES ('$this->nivel1','$this->nivel2','$this->nivel3','$this->comentario','$this->fecha_gestion','$this->hora_gestion','$this->rut','$fechahora','$this->fono_discado','$this->lista','$new_user','".$rowNivel3["Id_TipoGestion"]."','$this->cedente','".$rowNivel1["Respuesta_N1"]."','".$rowNivel2["Respuesta_N2"]."','".$rowNivel3["Respuesta_N3"]."','".$rowNivel3["P1"]."','".$rowNivel3["P2"]."','".$rowNivel3["P3"]."','".$rowNivel3["P4"]."','".$rowNivel3["Ponderacion"]."','$this->fechaCompromiso','$this->montoCompromiso','$tiempoLlamada','".$rowNivel3["Peso"]."','$NombreGrabacion','$origen','$fechaAgenda')");
			$db->query("INSERT INTO gestion_ult_trimestre(resultado, resultado_n2, resultado_n3, observacion,fecha_gestion,hora_gestion,rut_cliente,fechahora,fono_discado,lista,nombre_ejecutivo,Id_TipoGestion,cedente,n1,n2,n3,p1,p2,p3,p4,Ponderacion,fec_compromiso,monto_comp,duracion,Peso,nombre_grabacion,origen,fechaAgendamiento) VALUES ('$this->nivel1','$this->nivel2','$this->nivel3','$this->comentario','$this->fecha_gestion','$this->hora_gestion','$this->rut','$fechahora','$this->fono_discado','$this->lista','$new_user','".$rowNivel3["Id_TipoGestion"]."','$this->cedente','".$rowNivel1["Respuesta_N1"]."','".$rowNivel2["Respuesta_N2"]."','".$rowNivel3["Respuesta_N3"]."','".$rowNivel3["P1"]."','".$rowNivel3["P2"]."','".$rowNivel3["P3"]."','".$rowNivel3["P4"]."','".$rowNivel3["Ponderacion"]."','$this->fechaCompromiso','$this->montoCompromiso','$tiempoLlamada','".$rowNivel3["Peso"]."','$NombreGrabacion','$origen','$fechaAgenda')");

			$fechaCom = $this->fechaCompromiso." ".$this->hora_gestion;
			
			if ($this->tipo_gestion == 5){
				$db->query("INSERT INTO Agendamiento_Compromiso(Rut, FechaCompromiso, MontoCompromiso) VALUES ('$this->rut','$fechaCom','$this->montoCompromiso')");
			}

		}


		$this->colorFono($this->fono_discado, $this->rut, $rowNivel3["Id_TipoGestion"]);

		
		$sqlAgendamiento = "SELECT * FROM Agendamiento WHERE Rut = '".$this->rut."'";
		$resultAgendamiento = $db -> select($sqlAgendamiento);
		if (count($resultAgendamiento)>0){
			$updateAgendamiento = "UPDATE Agendamiento SET FechaAgenda = '".$fechaAgenda."' WHERE Rut = '".$this->rut."'";
			$db -> query($updateAgendamiento);
		}else{
			$db -> query("INSERT INTO Agendamiento(Rut, FechaAgenda) VALUES ('$this->rut','$fechaAgenda')");
		}

		
		$this->getActualizaEstadoAsignacion($asignacion,$rowNivel3["Id_TipoGestion"],$this->rut,$fechahora);


		$this->actualizaUltimaGestion($this->rut,$rowNivel3["Id_TipoGestion"],$this->comentario,$new_user,$fechahora,$this->fono_discado,$this->fecha_gestion,"");
		if($origen == 1){
			$sqlCedente= "SELECT Nombre_Cedente FROM Cedente WHERE Id_Cedente = '".$this->cedente."'";
			$resultCedente = $db -> select($sqlCedente);
			$cartera = $resultCedente[0]["Nombre_Cedente"];
			$anexo = "SIP/".$_SESSION['anexo_foco'];
			$idCedente = $_SESSION['cedente'];
			$sqlCantidad = "SELECT id, cantidad FROM cantidadGestionesPredictivo WHERE anexo = '".$anexo."' and cartera = '".$cartera."'";
			$resultCantidad = $db -> select($sqlCantidad);
			if (count($resultCantidad) > 0){
				$acumCantidad = 0;
				$idCantidad = $resultCantidad[0]["id"];
				$cantidad = $resultCantidad[0]["cantidad"];
				$acumCantidad = $cantidad + 1;
				$sqlUpdateCant = "UPDATE cantidadGestionesPredictivo SET cantidad = '".$acumCantidad."' WHERE id = '".$idCantidad."'";
				$db -> query($sqlUpdateCant);
			}else{
				$cantidad = 1;
				$sqlCantidadGestiones = "INSERT INTO cantidadGestionesPredictivo(anexo,cartera,cantidad) VALUES ('".$anexo."','".$cartera."','".$cantidad."')";
				$db -> query($sqlCantidadGestiones);
			}			
		}

		echo $this->getProgressAsignacion($asignacion);
			
		//echo "ok";
	}
	public function insertar2($nivel1,$nivel2,$nivel3,$comentario,$fecha_gestion,$hora_gestion,$rut,$fono_discado,$tipo_gestion,$cedente,$fechaCompromiso,$montoCompromiso,$usuario_foco,$lista,$tiempoLlamada,$NombreGrabacion,$asignacion,$origen,$facturas,$fechaAgendamiento,$horaAgendamiento,$Habla)
	{
		$db = new DB();
		$this->usuario_foco=$usuario_foco;
		$new_user = "Foco - ".$this->usuario_foco;
		$this->nivel1=$nivel1;
		$this->nivel2=$nivel2;
		$this->nivel3=$nivel3;
		$this->comentario=$comentario;
		$this->fecha_gestion=$fecha_gestion;
		$this->hora_gestion=$hora_gestion;
		$this->rut=$rut;
		$this->fono_discado=$fono_discado;
		$this->tipo_gestion=$tipo_gestion;
		$this->cedente=$cedente;
		$this->fechaCompromiso=$fechaCompromiso;
		$this->montoCompromiso=$montoCompromiso;
		$this->lista=$lista;
		$fechahora = $this->fecha_gestion." ".$this->hora_gestion;
		$fechaAgenda = $fechaAgendamiento." ".$horaAgendamiento;
		if($Habla!=''){
			$Habla = str_replace("undefined", "", $Habla);
			$db ->query("INSERT INTO Transcripciones(Rut, Fecha, Hora,Transcripcion,Usuario) VALUES ('$rut','$fecha_gestion','$hora_gestion','$Habla','$usuario_foco')");
		}
		
		$rowNivel1 = $this->datosNivel($this->nivel1,1);
		$rowNivel2 = $this->datosNivel($this->nivel2,2);
		$rowNivel3 = $this->datosNivel($this->nivel3,3);

		if ($_SESSION['tipoFactura'] == 1){
			// Entro aca si el cedente es de tipo factura   
			$Arrayfacturas = explode(",",$facturas);        
            foreach($Arrayfacturas as $numFactura){
			$fechaCom = $this->fechaCompromiso." ".$this->hora_gestion;	
			$this->hora_gestion = $this->sumarSegundoFecha($this->hora_gestion);			
			$db->query("INSERT INTO gestion_ult_semestre(resultado, resultado_n2, resultado_n3, observacion,fecha_gestion,hora_gestion,rut_cliente,fechahora,fono_discado,lista,nombre_ejecutivo,Id_TipoGestion,cedente,fec_compromiso,monto_comp,n1,n2,n3,p1,p2,p3,p4,ponderacion, duracion,nombre_grabacion,Origen,factura,fechaAgendamiento) VALUES ('$this->nivel1','$this->nivel2','$this->nivel3','$this->comentario','$this->fecha_gestion','$this->hora_gestion','$this->rut','$fechahora','$this->fono_discado','$this->lista','$new_user','".$rowNivel3["Id_TipoGestion"]."','$this->cedente','$this->fechaCompromiso','$this->montoCompromiso','".$rowNivel1["Respuesta_N1"]."','".$rowNivel2["Respuesta_N2"]."','".$rowNivel3["Respuesta_N3"]."','".$rowNivel3["P1"]."','".$rowNivel3["P2"]."','".$rowNivel3["P3"]."','".$rowNivel3["P4"]."','".$rowNivel3["Ponderacion"]."','$tiempoLlamada','$NombreGrabacion','$origen','$numFactura','$fechaAgenda')");
			$db->query("INSERT INTO gestion_ult_trimestre(resultado, resultado_n2, resultado_n3, observacion,fecha_gestion,hora_gestion,rut_cliente,fechahora,fono_discado,lista,nombre_ejecutivo,Id_TipoGestion,cedente,fec_compromiso,monto_comp,n1,n2,n3,p1,p2,p3,p4,Ponderacion, duracion,nombre_grabacion,origen,factura,fechaAgendamiento) VALUES ('$this->nivel1','$this->nivel2','$this->nivel3','$this->comentario','$this->fecha_gestion','$this->hora_gestion','$this->rut','$fechahora','$this->fono_discado','$this->lista','$new_user','".$rowNivel3["Id_TipoGestion"]."','$this->cedente','$this->fechaCompromiso','$this->montoCompromiso','".$rowNivel1["Respuesta_N1"]."','".$rowNivel2["Respuesta_N2"]."','".$rowNivel3["Respuesta_N3"]."','".$rowNivel3["P1"]."','".$rowNivel3["P2"]."','".$rowNivel3["P3"]."','".$rowNivel3["P4"]."','".$rowNivel3["Ponderacion"]."','$tiempoLlamada','$NombreGrabacion','$origen','$numFactura','$fechaAgenda')");

			

			if ($this->tipo_gestion == 5){
				$db -> query("INSERT INTO Agendamiento_Compromiso(Rut, FechaCompromiso, MontoCompromiso, NumeroFactura) VALUES ('$this->rut','$fechaCom','$this->montoCompromiso','$numFactura')");
			}

			}
		}else{
			$db->query("INSERT INTO gestion_ult_semestre(resultado, resultado_n2, resultado_n3, observacion,fecha_gestion,hora_gestion,rut_cliente,fechahora,fono_discado,lista,nombre_ejecutivo,Id_TipoGestion,cedente,fec_compromiso,monto_comp,n1,n2,n3,p1,p2,p3,p4,ponderacion, duracion,nombre_grabacion,Origen,fechaAgendamiento) VALUES ('$this->nivel1','$this->nivel2','$this->nivel3','$this->comentario','$this->fecha_gestion','$this->hora_gestion','$this->rut','$fechahora','$this->fono_discado','$this->lista','$new_user','".$rowNivel3["Id_TipoGestion"]."','$this->cedente','$this->fechaCompromiso','$this->montoCompromiso','".$rowNivel1["Respuesta_N1"]."','".$rowNivel2["Respuesta_N2"]."','".$rowNivel3["Respuesta_N3"]."','".$rowNivel3["P1"]."','".$rowNivel3["P2"]."','".$rowNivel3["P3"]."','".$rowNivel3["P4"]."','".$rowNivel3["Ponderacion"]."','$tiempoLlamada','$NombreGrabacion','$origen','$fechaAgenda')");
			$db->query("INSERT INTO gestion_ult_trimestre(resultado, resultado_n2, resultado_n3, observacion,fecha_gestion,hora_gestion,rut_cliente,fechahora,fono_discado,lista,nombre_ejecutivo,Id_TipoGestion,cedente,fec_compromiso,monto_comp,n1,n2,n3,p1,p2,p3,p4,Ponderacion, duracion,nombre_grabacion,origen,fechaAgendamiento) VALUES ('$this->nivel1','$this->nivel2','$this->nivel3','$this->comentario','$this->fecha_gestion','$this->hora_gestion','$this->rut','$fechahora','$this->fono_discado','$this->lista','$new_user','".$rowNivel3["Id_TipoGestion"]."','$this->cedente','$this->fechaCompromiso','$this->montoCompromiso','".$rowNivel1["Respuesta_N1"]."','".$rowNivel2["Respuesta_N2"]."','".$rowNivel3["Respuesta_N3"]."','".$rowNivel3["P1"]."','".$rowNivel3["P2"]."','".$rowNivel3["P3"]."','".$rowNivel3["P4"]."','".$rowNivel3["Ponderacion"]."','$tiempoLlamada','$NombreGrabacion','$origen','$fechaAgenda')");

			$fechaCom = $this->fechaCompromiso." ".$this->hora_gestion;
			
			if ($this->tipo_gestion == 5){
					$db -> query("INSERT INTO Agendamiento_Compromiso(Rut, FechaCompromiso, MontoCompromiso) VALUES ('$this->rut','$fechaCom','$this->montoCompromiso')");
			}

		}

		
		$this->colorFono($this->fono_discado, $this->rut, $rowNivel3["Id_TipoGestion"]);


		$sqlAgendamiento = "SELECT * FROM Agendamiento WHERE Rut = '".$this->rut."'";
		$resultAgendamiento = $db -> select($sqlAgendamiento);
		if (count($resultAgendamiento)>0){
			$updateAgendamiento = "UPDATE Agendamiento SET FechaAgenda = '".$fechaAgenda."' WHERE Rut = '".$this->rut."'";
			$db -> query($updateAgendamiento);
		}else{
			$db -> query("INSERT INTO Agendamiento(Rut, FechaAgenda) VALUES ('$this->rut','$fechaAgenda')");
		}
		

		$this->getActualizaEstadoAsignacion($asignacion,$rowNivel3["Id_TipoGestion"],$this->rut,$fechahora);

		$this->actualizaUltimaGestion($this->rut,$rowNivel3["Id_TipoGestion"],$this->comentario,$new_user,$fechahora,$this->fono_discado,$this->fecha_gestion,"");

		if($origen == 1){
			$sqlCedente= "SELECT Nombre_Cedente FROM Cedente WHERE Id_Cedente = '".$this->cedente."'";
			$resultCedente = $db -> select($sqlCedente);
			$cartera = $resultCedente[0]["Nombre_Cedente"];
			$anexo = "SIP/".$_SESSION['anexo_foco'];
			$idCedente = $_SESSION['cedente'];
			$sqlCantidad = "SELECT id, cantidad FROM cantidadGestionesPredictivo WHERE anexo = '".$anexo."' and cartera = '".$cartera."'";
			$resultCantidad = $db -> select($sqlCantidad);
			if (count($resultCantidad) > 0){
				$acumCantidad = 0;
				$idCantidad = $resultCantidad[0]["id"];
				$cantidad = $resultCantidad[0]["cantidad"];
				$acumCantidad = $cantidad + 1;
				$sqlUpdateCant = "UPDATE cantidadGestionesPredictivo SET cantidad = '".$acumCantidad."' WHERE id = '".$idCantidad."'";
				$db -> query($sqlUpdateCant);
			}else{
				$cantidad = 1;
				$sqlCantidadGestiones = "INSERT INTO cantidadGestionesPredictivo(anexo,cartera,cantidad) VALUES ('".$anexo."','".$cartera."','".$cantidad."')";
				$db -> query($sqlCantidadGestiones);
			}			
		}

		echo $this->getProgressAsignacion($asignacion);

		//echo "ok";
	}

	public function actualizaUltimaGestion($rut,$idTipoGestion,$observacion,$nombreEjecutivo,$fechaHora,$fono,$fechaGestion,$statusName){
		$db = new DB();
		$resultado = $db->select("SELECT * FROM Ultima_Gestion_Historica WHERE Rut = '$rut'");
		if (count($resultado) > 0){
			// update
			$db->query("UPDATE Ultima_Gestion_Historica SET fecha_gestion='$fechaGestion', Id_TipoGestion='$idTipoGestion', observacion = '$observacion', nombre_ejecutivo='$nombreEjecutivo', fechahora='$fechaHora', fono_discado = '$fono', status_name= '$statusName' WHERE Rut='$rut'");
		}else{
			// insert
			$db->query("INSERT INTO Ultima_Gestion_Historica(Rut,fecha_gestion,Id_TipoGestion,observacion,nombre_ejecutivo,fechahora,fono_discado) VALUES ('$rut','$fechaGestion','$idTipoGestion','$observacion','$nombreEjecutivo','$fechaHora','$fono')");
		}
	}

	public function insertar3($nivel1,$fecha_gestion,$hora_gestion,$rut,$fono_discado,$tipo_gestion,$cedente,$duracion_llamada,$usuario_foco,$lista,$tiempoLlamada,$NombreGrabacion,$asignacion,$origen)
	{
		$db = new DB();
		$this->usuario_foco=$usuario_foco;
		$new_user = "Foco - ".$this->usuario_foco;
		$this->nivel1=$nivel1;
		$this->fecha_gestion=$fecha_gestion;
		$this->hora_gestion=$hora_gestion;
		$this->rut=$rut;
		$this->fono_discado=$fono_discado;
		$this->tipo_gestion=$tipo_gestion;
		$this->cedente=$cedente;
		$this->duracion_llamada=$duracion_llamada;
		//$this->user_dial=$user_dial;
		$this->lista=$lista;
		//list($horas, $minutos, $segundos) = explode(':', $this->duracion_llamada);
		//$duracion_llamada = ($horas * 3600 ) + ($minutos * 60 ) + $segundos;
		$fechahora = $this->fecha_gestion." ".$this->hora_gestion;



		$row = $db->select("SELECT n1.Id as respuesta_n1, n2.id respuesta_n2, n3.id as respuesta_n3
					 FROM Nivel3 n3, Nivel2 n2, Nivel1 n1, Respuesta_Rapida r
					 WHERE FIND_IN_SET('$cedente',r.Id_Cedente) and n3.Id_Nivel2 = n2.id and n2.Id_Nivel1 = n1.Id and r.Respuesta_Nivel3 = n3.id and r.Respuesta_Nivel3 = '$this->nivel1'");
        $n1 = $row[0]["respuesta_n1"];
        $n2 = $row[0]["respuesta_n2"];
        $n3 = $row[0]["respuesta_n3"];

		$rowNivel1 = $this->datosNivel($n1,1);
		$rowNivel2 = $this->datosNivel($n2,2);
		$rowNivel3 = $this->datosNivel($n3,3);


		$db->query("INSERT INTO gestion_ult_semestre(resultado, resultado_n2, resultado_n3,fecha_gestion,hora_gestion,rut_cliente,fechahora,fono_discado,lista,nombre_ejecutivo,Id_TipoGestion,cedente,duracion,Origen,n1,n2,n3,p1,p2,p3,p4,ponderacion,nombre_grabacion) VALUES ('$n1','$n2','$n3','$this->fecha_gestion','$this->hora_gestion','$this->rut','$fechahora','$this->fono_discado','$this->lista','$new_user','".$rowNivel3["Id_TipoGestion"]."','$this->cedente','$tiempoLlamada','$origen','".$rowNivel1["Respuesta_N1"]."','".$rowNivel2["Respuesta_N2"]."','".$rowNivel3["Respuesta_N3"]."','".$rowNivel3["P1"]."','".$rowNivel3["P2"]."','".$rowNivel3["P3"]."','".$rowNivel3["P4"]."','".$rowNivel3["Ponderacion"]."','$NombreGrabacion')");
		$db->query("INSERT INTO gestion_ult_trimestre(resultado, resultado_n2, resultado_n3,fecha_gestion,hora_gestion,rut_cliente,fechahora,fono_discado,lista,nombre_ejecutivo,Id_TipoGestion,cedente,duracion,origen,n1,n2,n3,p1,p2,p3,p4,Ponderacion,nombre_grabacion) VALUES ('$n1','$n2','$n3','$this->fecha_gestion','$this->hora_gestion','$this->rut','$fechahora','$this->fono_discado','$this->lista','$new_user','".$rowNivel3["Id_TipoGestion"]."','$this->cedente','$tiempoLlamada','$origen','".$rowNivel1["Respuesta_N1"]."','".$rowNivel2["Respuesta_N2"]."','".$rowNivel3["Respuesta_N3"]."','".$rowNivel3["P1"]."','".$rowNivel3["P2"]."','".$rowNivel3["P3"]."','".$rowNivel3["P4"]."','".$rowNivel3["Ponderacion"]."','$NombreGrabacion')");

		$this->getActualizaEstadoAsignacion($asignacion,$rowNivel3["Id_TipoGestion"],$this->rut,$fechahora);

		$this->actualizaUltimaGestion($this->rut,$rowNivel3["Id_TipoGestion"],'',$new_user,$fechahora,$this->fono_discado,$this->fecha_gestion,"");

		if($origen == 1){
			$sqlCedente= "SELECT Nombre_Cedente FROM Cedente WHERE Id_Cedente = '".$this->cedente."'";
			$resultCedente = $db -> select($sqlCedente);
			$cartera = $resultCedente[0]["Nombre_Cedente"];
			$anexo = "SIP/".$_SESSION['anexo_foco'];
			$idCedente = $_SESSION['cedente'];
			$sqlCantidad = "SELECT id, cantidad FROM cantidadGestionesPredictivo WHERE anexo = '".$anexo."' and cartera = '".$cartera."'";
			$resultCantidad = $db -> select($sqlCantidad);
			if (count($resultCantidad) > 0){
				$acumCantidad = 0;
				$idCantidad = $resultCantidad[0]["id"];
				$cantidad = $resultCantidad[0]["cantidad"];
				$acumCantidad = $cantidad + 1;
				$sqlUpdateCant = "UPDATE cantidadGestionesPredictivo SET cantidad = '".$acumCantidad."' WHERE id = '".$idCantidad."'";
				$db -> query($sqlUpdateCant);
			}else{
				$cantidad = 1;
				$sqlCantidadGestiones = "INSERT INTO cantidadGestionesPredictivo(anexo,cartera,cantidad) VALUES ('".$anexo."','".$cartera."','".$cantidad."')";
				$db -> query($sqlCantidadGestiones);
			}			
		}

		$this->colorFono($this->fono_discado, $this->rut, $rowNivel3["Id_TipoGestion"]);

		echo $this->getProgressAsignacion($asignacion);

		//echo "ok";
	}
	public function limpiarSeleccion()
	{
		$db = new DB();
		$db->query("UPDATE Deuda SET Marca_Factura=0 WHERE Marca_Factura=1");
		$db->query("UPDATE Mail SET Marca=0 WHERE Marca=1");
	}

	public function mostrarScript($idCedente){
		$db = new Db();
		$sql = "SELECT script FROM script_cedente WHERE id_cedente = '".$idCedente."'";
		$resultado = $db->select($sql);
		$script = utf8_encode($resultado[0]['script']);
		return $script;
	}


	public function mostrarDeudas($rut,$cedente,$pantalla = "crm")
	{
		$idTableDeuda = 2;
		$db = new DB();
		$dbDiscador = new DB("discador");

        $Sql = "SELECT * FROM SIS_Columnas_Estrategias WHERE id_tabla='".$idTableDeuda."' and FIND_IN_SET('$cedente',Id_Cedente) order by columna";
		$columnas = $db -> select($Sql);
    	// total columnas por cedente $columnasDeudaTodas
  	 	$ArrayColumnas = array();
		$ArrayColumnasTmp = array();
		$ArrayColumnasSuma = array(); // Array que contiene todas las columnas que muestran el total (Suma) al final
		$resultado = array();
		foreach($columnas as $columna){
			array_push($ArrayColumnas,$columna["columna"]."|".$columna["tipo_dato"]);
			array_push($ArrayColumnasTmp,$columna["columna"]);
			if ($columna["suma"] == 1){
				$Array = array();
				$Array[$columna["columna"]] = 0;
				$ArrayColumnasSuma = array_merge($ArrayColumnasSuma,$Array);
			}
		}
    	$columnasDeudaTodas = implode(",",$ArrayColumnasTmp);
        $SqlDeuda = "SELECT ".$columnasDeudaTodas." FROM Deuda WHERE Rut ='".$rut."' and Id_Cedente = '".$cedente."'";
		//$deudas = $db -> select($SqlDeuda);
		switch($pantalla){
			case "crm":
				$deudas = $db -> select($SqlDeuda);
			break;
			case "predictivo":
				$deudas = $dbDiscador -> select($SqlDeuda);
			break;
		}
    	// total columnas con valores $arrayColumnasConData

		$arrayColumnasConData = array();
		$arrayColumnasConDataTmp = array();
		foreach($ArrayColumnas as $Columna){
			$Col = explode("|",$Columna);
  			if($this->getCamposMostrar($deudas,$Col[0])){
    			array_push($arrayColumnasConData,$Columna);
				array_push($arrayColumnasConDataTmp,$Col[0]);
  			}
		}

		$columnasDeudaFinal = implode(",",$arrayColumnasConDataTmp);

 		echo '<div class="table-responsive">';
			echo '<table id="demo-dt-basic" class="table table-striped table-bordered" cellspacing="0" width="100%">';
				echo '<thead>';
					echo '<tr>';
					foreach($arrayColumnasConDataTmp as $columna){
						echo '<th>'.$columna.'</th>';
					}
					echo '</tr>';
				echo '</thead>';

				$SqlDeuda = "SELECT ".$columnasDeudaFinal." FROM Deuda WHERE Rut ='".$rut."' and Id_Cedente = '".$cedente."'";
				$deudas = $db -> select($SqlDeuda);
				$ContadorDeColumnas = 0; // 10296535 Monto_Mora Saldo_Insoluto
				$acumMontoMora = 0;
				$acumSaldoInsoluto = 0;
				$camposSuma = 0; // 0 me indica que no tenemos totales en al menos un campo
				foreach($deudas as $deuda){
					echo '<tr>';
					for($i=0;$i<=count($arrayColumnasConData) - 1;$i++){
						$ColumnaArray = explode("|",$arrayColumnasConData[$i]);
						$Columna = $ColumnaArray[0];
						$Value = $deuda[$Columna];
						// saco el total de todos los campos que se suman (acumulador)
						foreach ($ArrayColumnasSuma as $clave => $valor){
							if ($Columna == $clave){
								$camposSuma = 1;
								$ArrayColumnasSuma[$clave] = $ArrayColumnasSuma[$clave] + $Value;
							}
						}

						$decimales = strrpos($Value, '.');
						$monto = $Value;
						$Value = $ColumnaArray[1] == "0" ? number_format($Value, 0, '', '.') : $Value;

						if (is_numeric($decimales)){
							//es decimal;
							$decimales = strrpos($monto, '.00');
							if (is_numeric($decimales)){
								$monto = $ColumnaArray[1] == "0" ? number_format($monto, 0, '', '.') : $monto;
							}else{
								$monto = $ColumnaArray[1] == "0" ? number_format($monto, 2, ',', '.') : $monto;
							}
							$Value = $monto;
						}

						$CheckNumeroFactura = "";
						switch($Columna){
							case "Monto_Mora":
								$acumMontoMora = $acumMontoMora + $Value;
							break;
							case "Saldo_Insoluto":
								$acumSaldoInsoluto = $acumSaldoInsoluto + $Value; // number_format($número, 2, ',', ' ');
							break;
							case "Numero_Factura":
								if($_SESSION['tipoSistema'] == "1"){
									$File = "facturas/".$_SESSION['mandante']."/".$_SESSION['cedente']."/".$rut."/".$Value.".pdf";
									$Color = "";
									$Disabled = "";
									if(!file_exists("../../".$File)){
										$Color = "color: #CCCCCC;";
										$Disabled = "Disabled";
									}
									$CheckNumeroFactura = "<label class='form-checkbox form-normal form-primary inputCheckFactura' style='margin-left: 10px;'><input type='checkbox'></label><i class='fa fa-download DownloadFactura ".$Disabled."' style='float: right;font-size: 20px;cursor: pointer;".$Color."' href='".$File."' number='".$Value."'></i>";
								}
							break;
						}
						/* if ($Columna == 'Monto_Mora'){
							$acumMontoMora = $acumMontoMora + $Value;
						}
						if ($Columna == 'Saldo_Insoluto'){
							$acumSaldoInsoluto = $acumSaldoInsoluto + $Value; // number_format($número, 2, ',', ' ');
						} */

						//$Value = $ColumnaArray[1] == "0" ? number_format($Value, 0, '', '.') : $Value;
						echo '<td><span>'.$Value."</span>".$CheckNumeroFactura.'</td>';
						$ContadorDeColumnas++;
					}
					echo '</tr>';
				}
				// Si tengo columnas Suma (Muestra totales)
				if ($camposSuma == 1)
				{
				echo '<tr style="background-color:#CCFFFF">';
				foreach($arrayColumnasConDataTmp as $columna)
				{
					$Value = "";
					foreach ($ArrayColumnasSuma as $clave => $valor){

							if ($columna == $clave){
								if($valor == 0){
									$Value = "";
								}else{
									$decimales = strrpos($valor, '.');
									if (is_numeric($decimales)){
										//es decimal;
										$decimales = strrpos($valor, '.00');
										if (is_numeric($decimales)){
											$Value = $ColumnaArray[1] == "0" ? number_format($valor, 0, '', '.') : $Value;
										}else{
											$Value = $ColumnaArray[1] == "0" ? number_format($valor, 2, ',', '.') : $Value;
										}
									}else{
										$Value = number_format($valor, 0, '', '.');
									}



									//$Value = number_format($valor, 2, ',', '.');
								}
							}
					}


					echo '<td>'.$Value.'</td>';

				}
				echo '</tr>';
				}
				// fin pinto filas totales
			echo '</table>';
		echo '</div>';

	}

	public function getCamposMostrar($Deudas,$Columna){
  		$ToReturn = false;
  		foreach($Deudas as $Deuda){
    		if($Deuda[$Columna] != ""){
      			if($Deuda[$Columna] != "0"){
        			$ToReturn = true;
        			break;
      			}
    		}
  		}
  	return $ToReturn;
	}



/* public function mostrarDeudasviejo($rut,$cedente)
{
	$this->rut=$rut;
	$this->cedente=$cedente;
	$qry1 = mysql_query("SELECT Id_Conf	, Nombre_Conf,Id_Cedente,Nombre_Tabla,Descripcion_Consulta,Nombre_Campos,Nombre_Columnas FROM Conf_Pantalla_Cedente WHERE Nombre_Tabla = 'Deuda' and Id_Cedente = $this->cedente ORDER BY Id_Conf DESC LIMIT 1  ");
	$Nombre_Campos = "";
	$Nombre_Columnas = "";
	$strConsuta = "";
	$sw1 = 0;
	while($row = mysql_fetch_array($qry1))
	{
		$Nombre_Campos = $row['Nombre_Campos'];
		$Nombre_Columnas = $row['Nombre_Columnas'];
		$strConsuta = $row['Descripcion_Consulta'] . " WHERE Rut = '$this->rut=$rut'  AND Id_Cedente = $this->cedente ";
		$sw1 = 1;
	}
	if ($sw1 == 0) // Campos por defecto cuando no tiene configuracion creada
	{
		$Nombre_Campos = "Rut,Monto_Mora";
		$Nombre_Columnas = "Rut,Monto_Mora";
		$strConsuta = " SELECT Rut,Monto_Mora FROM Deuda WHERE Rut = '$this->rut=$rut' AND Id_Cedente = $this->cedente ";
	}
	$arrSele = explode ( "FROM" , $strConsuta );
	$strConsuta = $arrSele[0] . ", Id_deuda as Id_D FROM " . $arrSele[1];
	$arrNomColum = explode ( ',' , $Nombre_Campos );
	$arrheadColum = explode ( ',' , $Nombre_Columnas );
	$totalColum = count($arrheadColum);
	echo '<div class="table-responsive">';
	echo '<table id="demo-dt-basic" class="table table-striped table-bordered" cellspacing="0" width="100%">';
	echo '<thead>';
	echo '<tr>';
	foreach($arrheadColum as $itemHead):
	echo "<th> $itemHead </th>";
	endforeach;
	echo "<th style='text-align:center'>Factura</th>";
	echo "<th style='text-align:center'>Adjuntar</th>";
	echo '</tr>';
	echo '</thead>';
	echo '<tbody>';
	$query2 = mysql_query($strConsuta);
	$j = 1;
	$varCK = "";
		while($row2 = mysql_fetch_array($query2))
	{
		$varCK = "chk".$j;
		echo "<tr id='$row2[Id_D]' class='$j' >";
		for ($i=0; $i < $totalColum; $i++) {
			$valtem = trim($arrNomColum[$i]);
			echo "<td class='text-sm'>$row2[$valtem]</td>";
			}

		$fac = $row2[3];
		$ruta = "/home/foco/ftp/".$this->cedente;

		$resp = $this->BuscarEnDirectorio($ruta,$fac);
		$ruta_pdf = $ruta."/".$resp;

		if($resp=='0')
		{
			echo "<td style='text-align:center'>";
			echo "Sin Factura Fisica";
			echo "</td>";
			echo "<td style='text-align:center'>";
			echo " <input type='checkbox' class='ckhsel'  disabled='disabled' name='$varCK' value='$varCK' id='$varCK' >";
			echo "</td>";
		}
		else
		{
			echo "<td style='text-align:center'>";
			echo "<a href='factura.php?factura=$ruta_pdf'><i class='fa fa-file-pdf-o' aria-hidden='true'></i> - $resp </a>";
			echo "</td>";
			echo "<td style='text-align:center'>";
			echo " <input type='checkbox' class='ckhsel' name='$varCK' value='$varCK' id='$varCK' >";
			echo "</td>";
		}
		echo '</tr>';
		$j = $j + 1;
	}
	echo '</tbody></table></div>';


} */

	public function BuscarEnDirectorio($path,$num_factura)
	{
	    $this->path=$path;
	    $this->num_factura=$num_factura;

	    $dir = opendir($this->path);
	    $files = array();
	    $nombreArchivo = "";
	    while ($current = readdir($dir)){
	        if( $current != "." && $current != "..") {
	            if(is_dir($this->path.$current)) {
	                //showFiles($path.$current.'/');
	            }
	            else {
	                $files[] = $current;
	                $pos = strpos($current, $this->num_factura);
	                if ($pos !== false) {
					     return $current;
					}
	            }
	        }
	    }
	    return "0";

	}
	public function mostrarAsignacion($Cola){
		$db = new DB();
		$this->Cola=$Cola;
		$Cola2 = $this->Cola."_";

		echo "<select class='select1' id='seleccione_asignacion' name='seleccione_cedente'>";
        $rows=$db->select("select TABLE_NAME from information_schema.COLUMNS WHERE TABLE_SCHEMA='foco' and TABLE_NAME like '%$this->Cola%' group by TABLE_NAME");
       	echo '<option value="0">Seleccione</option>';
        foreach($rows as $row)
        {
        	$Cola = $row["TABLE_NAME"];
			$ParteA = explode("_", $Cola);
			if($ParteA[7]==1){
				switch ($ParteA[3]) {
					case 'E':
						$Tipo = 'Ejecutivo';
						$Entidades  = $db->select("SELECT Nombre FROM Personal WHERE Id_Personal = $ParteA[4] LIMIT 1");
						foreach($Entidades as $Entidad){
							$Nombre = $Entidad["Nombre"];
						}

						echo "<option value='$Cola'>"."Asignacion " . $Tipo . " " . $Nombre . "</option>";

						break;
					case 'S':
						$Tipo = 'Supervisor';
						$Entidades  = $db->select("SELECT Nombre FROM Personal WHERE Id_Personal = $ParteA[4] LIMIT 1");
						foreach($Entidades as $Entidad){
							$Nombre = $Entidad["Nombre"];
						}

						echo "<option value='$Cola'>"."Asignacion " . $Tipo . " " . $Nombre . "</option>";

						break;
					case 'G':
						$Tipo = 'Grupo';
						$Entidades  = $db->select("SELECT Nombre FROM Personal WHERE Id_Personal = $ParteA[4] LIMIT 1");
						foreach($Entidades as $Entidad){
							$Nombre = $Entidad["Nombre"];
						}

						echo "<option value='$Cola'>"."Asignacion " . $Tipo . " " . $Nombre . "</option>";

						break;
					case 'EE':
						$Tipo = 'EMPRESA EXTERNA';
						$Entidades  = $db->select("SELECT Nombre FROM Personal WHERE Id_Personal = $ParteA[4] LIMIT 1");
						foreach($Entidades as $Entidad){
							$Nombre = $Entidad["Nombre"];
						}

						echo "<option value='$Cola'>"."Asignacion " . $Tipo . " " . $Nombre . "</option>";
						break;
				}
			}else{
			}

        }

        echo "</select>";

	}

	function actualizaAsignacion($Asignacion){
		$db = new DB();
		$fecha1 = date("Y-m");
    	$fecha = $fecha1."-01";
     	$SqlUpdate2 = "UPDATE ".$Asignacion." set estado = 0, orden = 0, fechaGestion = '0000-00-00 00:00:00'";
		$db -> query($SqlUpdate2);
		$sql = "SELECT ".$Asignacion.".Rut, Id_TipoGestion, fechahora FROM ".$Asignacion." INNER JOIN Ultima_Gestion_Historica WHERE ".$Asignacion.".Rut = Ultima_Gestion_Historica.Rut AND Ultima_Gestion_Historica.fecha_gestion >= '".$fecha."'";
		$resultado = $db->select($sql);
		foreach($resultado as $fila){
			$tipoGestion = $fila["Id_TipoGestion"];
			$Rut = $fila["Rut"];
			$fechaGestion = $fila["fechahora"];
			$this->getActualizaEstadoAsignacion($Asignacion,$tipoGestion,$Rut,$fechaGestion);
		}
		$sql2 = "SELECT id FROM ".$Asignacion." ORDER BY fechaGestion ASC";
		$resultado2 = $db->select($sql2);
		$contador = 0;
		foreach($resultado2 as $fila2){
			$contador = $contador + 1;
			$id = $fila2["id"];
			$SqlUpdate = "UPDATE ".$Asignacion." set orden = '".$contador."' WHERE id='".$id."'";
			$db -> query($SqlUpdate);
		}


	}

	function ordenarAsignacion($Asignacion){
		$db = new DB();
		$sql2 = "SELECT id FROM ".$Asignacion." ORDER BY fechaGestion ASC";
		$resultado2 = $db->select($sql2);
		$contador = 0;
		foreach($resultado2 as $fila2){
			$contador = $contador + 1;
			$id = $fila2["id"];
			$SqlUpdate = "UPDATE ".$Asignacion." set orden = '".$contador."' WHERE id='".$id."'";
			$db -> query($SqlUpdate);
		}
	}



	function getActualizaEstadoAsignacion($Asignacion,$tipoGestion,$rut,$fechaGestion){
		$db = new DB();
		switch ($tipoGestion){
			case 3:
			case 4:
			// 3,4 SC
			$estatus = 1;
			break;
			case 1:
			case 2:
			case 5:
			// 1,2,5 C
			$estatus = 2;
			break;
			default:
			// sin historica
			$estatus = 0;
		}
		$SqlUpdate = "UPDATE ".$Asignacion." set estado = '".$estatus."', fechaGestion = '".$fechaGestion."'  WHERE Rut='".$rut."'";
		$db -> query($SqlUpdate);
	}

	function mostrarInforme(){
		$db = new DB();
		$this->updateTiempoTranscurrido();
		$sql = "SELECT anexo, ejecutivo, estatus, pausa, tiempo, cartera FROM reporteOnLine WHERE activo = 1";
		$resultado = $db -> select($sql);
		echo '<table id="demo-foo-filtering" class="table table-bordered table-hover toggle-circle" data-page-size="12">
								<thead>
									<tr>
										<th data-toggle="true">Anexo</th>
										<th>Ejecutivo</th>
										<th data-hide="phone, tablet">Estatus</th>
										<th data-hide="phone, tablet">Pausa</th>
										<th data-hide="phone, tablet">MM:SS</th>
										<th data-hide="phone, tablet">Cartera</th>
										<th data-hide="phone, tablet">Cantidad</th>
									</tr>
								</thead>
								<div class="pad-btm form-inline">
								<tbody>';
								foreach($resultado as $result){ // anexo, ejecutivo, estatus, pausa, tiempo, cartera						
									
									$anexo = $result['anexo'];
									$ejecutivo = utf8_encode($result['ejecutivo']);
									$estatus = utf8_encode($result['estatus']);
									$pausa = utf8_encode($result['pausa']);
									$tiempo = $result['tiempo'];
									$cartera = utf8_encode($result['cartera']);
									$sqlCantidad = "SELECT cantidad FROM cantidadGestionesPredictivo WHERE anexo = '".$anexo."' and cartera = '".$cartera."'";
									$resu = $db -> select($sqlCantidad);
									if(count($resu)>0){
										$cantidad = $resu[0]["cantidad"];
									}else{
										$cantidad = 0;
									}
									
									switch ($estatus){
										case 'DISPONIBLE': // esperando llamada
											$colorEstatus = 'label-success';
											$colorFila = 'background-color: #E9FCED';
										break;
										case 'INCALL': // esta hablando

											$colorEstatus = 'label-danger';
											$colorFila = 'background-color: #FAE2D1';
										break;
										case 'PAUSED': // esta en pausa
											$colorEstatus = 'label-warning';
											$colorFila = $this->colorFilaEstatus($tiempo,"PAUSED");
										break;
										case 'DEAD': // colgo y esta en tiempo de guardar la gestion
											$colorEstatus = 'label-dark';
											$colorFila = 'background-color: #FFFFFF';
										break;
									}

									switch ($pausa){
										case 'Cafe': // esperando llamada
											$colorPausa = 'label-info';
										break;
										case 'Bano': // esta hablando
											$colorPausa = 'label-danger';
										break;
										case 'Soporte': // esta en pausa
											$colorPausa = 'label-warning';
										break;
										case 'Office': // esta en pausa
											$colorPausa = 'label-mint';
										break;
										case 'Capacitacion': // esta en pausa
											$colorPausa = 'label-purple';
										break;
										case 'Reunion': // esta en pausa
											$colorPausa = 'label-success';
										break;
										default:
										$colorPausa = '';

									}

									echo '<tr style="'.$colorFila.'">
										<td>'.$anexo.'</td>
										<td>'.$ejecutivo.'</td>
                    					<td><span class="label label-table '.$colorEstatus.'">'.$estatus.'</span></td>
                    					<td><span class="label label-table '.$colorPausa.'">'.$pausa.'</span></td>
                    					<td>'.$tiempo.'</td>
										<td>'.$cartera.'</td>
										<td>'.$cantidad.'</td>
									</tr>';
								}

								echo '</tbody>
								<!--<tfoot>
									<tr>
										<td colspan="6">
											<div class="text-right">
												<ul class="pagination"></ul>
											</div>
										</td>
									</tr>
								</tfoot>-->
							</table>';
	}

	function nuevoEstatusReporteOnline($datos){
		// activo nuevo estatus
		$db = new DB();
		$estatus = $datos['estatus'];
		$pausa = $datos['pausa'];
		$anexo = "SIP/".$_SESSION['anexo_foco'];
		$fechaActual = date("Y-m-d");
		$idCedente = $_SESSION['cedente'];

		if ($estatus <> ''){

				$sqlCedente= "SELECT Nombre_Cedente FROM Cedente WHERE Id_Cedente = '".$idCedente."'";
				$resultCedente = $db -> select($sqlCedente);
				$cartera = $resultCedente[0]["Nombre_Cedente"];

				// antes de guardar guardo el acumulativo busco el status activo (puede que no este ninguno activo en este caso
				// el ejecutivo esta fuera de linea y esta entrando por primera vez a disponible)
				$sqlEstatusActivo = "SELECT tiempo, estatus, cartera, id_reporte FROM reporteOnLine WHERE anexo = '".$anexo."' AND activo = 1";
				$result = $db -> select($sqlEstatusActivo);
				if (count($result) > 0){
					// sumo el tiempo acumulado y lo almaceno en la tabla historica
					$tiempo = $result[0]["tiempo"];
					$estatusHis = $result[0]["estatus"];
					$cartera = $result[0]["cartera"];
					$idReporte = $result[0]["id_reporte"];


					$sqlTiempo = "SELECT tiempo, id_reporte FROM reporteOnLineHistorico WHERE anexo = '".$anexo."' AND estatus = '".$estatusHis."' AND fecha = '".$fechaActual."' AND cartera = '".$cartera."'";
					$tiempoHis = 0;
					$resultTiempo = $db -> select($sqlTiempo);
					$tiempoHis = $resultTiempo[0]["tiempo"];
					$idReporte = $resultTiempo[0]["id_reporte"];
					// OJOOOOOO SUMAR TIEMPOS (ACUMULADO)
					$tiempoAcumulado = $tiempoHis + $tiempo;

					$sqlTiempoHistorico = "UPDATE reporteOnLineHistorico SET tiempo = '".$tiempoAcumulado."' WHERE id_reporte = '".$idReporte."'";
					$db -> query($sqlTiempoHistorico);

				}

				// inactivo el ultimo estatus
				$sqlActivo = "UPDATE reporteOnLine SET activo = 0 WHERE anexo = '".$anexo."'";
				$db -> query($sqlActivo);

				// verifico si el estatus ya esta creado, si es asi solo lo actualizo si no lo creo
				$sqlEstatus = "SELECT id_reporte FROM reporteOnLine WHERE anexo = '".$anexo."' AND estatus = '".$estatus."'";
				$result = $db -> select($sqlEstatus);
				$inicio = date("H:i:s");
				$termino = date("H:i:s");
				$tiempo = 0;
				$ejecutivo = $_SESSION['nombreUsuario'];

				if (count($result) > 0){
					$idReporte = $result[0]["id_reporte"];
					// activo el nuevo estatus
					$sql = "UPDATE reporteOnLine SET inicio = '".$inicio."', termino = '".$termino."', activo = 1, tiempo = 0, cartera = '".$cartera."', pausa = '".$pausa."' WHERE id_reporte = '".$idReporte."'";
					$db -> query($sql);
				}else{
					$activo = 1;
					$sqlCreaRegistro = "INSERT INTO reporteOnLine(anexo,ejecutivo,estatus,pausa,inicio,termino,tiempo,cartera,activo) VALUES ('".$anexo."','".$ejecutivo."','".$estatus."','".$pausa."','".$inicio."','".$termino."','".$tiempo."','".$cartera."','".$activo."')";
					$db -> query($sqlCreaRegistro);
					$sqlCreaRegistroHisto = "INSERT INTO reporteOnLineHistorico(anexo,ejecutivo,estatus,pausa,tiempo,cartera,fecha) VALUES ('".$anexo."','".$ejecutivo."','".$estatus."','".$pausa."','".$tiempo."','".$cartera."','".$fechaActual."')";
					$db -> query($sqlCreaRegistroHisto);									
				}
		}else{
			// el ejecutivo salio de la cola por lo tanto no quedan status activos
			// inactivo el ultimo estatus
			$sqlActivo = "UPDATE reporteOnLine SET activo = 0 WHERE anexo = '".$anexo."'";
			$db -> query($sqlActivo);
		}



	}

	function diferenciaEntreHoras($PrimeraFecha,$UltimaFecha){
		$PrimeraFecha = date($PrimeraFecha);
		$UltimaFecha = date($UltimaFecha);
		$PrimeraFecha = new DateTime($PrimeraFecha);
		$UltimaFecha = new DateTime($UltimaFecha);
		$Diferencia = $PrimeraFecha->diff($UltimaFecha);
		$Horas = strlen($Diferencia->h) > 1 ? $Diferencia->h : "0".$Diferencia->h;
		$Minutos = strlen($Diferencia->i) > 1 ? $Diferencia->i : "0".$Diferencia->i;
		$Segundos = strlen($Diferencia->s) > 1 ? $Diferencia->s : "0".$Diferencia->s;
		$diferencia = $Horas.":".$Minutos.":".$Segundos;
		return $diferencia;
	}

	function pausaPredictivo(){
		$db = new DB("discador");
		$Anexo = "SIP/".$_SESSION['anexo_foco'];
		$row = $db->select("SELECT Queue FROM Asterisk_Agentes WHERE Agente = '$Anexo' limit 1");
		$queues = $row[0]["Queue"];
		shell_exec("php /var/www/html/produccion/discador/AGI/Pause.php '$queues' '$Anexo'");
	}

	function capturaHangup(){
		$dbDiscador = new DB("discador");
		$anexo = $_SESSION['anexo_foco'];
		$sql = "SELECT anexo FROM Asterisk_Hangup WHERE anexo = '".$anexo."'";
		$resultado = $dbDiscador -> select($sql);
		if (count($resultado) > 0){
			return 1;	
		}else{
			return 0;
		}
	}

	function updateTiempoTranscurrido(){
		$db = new DB();
		$horaActual = date('H:i:s');
		$sql = "SELECT inicio, id_reporte FROM reporteOnLine WHERE activo = 1";
		$resultado = $db -> select($sql);
		foreach($resultado as $fila){
			$horaInicio = $fila["inicio"];
			$idReporte = $fila["id_reporte"];
			$tiempo = $this->diferenciaEntreHoras($horaInicio,$horaActual);
			$sqlTiempo = "UPDATE reporteOnLine SET tiempo = '".$tiempo."' WHERE id_reporte = '".$idReporte."'";
			$db -> query($sqlTiempo);
		}
	}


	function colorFilaEstatus($tiempo,$tipoEstatus){
		$array = explode(":", $tiempo);
		if(($array[0] == 0) && ($array[1] == 0)){
  			if($array[2] < 10){
    			if ($tipoEstatus == "PAUSED"){ 
					$color = 'background-color: #F7F6E7';
				}else{
					if ($tipoEstatus == "INCALL"){ 
						$color = 'background-color: #FAE2D1';
					}	
				}
  			}else{
				if ($tipoEstatus == "PAUSED"){ 
					$color = 'background-color: #F7EF81';
				}else{
					if ($tipoEstatus == "INCALL"){ 
						$color = 'background-color: #CCFEF9';
					}		
				}
				
			}
		}else{

		
				if ($array[0] > 0){
					if ($tipoEstatus == "PAUSED"){ 
						$color = 'background-color: #FB7C7C';
					}else{
						if ($tipoEstatus == "INCALL"){ 
							$color = 'background-color: #FB7C7C';
						}	
					}					
					
				}else{  
						if(($array[1] >= 1) && ($array[1] < 5)){
							if ($tipoEstatus == "PAUSED"){ 
								$color = 'background-color: #9CC6DA';
							}else{
								if ($tipoEstatus == "INCALL"){ 
									$color = 'background-color: #8AF7ED';
								}	
							}						
							
						}else{				
								if($array[1] >= 5){
									if ($tipoEstatus == "PAUSED"){ 
								$color = 'background-color: #FB7C7C';
							}else{
								if ($tipoEstatus == "INCALL"){ 
									$color = 'background-color: #8AF7ED';
								}	
							}	
									
								}else{
									if ($tipoEstatus == "PAUSED"){ 
								$color = 'background-color: #F7F6E7';
							}else{
								if ($tipoEstatus == "INCALL"){ 
									$color = 'background-color: #75C9C1';
								}	
							}	
									
								}
						}
					}   
		}			  
			
	return $color;
	}
	function isAuthorizedModule($Type){
		$db = new DB();
		$ToReturn = array();
		$ToReturn["result"] = false;
		$SqlAuthorized = "select * from AutorizacionEjecutivos where Id_Usuario='".$_SESSION["id_usuario"]."' and Id_Cedente='".$_SESSION["cedente"]."' AND tipoAutorizacion='".$Type."'";
		$Authorized = $db->select($SqlAuthorized);
		if(count($Authorized) > 0){
			$ToReturn["result"] = true;
		}
		return $ToReturn;
	}
	function guardarGestionCorreo($correos, $facturas, $rut, $template){
		$db = new DB();
		$fecha = date("Y-m-d");
		$hora = date("H:i:s");	
		$cedente = $_SESSION["cedente"]; 
		$nombre = "Foco - ".$_SESSION["nombreUsuario"];
		$correosEn = implode(",",$correos);
		if (count($facturas)>0){
			$facturasEn = implode(",",$facturas);	
		}	
		$sqlCreaRegistro = "INSERT INTO gestion_correo(rut_cliente,fecha_gestion,hora_gestion,nombre_ejecutivo,cedente,correos,facturas,template) VALUES ('".$rut."','".$fecha."','".$hora."','".$nombre."','".$cedente."','".$correosEn."','".$facturasEn."','".$template."')";
		$db -> query($sqlCreaRegistro);
	}
}
?>
