<?php
//include("../../db/db.php");
class Tareas
{
	public function asignarTipo($id,$id_cedente)
	{
		$this->id=$id;
		$this->id_cedente=$id_cedente;
	}
	public function mostrarTipo()
	{
		include("../../db/db.php");
		$sql_estrategia = mysql_query("SELECT * FROM SIS_Estrategias WHERE  tipo=$this->id AND Id_Cedente = '$this->id_cedente'");
		if(mysql_num_rows($sql_estrategia)>0)
		{
			echo '<table id="demo-dt-basic" class="table table-striped table-bordered" cellspacing="0" width="100%">
    <thead><tr>';
            echo '<th>ID Estrategia</th>';
           	echo '<th>Nombre de la Estrategia</th>';
			echo '<th class="min-desktop"><center>Creador</center></th>';
			echo '<th class="min-desktop"><center>Hora</center></th>';
			echo '<th class="min-desktop"><center>Fecha</center></th>';
			echo '<th class="min-desktop"><center>Seleccionar</center></th></tr>';                               
            echo '</thead><tbody>';
			$j = 1;
            $sql_estrategia2 = mysql_query("SELECT * FROM SIS_Estrategias WHERE  tipo=$this->id AND Id_Cedente = '$this->id_cedente'");
            while($row=mysql_fetch_array($sql_estrategia2))
			{ 
            	echo "<tr id='$row[0]' class='$j'>";
             	echo "<td>$row[0]</td>";
			    echo "<td>$row[1]</td>";
			    echo "<td>$row[2]</td>";
			    echo "<td>$row[3]</td>";
			    echo "<td>$row[4]</td>";
                echo "<td><center><input type='checkbox' class='seleccione_estrategia' id='dos$j' />";
				echo "</center></td></td></tr>";
			    $j++;
             }
		echo "</tbody></table>";
        } 
		else 
		{
			echo "No hay estrategias creadas en el Tipo seleccionado.";
        }
	}
	public function asignarEstrategia($ide)
	{
		$this->ide=$ide;
	}
	public function mostrarEstrategia()
	{	
		include("../../db/db.php");
		include("../../class/db/DB.php");
		$db = new DB();
		$SqlTipoSistema = "select tipoSistema from focoConfig";
		$TipoSistema = $db->select($SqlTipoSistema);
		$TipoSistema = $TipoSistema[0]["tipoSistema"];
		$sql_num = $db->select("SELECT id FROM SIS_Querys_Estrategias WHERE  id_estrategia=$this->ide AND terminal=1");
		if(count($sql_num)>0)
		{
			echo '<table id="demo-dt-basic" class="table table-striped table-bordered" cellspacing="0" width="100%">';
			echo '<thead><tr><th>ID Cola</th><th>Cola</th><th class="min-desktop"><center>Cantidad de Registros</center></th>';
			echo '<th class="min-desktop"><center>Monto</center></th><th class="min-desktop"><center>Prioridad</center></th>';
			echo '<th class="min-desktop"><center>Comentario</center></th>';
			if($TipoSistema == "1"){
				echo '<th class=""><center>Cautiva</center></th>';
			}
			echo '<th class=""><center>Asignar</center></th>';
			//echo '<th class="min-desktop"><center>Comentario</center></th><th class="min-desktop"><center>Descargar Fonos <br>por Categoria</center></th><th class="min-desktop"><center>Descargar IVR<br> por Categoria</center></th><th class="min-desktop"><center>Descargar <br>Consolidado</center></th><th class="min-desktop"><center>Activar</center></th><th class=""><center>Asignar</center></th>';
			echo '</tr></thead><tbody>';
			$k = 1;
			$sql_estrategia = $db->select("SELECT id,cola,cantidad,monto,prioridad,comentario,discador FROM SIS_Querys_Estrategias WHERE  id_estrategia=$this->ide AND terminal=1");
			foreach($sql_estrategia as $Estrategia)
			{ 
			
				$id_query = $Estrategia['id'];
				$discador= $Estrategia['discador'];

				echo "<tr id='".$id_query."' class='$k'>";
				echo "<td>".$id_query."</td>";
				echo "<td>".$Estrategia['cola']."</td>";
				echo "<td><center>".$Estrategia['cantidad']."</center></td>";
				echo "<td><center>".$Estrategia['monto']."</center></td>";
				echo "<td><center>".$Estrategia['prioridad']."</center></td>";
				echo "<td><center>".$Estrategia['comentario']."</center></td>";
				/*echo "<td><center>";
				$PuedeAsignar = "Disabled";
				if($discador==1)
				{	
					echo "<input  type='checkbox' checked  class='activar_cola'  value='1' id='k$id_query' />";
					$PuedeAsignar = "";
				}
				else
				{
					echo "<input  type='checkbox'   class='activar_cola'  value='0' id='k$id_query' />";
				}	
				$k++;
				echo "</center></td>";*/
				if($TipoSistema == "1"){
					echo "<td><center><i class='fa fa-download fa-lg Cautiva'></i></center></td>";
				}
				echo "<td class='AsignadorBtn'><center><i class='fa fa-download fa-lg Asignar'></i></center></td></tr>";
			}
			echo "</tbody></table>"; 
		}
		else
		{
			echo "No hay <b>colas terminales</b> en esta estrategia.";
		}
	}	
	public function activarCola($id)
	{
		include("../../db/db.php");
		$this->id=$id;
		$sel_cola = mysql_query("SELECT cola,query,Id_Cedente FROM SIS_Querys_Estrategias WHERE id=$this->id");
		while($row=mysql_fetch_array($sel_cola))
		{
			$nombre_cola = $row[0];
			$query = $row[1];
			$cedente= $row[2];

		}
		$prefijo = "QR_".$cedente."_".$this->id;
		$ver_prefijo= mysql_query("show tables like '$prefijo'");

 		if(mysql_num_rows ($ver_prefijo)>0)
 		{	
 			echo "1";
 		}
 		else
 		{	
			
			$fecha_traza = date('Y-m-d');
			$crear = "CREATE TABLE $prefijo (id INT NOT NULL AUTO_INCREMENT, Rut INT  ,llamado INT DEFAULT '0' ,KEY (id))";
			mysql_query($crear);
			mysql_query("ALTER TABLE $prefijo ADD UNIQUE KEY `rut` (`Rut`)");
			mysql_query("INSERT INTO $prefijo (Rut) $query");
			//mysql_query("INSERT INTO Trazabilidad_Rut_Cola (Rut) SELECT Rut FROM $prefijo");
			//mysql_query("UPDATE Trazabilidad_Rut_Cola SET Cola_Trabajo='$nombre_cola' , Fecha_Traza='$fecha_traza',Prefijo='$prefijo' WHERE Cola_Trabajo IS NULL AND Fecha_Traza IS NULL");
			mysql_query("UPDATE SIS_Querys_Estrategias SET discador=1 WHERE id=$this->id");
		}	
		
	}
	public function desactivarCola($id)
	{
		include("../../db/db.php");
		$this->id=$id;
		$sel_cola = mysql_query("SELECT cola,query,Id_Cedente FROM SIS_Querys_Estrategias WHERE id=$this->id");
		while($row=mysql_fetch_array($sel_cola))
		{
			$nombre_cola = $row[0];
			$query = $row[1];
			$cedente= $row[2];

		}
		$prefijo = "QR_".$cedente."_".$this->id;
		$ver_prefijo= mysql_query("show tables like '$prefijo'");

 		if(mysql_num_rows ($ver_prefijo)>0)
 		{	
 			
 			$query = "DROP TABLE $prefijo";
 			mysql_query($query);
 			mysql_query("UPDATE SIS_Querys_Estrategias SET discador=0 WHERE id=$this->id");
 			echo "1";
 		}
 		else
 		{
 			echo "0";
 		}
 	}

 	public function actualizarCola($Cola = ""){
		include("../../db/db.php");
		$WhereCola = $Cola != "" ? " and id='".$Cola."'" : "";
		$query_discador = mysql_query("SELECT id,query,Id_Cedente,cola FROM SIS_Querys_Estrategias WHERE discador=1 ".$WhereCola);
		while($row = mysql_fetch_array($query_discador))
		{
			$id = $row[0];
			$query = $row[1];
			$cedente = $row[2];
			$nombre_cola = $row[3];
			$prefijo = "QR_".$cedente."_".$id;
			//$query_drop = "DROP TABLE $prefijo";
 			//mysql_query($query_drop);
 			$fecha_traza = date('Y-m-d');
			//$crear = "CREATE TABLE $prefijo (id INT NOT NULL AUTO_INCREMENT, Rut INT  ,llamado INT DEFAULT '0' ,KEY (id))";
			//mysql_query($crear);
			mysql_query("TRUNCATE TABLE $prefijo");
			$MysqlIndex = mysql_query("select case when ((SELECT COUNT(*) FROM information_schema.statistics WHERE TABLE_SCHEMA = 'foco' AND TABLE_NAME = '$prefijo' AND INDEX_NAME = 'rut') = 0) then 'ALTER TABLE $prefijo ADD UNIQUE KEY `rut` (`Rut`)' else '' end as IndexSql");
			while($index = mysql_fetch_array($MysqlIndex)){
				if($index[0] != ""){
					mysql_query($index[0]);
				}
			}
			mysql_query("INSERT IGNORE INTO $prefijo (Rut) $query");
			mysql_query("DELETE FROM $prefijo WHERE  NOT Rut IN ($query)");
			mysql_query("INSERT INTO Trazabilidad_Rut_Cola (Rut) SELECT Rut FROM $prefijo");
			mysql_query("UPDATE Trazabilidad_Rut_Cola SET Cola_Trabajo='$nombre_cola' , Fecha_Traza='$fecha_traza',Prefijo='$prefijo' WHERE Cola_Trabajo IS NULL AND Fecha_Traza IS NULL ");
			

		}	


	}
	function getEntidades($TipoEntidad, $Array){
		$db = new Db();
		$ToReturn = "";
		$In = "";
		switch($TipoEntidad){
			case '1':
				if($Array != ""){
					$In =  "and empresa_externa.IdEmpresaExterna not in (".$Array.")";
				}
				$sql = "select * from empresa_externa where IdCedente='".$_SESSION['cedente']."' ".$In."";
				$Supervisores = $db->select($sql);
				foreach($Supervisores as $Supervisor){
					$ToReturn .= "<option value='EE_".$Supervisor["IdEmpresaExterna"]."'>".$Supervisor["Nombre"]."</option>";
				}
			break;
			case '2':
				if($Array != ""){
					$In =  "and Personal.Id_Personal not in (".$Array.")";
				}
				$ToReturn .= "<optgroup label='Supervisor'>";
					//echo $Array;
					//echo implode(",",$Array);
					$sql = "select Personal.* from Usuarios inner join Personal on Personal.Nombre_Usuario = Usuarios.usuario where Usuarios.nivel = '2' ".$In."";
					$Supervisores = $db->select($sql);
					foreach($Supervisores as $Supervisor){
						$ToReturn .= "<option value='S_".$Supervisor["Id_Personal"]."'>".$Supervisor["Nombre"]."</option>";
					}
				$ToReturn .= "</optgroup>";
				$ToReturn .= "<optgroup label='Ejecutivo'>";
					$sql = "select Personal.* from Usuarios inner join Personal on Personal.Nombre_Usuario = Usuarios.usuario where Usuarios.nivel = '4' ".$In."";
					$Supervisores = $db->select($sql);
					foreach($Supervisores as $Supervisor){
						if($Supervisor["Nombre"] != ""){
							$ToReturn .= "<option value='E_".$Supervisor["Id_Personal"]."'>".$Supervisor["Nombre"]."</option>";
						}
					}
				$ToReturn .= "</optgroup>";
			break;
			case '3':
				if($Array != ""){
					$In =  "and grupos.IdGrupo not in (".$Array.")";
				}
				$sql = "select * from grupos where IdCedente='".$_SESSION['cedente']."' ".$In."";
				$Supervisores = $db->select($sql);
				foreach($Supervisores as $Supervisor){
					$ToReturn .= "<option value='G_".$Supervisor["IdGrupo"]."'>".$Supervisor["Nombre"]."</option>";
				}
			break;
		}
		return $ToReturn;
	}
	function SeparateByRuts($idCola, $Rows, $DropTables = true){
		$Algoritmo = "0";
		$db = new DB();
		$Cedente = $_SESSION['cedente'];
		$SqlCola = "select Rut from QR_".$Cedente."_".$idCola;
		$Ruts = $db->select($SqlCola);
		$NumRuts = count($Ruts);
		$CantRutsAvailable = $NumRuts;
		$ArrayAsignacion = array();
		$Prefix = "QR_".$Cedente."_".$idCola."_";
		if($DropTables){
			$this->DeleteTablesFromCola($Prefix);
		}
		foreach($Rows as $Row){
			$Nombre = $Row[0];
			$Porcentaje = $Row[1];
			$Porcentaje = $Porcentaje / 100;
			$Foco = $Row[3];
			$Id = $Row[2];
			$TotalRuts = ceil($NumRuts * $Porcentaje);
			if($CantRutsAvailable <= $TotalRuts){
				$TotalRuts = $CantRutsAvailable;
			}
			$CantRutsAvailable = $CantRutsAvailable - $TotalRuts;
			$ArrayAsignacion[$Id]["Porcentaje"] = $Porcentaje * 100;
			$ArrayAsignacion[$Id]["TotalRuts"] = $TotalRuts;
			$ArrayAsignacion[$Id]["Ruts"] = array();
			$Cont = 1;
			foreach($Ruts as $Key => $Rut){
				if($Cont <= $TotalRuts){
					array_push($ArrayAsignacion[$Id]["Ruts"],$Rut["Rut"]);
					$Cont++;
					unset($Ruts[$Key]);
				}else{
					break;
				}
			}
			$TableName = "`QR_".$Cedente."_".$idCola."_".$Id."_".($Porcentaje * 100)."_".$Algoritmo."_".$Foco."`";
			$RutsArray = $ArrayAsignacion[$Id]["Ruts"];
			foreach($RutsArray as $Key => $Rut){
				$RutsArray[$Key] = "(".$Rut.")";
			}
			$RutsImplode = implode(",",$RutsArray);
			$fecha_traza = date('Y-m-d');
			if($DropTables){
				$crear = "CREATE TABLE $TableName (id INT NOT NULL AUTO_INCREMENT, Rut INT, estado INT NOT NULL DEFAULT 0, orden INT, KEY (id), fechaGestion datetime)";
				$InsertTable = $db->query($crear);
				if($InsertTable){
					$db->query("ALTER TABLE $TableName ADD UNIQUE KEY `rut` (`Rut`)");
					$SqlInsert = "INSERT INTO $TableName (Rut) values ".$RutsImplode;
					$Insert = $db->query($SqlInsert);
				}
			}else{
				$SqlInsert = "INSERT INTO $TableName (Rut) values ".$RutsImplode;
				$Insert = $db->query($SqlInsert);
			}
		}
		$Tipos = array();
		$Tipos["Tipo1"] = array();
		$Tipos["Tipo2"] = array();
		foreach($ArrayAsignacion as $key => $Entidad){
			$Ruts = $Entidad["Ruts"];
			$File = array();
				$File[$key] = $this->CrearArchivoAsignacion($key,$Ruts);
			array_push($Tipos["Tipo1"],$File[$key]);
			$File = array();
				$File[$key] = $this->CrearArchivoAsignacionTipo2($key,$Ruts);
			array_push($Tipos["Tipo2"],$File[$key]);
		}
		echo json_encode($Tipos);
	}
	function SeparateByDeuda($idCola, $Rows, $DropTables = true){
		$Algoritmo = "1";
		$db = new DB();
		$Rows = array_sort($Rows, 1, SORT_DESC);
		$Cedente = $_SESSION['cedente'];
		$SqlCola = "select Rut as Rut, Sum(Monto_Mora) as Deuda from Deuda where Id_Cedente = '".$Cedente."' and Rut in (select Rut from QR_".$Cedente."_".$idCola.") Group By Rut Order by Deuda DESC";
		$Deudas = $db->select($SqlCola);
		$CantTotalDeudas = $this->DeudaTotal($Deudas);
		$CantDeudasAvailable = $CantTotalDeudas;
		$ArrayAsignacion = array();
		$Prefix = "QR_".$Cedente."_".$idCola."_";
		if($DropTables){
			$this->DeleteTablesFromCola($Prefix);
		}
		foreach($Rows as $Row){
			$Nombre = $Row[0];
			$Porcentaje = $Row[1];
			$Porcentaje = $Porcentaje / 100;
			$Foco = $Row[3];
			$Id = $Row[2];
			$TotalDeudas = ($CantTotalDeudas * $Porcentaje);
			if($CantDeudasAvailable <= $TotalDeudas){
				$TotalDeudas = $CantDeudasAvailable;
			}
			$ArrayAsignacion[$Id]["Ruts"] = array();
			$SumDeuda = 0;
			foreach($Deudas as $Key => $Deuda){
				if($SumDeuda <= $TotalDeudas){
					$SumDeuda += $Deuda["Deuda"];
					array_push($ArrayAsignacion[$Id]["Ruts"],$Deuda["Rut"]);
					unset($Deudas[$Key]);
				}else{
					break;
				}
			}
			$TableName = "`QR_".$Cedente."_".$idCola."_".$Id."_".($Porcentaje * 100)."_".$Algoritmo."_".$Foco."`";
			$RutsArray = $ArrayAsignacion[$Id]["Ruts"];
			foreach($RutsArray as $Key => $Rut){
				$RutsArray[$Key] = "(".$Rut.")";
			}
			$RutsImplode = implode(",",$RutsArray);
			$fecha_traza = date('Y-m-d');
			if($DropTables){
				$crear = "CREATE TABLE $TableName (id INT NOT NULL AUTO_INCREMENT, Rut INT, estado INT NOT NULL DEFAULT 0, orden INT, KEY (id), fechaGestion datetime)";
				$InsertTable = $db->query($crear);
				if($InsertTable){
					$db->query("ALTER TABLE $TableName ADD UNIQUE KEY `rut` (`Rut`)");
					$SqlInsert = "INSERT INTO $TableName (Rut) values ".$RutsImplode;
					$Insert = $db->query($SqlInsert);
				}
			}else{
				$SqlInsert = "INSERT INTO $TableName (Rut) values ".$RutsImplode;
				$Insert = $db->query($SqlInsert);
			}
		}
		$Tipos = array();
		$Tipos["Tipo1"] = array();
		$Tipos["Tipo2"] = array();
		foreach($ArrayAsignacion as $key => $Entidad){
			$Ruts = $Entidad["Ruts"];
			$File = array();
				$File[$key] = $this->CrearArchivoAsignacion($key,$Ruts);
			array_push($Tipos["Tipo1"],$File[$key]);
			$File = array();
				$File[$key] = $this->CrearArchivoAsignacionTipo2($key,$Ruts);
			array_push($Tipos["Tipo2"],$File[$key]);
		}
		echo json_encode($Tipos);
	}
	function DeudaTotal($Deudas){
		$ToReturn = 0;
		foreach($Deudas as $Deuda){
			$ToReturn += $Deuda["Deuda"];
		}
		return $ToReturn;
	}
	function DeleteTablesFromCola($Prefix){
		$db = new DB();
		$SqlTables = "select TABLE_NAME as tabla from information_schema.TABLES where TABLE_SCHEMA='foco' and TABLE_NAME like '".$Prefix."%'";
		$Tables = $db->select($SqlTables);
		if(count($Tables) > 0){
			foreach($Tables as $Table){
				$Tabla = $Table["tabla"];
				$Sql = "drop table `".$Tabla."`";
				$db->query($Sql);
			}
		}
	}
	
	function CrearArchivoAsignacion($fileName,$Ruts,$Cedente = "",$Cola = "",$Download = true){
		$objPHPExcel = new PHPExcel();
		$db = new DB();
		if($Cedente == ""){
			$Cedente = $_SESSION['cedente'];
		}
		$fileName = $this->getEntidadName($fileName)."_Foco";
		ob_start();
		$objPHPExcel->
			getProperties()
				->setCreator("FOCO Estrategico")
				->setLastModifiedBy("FOCO Estrategico");
		
		$objPHPExcel->removeSheetByIndex(
			$objPHPExcel->getIndex(
				$objPHPExcel->getSheetByName('Worksheet')
			)
		);

		$NextSheet = 0;

		$objPHPExcel->createSheet($NextSheet);
		$objPHPExcel->setActiveSheetIndex($NextSheet);
		$objPHPExcel->getActiveSheet()->setTitle('Personas');

		$objPHPExcel->
		setActiveSheetIndex($NextSheet)
                ->setCellValueByColumnAndRow(0,1,"Rut")
				->setCellValueByColumnAndRow(1,1,"DV")
				->setCellValueByColumnAndRow(2,1,"Nombre Completo");

		$RutsImplode = implode(",",$Ruts);
		if($RutsImplode != ""){
			$SqlPersonas = "select * from Persona where Rut in (".$RutsImplode.")";
			$Personas = $db->select($SqlPersonas);
			
			$Cont = 2;
			foreach($Personas as $Persona){
				$objPHPExcel->
				setActiveSheetIndex($NextSheet)
						->setCellValueByColumnAndRow(0,$Cont,$Persona["Rut"])
						->setCellValueByColumnAndRow(1,$Cont,$Persona["Digito_Verificador"])
						->setCellValueByColumnAndRow(2,$Cont,$Persona["Nombre_Completo"]);
				$Cont++;
			}
		}

		$NextSheet++;

		$objPHPExcel->createSheet($NextSheet);
		$objPHPExcel->setActiveSheetIndex($NextSheet);
		$objPHPExcel->getActiveSheet()->setTitle('Deudas');

		$objPHPExcel->
		setActiveSheetIndex($NextSheet)
                ->setCellValueByColumnAndRow(0,1,"Rut")
				->setCellValueByColumnAndRow(1,1,"Tipo Deudor")
				->setCellValueByColumnAndRow(2,1,"Producto")
				->setCellValueByColumnAndRow(3,1,"Numero Operacion")
				->setCellValueByColumnAndRow(4,1,"Segmento")
				->setCellValueByColumnAndRow(5,1,"Tramo Dias Mora")
				->setCellValueByColumnAndRow(6,1,"Fecha Vencimiento")
				->setCellValueByColumnAndRow(7,1,"Monto Mora")
				->setCellValueByColumnAndRow(8,1,"Dias Mora")
				->setCellValueByColumnAndRow(9,1,"Fecha Ingreso")
				->setCellValueByColumnAndRow(10,1,"Cuenta");

		if($RutsImplode != ""){
			$SqlDeudas = "select * from Deuda where Rut in (".$RutsImplode.") and Id_Cedente = '".$Cedente."'";
			$Deudas = $db->select($SqlDeudas);
			
			$Cont = 2;
			foreach($Deudas as $Deuda){
				$objPHPExcel->
				setActiveSheetIndex($NextSheet)
						->setCellValueByColumnAndRow(0,$Cont,$Deuda["Rut"])
						->setCellValueByColumnAndRow(1,$Cont,$Deuda["Tipo_Deudor"])
						->setCellValueByColumnAndRow(2,$Cont,$Deuda["Producto"])
						->setCellValueByColumnAndRow(3,$Cont,$Deuda["Numero_Operacion"])
						->setCellValueByColumnAndRow(4,$Cont,$Deuda["Segmento"])
						->setCellValueByColumnAndRow(5,$Cont,$Deuda["Tramo_Dias_Mora"])
						->setCellValueByColumnAndRow(6,$Cont,$Deuda["Fecha_Vencimiento"])
						->setCellValueByColumnAndRow(7,$Cont,$Deuda["Monto_Mora"])
						->setCellValueByColumnAndRow(8,$Cont,$Deuda["Dias_Mora"])
						->setCellValueByColumnAndRow(9,$Cont,$Deuda["Fecha_Ingreso"])
						->setCellValueByColumnAndRow(10,$Cont,$Deuda["Cuenta"]);
				$Cont++;
			}
		}

		$NextSheet++;

		$objPHPExcel->createSheet($NextSheet);
		$objPHPExcel->setActiveSheetIndex($NextSheet);
		$objPHPExcel->getActiveSheet()->setTitle('Fonos');

		$objPHPExcel->
		setActiveSheetIndex($NextSheet)
                ->setCellValueByColumnAndRow(0,1,"Rut")
				->setCellValueByColumnAndRow(1,1,"Tipo Fono")
				->setCellValueByColumnAndRow(2,1,"Fono");

		if($RutsImplode != ""){
			$SqlFonos = "select * from fono_cob where Rut in (".$RutsImplode.")";
			$Fonos = $db->select($SqlFonos);
			
			$Cont = 2;
			foreach($Fonos as $Fono){
				$objPHPExcel->
				setActiveSheetIndex($NextSheet)
						->setCellValueByColumnAndRow(0,$Cont,$Fono["Rut"])
						->setCellValueByColumnAndRow(1,$Cont,$Fono["tipo_fono"])
						->setCellValueByColumnAndRow(2,$Cont,$Fono["formato_subtel"]);
				$Cont++;
			}
		}

		$NextSheet++;

		if($RutsImplode != ""){
			$SqlDirecciones = "select * from Direcciones where Rut in (".$RutsImplode.")";
			$Direcciones = $db->select($SqlDirecciones);
			if(count($Direcciones) > 0){
				$objPHPExcel->createSheet($NextSheet);
				$objPHPExcel->setActiveSheetIndex($NextSheet);
				$objPHPExcel->getActiveSheet()->setTitle('Direcciones');

				$objPHPExcel->
				setActiveSheetIndex($NextSheet)
						->setCellValueByColumnAndRow(0,1,"Rut")
						->setCellValueByColumnAndRow(1,1,"Direccion")
						->setCellValueByColumnAndRow(2,1,"Codigo Postal")
						->setCellValueByColumnAndRow(3,1,"Complemento Direccion");

				$Cont = 2;
				foreach($Direcciones as $Direccion){
					$objPHPExcel->
					setActiveSheetIndex($NextSheet)
							->setCellValueByColumnAndRow(0,$Cont,$Direccion["Rut"])
							->setCellValueByColumnAndRow(1,$Cont,$Direccion["Direccion"])
							->setCellValueByColumnAndRow(2,$Cont,$Direccion["Complemento_Direccion"])
							->setCellValueByColumnAndRow(3,$Cont,$Direccion["Codigo_postal"]);
					$Cont++;
				}

				$NextSheet++;
			}
		}

		if($RutsImplode != ""){
			$SqlMails = "select * from Mail where Rut in (".$RutsImplode.")";
			$Mails = $db->select($SqlMails);
			if(count($Mails) > 0){
				$objPHPExcel->createSheet($NextSheet);
				$objPHPExcel->setActiveSheetIndex($NextSheet);
				$objPHPExcel->getActiveSheet()->setTitle('Mails');

				$objPHPExcel->
				setActiveSheetIndex($NextSheet)
						->setCellValueByColumnAndRow(0,1,"Rut")
						->setCellValueByColumnAndRow(1,1,"Correo Electronico");

				$Cont = 2;
				foreach($Mails as $Mail){
					$objPHPExcel->
					setActiveSheetIndex($NextSheet)
							->setCellValueByColumnAndRow(0,$Cont,$Mail["Rut"])
							->setCellValueByColumnAndRow(1,$Cont,$Mail["correo_electronico"]);
					$Cont++;
				}

				$NextSheet++; 
			}
		}
		
		$objPHPExcel->setActiveSheetIndex(0);

		if($Download){
			header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment; filename="'.$fileName.'.xlsx"');
			header('Cache-Control: max-age=0');
			$objWriter=PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel2007');
			$objWriter->save('php://output');
			$xlsData = ob_get_contents();
			ob_end_clean();
			$response =  array(
				'fileName' => utf8_encode($fileName),
				'file' => "data:application/vnd.ms-excel;base64,".base64_encode($xlsData)
			);
		}else{
			$objWriter=PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel2007');
			if (!file_exists("../task/asignaciones/".$Cedente."/".$Cola)){
				mkdir("../task/asignaciones/".$Cedente."/".$Cola, 0777, true);
			}
			$objWriter->save('../task/asignaciones/'.$Cedente."/".$Cola.'/'.$fileName.".xlsx");
			$response = array("result" => true);
		}
		
		
		
		return $response;
	}
	function getAsignaciones($idCola){
		$ToReturn = array();
		$db = new DB();
		$Cedente = $_SESSION['cedente'];
		$Prefix = "QR_".$Cedente."_".$idCola."_";
		$SqlTables = "select TABLE_NAME as tabla from information_schema.TABLES where TABLE_SCHEMA='foco' and TABLE_NAME like '".$Prefix."%'";
		$Tables = $db->select($SqlTables);
		foreach($Tables as $Table){
			$Array = array();
			$Tabla = $Table["tabla"];
			$ArrayTabla = explode("_",$Tabla);
			$PrefijoTabla = $ArrayTabla[0];
			$Cedente = $ArrayTabla[1];
			$Cola = $ArrayTabla[2];
			$TipoEntidad = $ArrayTabla[3];
			$idEntidad = $ArrayTabla[4];
			$Porcentaje = $ArrayTabla[5];
			$TipoAsignacion = $ArrayTabla[6];
			$Foco = $ArrayTabla[7];
			$Entidad = $TipoEntidad."_".$idEntidad;
			$Nombre = "";
			$Tipo = "";
			switch($TipoEntidad){
				case 'S':
				case 'E':
					$SqlNombre = "select * from Personal where Id_Personal='".$idEntidad."'";
					$Nombre = $db->select($SqlNombre);
					$Nombre = $Nombre[0]["Nombre"];
					$Tipo = "Personal";
				break;
				case 'EE':
					$SqlNombre = "select * from empresa_externa where IdEmpresaExterna='".$idEntidad."'";
					$Nombre = $db->select($SqlNombre);
					$Nombre = $Nombre[0]["Nombre"];
					$Tipo = "Empresa Externa";
				break;
				case 'G':
					$SqlNombre = "select * from grupos where IdGrupo='".$idEntidad."'";
					$Nombre = $db->select($SqlNombre);
					$Nombre = $Nombre[0]["Nombre"];
					$Tipo = "Grupo";
				break;
			}
			$Array["Nombre"] = utf8_encode($Nombre);
			$Array["Tipo"] = $Tipo;
			$Array["Porcentaje"] = $Porcentaje;
			$Array["id"] = $Entidad;
			$Array["Foco"] = $Foco;
			$Array["Actions"] = $Entidad;
			array_push($ToReturn,$Array);
		}
		return $ToReturn;
	}
	function getAsignacionesArchivos($idCola,$Tipo){
		$ToReturn = array();
		$db = new DB();
		$Cedente = $_SESSION['cedente'];
		$Prefix = "QR_".$Cedente."_".$idCola."_";
		$SqlTables = "select TABLE_NAME as tabla from information_schema.TABLES where TABLE_SCHEMA='foco' and TABLE_NAME like '".$Prefix."%'";
		$Tables = $db->select($SqlTables);
		foreach($Tables as $Table){
			$Array = array();
			$Tabla = "`".$Table["tabla"]."`";
			$ArrayTabla = explode("_",$Tabla);
			$PrefijoTabla = $ArrayTabla[0];
			$Cedente = $ArrayTabla[1];
			$Cola = $ArrayTabla[2];
			$TipoEntidad = $ArrayTabla[3];
			$idEntidad = $ArrayTabla[4];
			$Porcentaje = $ArrayTabla[5];
			$TipoAsignacion = $ArrayTabla[6];
			$Foco = $ArrayTabla[7];
			$Entidad = $TipoEntidad."_".$idEntidad;
			$SqlTabla = "select Rut from ".$Tabla;
			$Ruts = $db->select($SqlTabla);
			$RutsTmp = array();
			foreach($Ruts as $Rut){
				array_push($RutsTmp,$Rut["Rut"]);
			}
			$ToReturn[$Entidad] = array();
			switch($Tipo){
				case '1':
					$File = $this->CrearArchivoAsignacion($Entidad,$RutsTmp);
				break;
				case '2':
					$File = $this->CrearArchivoAsignacionTipo2($Entidad,$RutsTmp);
				break;
			}
			array_push($ToReturn[$Entidad],$File);
		}
		return $ToReturn;
	}
	function CrearArchivoAsignacionTipo2($fileName,$Ruts,$Cedente = "",$Cola = "",$Download = true){
		$objPHPExcel = new PHPExcel();
		$db = new DB();
		if($Cedente == ""){
			$Cedente = $_SESSION['cedente'];
		}
		$fileName = $this->getEntidadName($fileName)."_Dial";
		ob_start();
		$objPHPExcel->
			getProperties()
				->setCreator("FOCO Estrategico")
				->setLastModifiedBy("FOCO Estrategico");
		
		$objPHPExcel->removeSheetByIndex(
			$objPHPExcel->getIndex(
				$objPHPExcel->getSheetByName('Worksheet')
			)
		);

		$NextSheet = 0;

		$objPHPExcel->createSheet($NextSheet);
		$objPHPExcel->setActiveSheetIndex($NextSheet);
		$objPHPExcel->getActiveSheet()->setTitle('Personas');

		$Sql = "select * from Columnas_Asignacion_Dial where Id_Mandante in (select Id_Mandante from mandante_cedente where Id_Cedente='".$Cedente."') ORDER BY Prioridad";
		$ColumnasAsignacion = $db->select($Sql);
		$Col = 0;
		$ArrayStackedColumns = array();
		foreach($ColumnasAsignacion as $ColumnaAsignacion){
			$objPHPExcel->
			setActiveSheetIndex($NextSheet)
					->setCellValueByColumnAndRow($Col,1,$ColumnaAsignacion["Nombre"]);
			if($ColumnaAsignacion["Tipo_Campo"] == "1"){
				array_push($ArrayStackedColumns,$ColumnaAsignacion["Campo"]);
			}
			$Col++;
		}

		$SqlColumnas = "select SIS_Columnas_Estrategias.columna from SIS_Tablas inner join SIS_Columnas_Estrategias on SIS_Columnas_Estrategias.id_tabla = SIS_Tablas.id where SIS_Tablas.nombre = 'Deuda' and FIND_IN_SET('".$Cedente."',SIS_Columnas_Estrategias.Id_Cedente) order by SIS_Columnas_Estrategias.columna";
		$Columnas = $db->select($SqlColumnas);

		$ArrayDeudaSearch = array();
		foreach($Columnas as $Columna){
			$key = array_search($Columna["columna"],$ArrayStackedColumns);
			if($key === FALSE){
				/*$objPHPExcel->
				setActiveSheetIndex($NextSheet)
					->setCellValueByColumnAndRow($Col,1,$Columna["columna"]);
				$Col++;*/
				array_push($ArrayDeudaSearch,$Columna['columna']);
			}
		}

		$ArrayDeudaSearchImplode = implode(",",$ArrayDeudaSearch);
		$Cont = 2;
		foreach($Ruts as $Rut){
			
			$SqlFonos = "select fono_cob.*, SIS_Categoria_Fonos.tipo_var as Gestion from SIS_Categoria_Fonos inner join fono_cob on fono_cob.color = SIS_Categoria_Fonos.color where fono_cob.rut = '".$Rut."' and SIS_Categoria_Fonos.sel='0' order by SIS_Categoria_Fonos.prioridad limit 3";
			$Fonos = $db->select($SqlFonos);
			$FonosTmp = array();
			foreach($Fonos as $Fono){
				array_push($FonosTmp,$Fono["formato_subtel"]."_".$Fono["Gestion"]);
			}
			$FonoEspecial = isset($FonosTmp[0]) ? substr($FonosTmp[0],0,strpos($FonosTmp[0],"_")) : "";
			$GestionEspecial = isset($FonosTmp[0]) ? substr($FonosTmp[0],strpos($FonosTmp[0],"_") + 1,strlen($FonosTmp[0])) : "";
			$ColorEspecial = isset($FonosTmp[0]) ? substr($FonosTmp[0],strripos($FonosTmp[0],"_") + 1,strlen($FonosTmp[0])) : "";

			$Fono2 = isset($FonosTmp[1]) ? substr($FonosTmp[1],0,strpos($FonosTmp[1],"_")) : "";
			$Gestion2 = isset($FonosTmp[1]) ? substr($FonosTmp[1],strpos($FonosTmp[1],"_") + 1,strlen($FonosTmp[1])) : "";
			$ColorFono2 = isset($FonosTmp[1]) ? substr($FonosTmp[1],strripos($FonosTmp[1],"_") + 1,strlen($FonosTmp[1])) : "";

			$Fono3 = isset($FonosTmp[2]) ? substr($FonosTmp[2],0,strpos($FonosTmp[2],"_")) : "";
			$Gestion3 = isset($FonosTmp[2]) ? substr($FonosTmp[2],strpos($FonosTmp[2],"_") + 1,strlen($FonosTmp[2])) : "";
			$ColorFono3 = isset($FonosTmp[3]) ? substr($FonosTmp[3],strripos($FonosTmp[3],"_") + 1,strlen($FonosTmp[3])) : "";
			
			$SqlMejorGestion = "select * from Mejor_Gestion_Historica where Rut='".$Rut."'";
			$MejorGestion = $db->select($SqlMejorGestion);
			$MejorGestionTexto = "";
			$MejorGestionFecha = "";
			if(count($MejorGestion) > 0){
				$SqlTipoContacto = "select * from Tipo_Contacto where Id_TipoContacto='".$MejorGestion[0]["Id_TipoGestion"]."'";
				$TipoContacto = $db->select($SqlTipoContacto);
				if(count($TipoContacto) > 0){
					$MejorGestionTexto = $TipoContacto[0]["Nombre"];
					$MejorGestionFecha = $MejorGestion[0]["fechahora"];
				}
			}

			$SqlUltimaGestion = "select * from Ultima_Gestion_Historica where Rut='".$Rut."'";
			$UltimaGestion = $db->select($SqlUltimaGestion);
			$UltimaGestionTexto = "";
			$UltimaGestionFecha = "";
			$UltimaGestionObservacion = "";
			$UltimaGestionUsuario = "";
			if(count($UltimaGestion) > 0){
				$SqlTipoContacto = "select * from Tipo_Contacto where Id_TipoContacto='".$UltimaGestion[0]["Id_TipoGestion"]."'";
				$TipoContacto = $db->select($SqlTipoContacto);
				$UltimaGestionObservacion = "";
				if(count($TipoContacto) > 0){
					$UltimaGestionTexto = $TipoContacto[0]["Nombre"];
					$UltimaGestionFecha = $UltimaGestion[0]["fecha_gestion"];
					$UltimaGestionObservacion = $UltimaGestion[0]["observacion"];
					$UltimaGestionUsuario = $UltimaGestion[0]["nombre_ejecutivo"];	
				}
			}

			$SqlUltimoCompromiso = "select * from Ultimo_Compromiso where Rut='".$Rut."'";
			$UltimoCompromiso = $db->select($SqlUltimoCompromiso);
			$UltimoCompromisoTexto = "";
			$UltimoCompromisoFecha = "";
			$UltimoCompromisoObservacion = "";
			if(count($UltimoCompromiso) > 0){
				$SqlTipoContacto = "select * from Tipo_Contacto where Id_TipoContacto='".$UltimoCompromiso[0]["Id_TipoGestion"]."'";
				$TipoContacto = $db->select($SqlTipoContacto);
				if(count($TipoContacto) > 0){
					$UltimoCompromisoTexto = $TipoContacto[0]["Nombre"];
					$UltimoCompromisoFecha = $UltimoCompromiso[0]["fec_compromiso"];
					$UltimoCompromisoObservacion = $UltimoCompromiso[0]["observacion"];	
				}
			}

			$FechaPeriodo = $this->getFechasPeriodosCargas();

			$SqlCantidadGestiones = "select count(*) as Cantidad from gestion_ult_trimestre where rut_cliente='".$Rut."' and fecha_gestion between '".$FechaPeriodo["Desde"]."' and '".$FechaPeriodo["Hasta"]."' and find_in_set(cedente,(select group_concat(Lista_Vicidial) from mandante_cedente where Id_Cedente='".$Cedente."'))";
			//$SqlCantidadGestiones = "select count(*) as Cantidad from gestion_ult_trimestre where rut_cliente='".$Rut."'";
			$CantidadGestiones = $db->select($SqlCantidadGestiones);
			if(count($CantidadGestiones) > 0){
				$CantidadGestiones = $CantidadGestiones[0]["Cantidad"];
			}
			
			$SqlDeudas = "select ".$ArrayDeudaSearchImplode." from Deuda where Id_Cedente = '".$Cedente."' and Rut = '".$Rut."' LIMIT 1";
			$Deudas = $db->select($SqlDeudas);
			foreach($Deudas as $Deuda){
				$Col = 0;
				foreach($ColumnasAsignacion as $ColumnaAsignacion){
					$Operacion = $ColumnaAsignacion["Operacion"];
					$Tabla = $ColumnaAsignacion["Tabla"];
					$Campo = $ColumnaAsignacion["Campo"];
					$Campo = $Operacion != "" ? $Operacion."(".$Campo.")" : $Campo;
					$TipoCampo = $ColumnaAsignacion["Tipo_Campo"];
					$Value = "";
					switch($TipoCampo){
						case '1':
							$Sql = "select ".$Campo." as Val from ".$Tabla." WHERE Rut='".$Rut."' AND FIND_IN_SET(".$Cedente.",Id_Cedente) LIMIT 1;";
							$Vals = $db->select($Sql);
							foreach($Vals as $Val){
								$Value = $Val["Val"];
							}
						break;
						case '2':
							switch($Campo){
								/*
									INICIO VARIABLES FONOS
								*/
								case 'fono_especial':
									$Value = $FonoEspecial;
								break;
								case 'gestion_fono_especial':
									$Value = $GestionEspecial;
								break;
								case 'color_fono_especial':
									$Value = $ColorEspecial;
								break;
								case 'fono_2':
									$Value = $Fono2;
								break;
								case 'gestion_fono_2':
									$Value = $Gestion2;
								break;
								case 'color_fono_2':
									$Value = $ColorFono2;
								break;
								case 'fono_3':
									$Value = $Fono3;
								break;
								case 'gestion_fono_3':
									$Value = $Gestion3;
								break;
								case 'color_fono_3':
									$Value = $ColorFono3;
								break;
								/*
									FIN VARIABLES FONOS
								*/

								/*
									INICIO VARIABLES FONOS
								*/
								case 'mejor_gestion_texto':
									$Value = $MejorGestionTexto;
								break;
								case 'mejor_gestion_fecha':
									$Value = $MejorGestionFecha;
								break;
								case 'ultima_gestion_texto':
									$Value = $UltimaGestionTexto;
								break;
								case 'ultima_gestion_fecha':
									$Value = $UltimaGestionFecha;
								break;
								case 'ultima_gestion_observacion':
									$Value = $UltimaGestionObservacion;
								break;
								case 'ultima_gestion_usuario':
									$Value = $UltimaGestionUsuario;
								break;
								case 'ultimo_compromiso_texto':
									$Value = $UltimoCompromisoTexto;
								break;
								case 'ultimo_compromiso_fecha':
									$Value = $UltimoCompromisoFecha;
								break;
								case 'ultimo_compromiso_observacion':
									$Value = $UltimoCompromisoObservacion;
								break;
								case 'cantidad_gestiones':
									$Value = $CantidadGestiones;
								break;
								/*
									FIN VARIABLES FONOS
								*/
							}
						break;
					}
					$objPHPExcel->
						setActiveSheetIndex($NextSheet)
							->setCellValueByColumnAndRow($Col,$Cont,$Value);
					$Col++;
				}
				$Cont++;
			}
		}
		$objPHPExcel->setActiveSheetIndex(0);

		if($Download){
			header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment; filename="'.$fileName.'.xlsx"');
			header('Cache-Control: max-age=0');
			$objWriter=PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel2007');
			$objWriter->save('php://output');
			$xlsData = ob_get_contents();
			ob_end_clean();
			$response =  array(
				'fileName' => utf8_encode($fileName),
				'file' => "data:application/vnd.ms-excel;base64,".base64_encode($xlsData)
			);
		}else{
			$objWriter=PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel2007');
			if (!file_exists("../task/asignaciones/".$Cedente."/".$Cola)){
				mkdir("../task/asignaciones/".$Cedente."/".$Cola, 0777, true);
			}
			$objWriter->save('../task/asignaciones/'.$Cedente."/".$Cola.'/'.$fileName.".xlsx");
			$response = array("result" => true);
		}
		return $response;
	}
	function DownloadReporteDialAsignacion(){
		$db = new DB();
		$objPHPExcel = new PHPExcel();

		$Cedente = $_SESSION['cedente'];

		$fileName = "Reporte asignacion ".date("d_m_Y");
		ob_start();
		$objPHPExcel->
			getProperties()
				->setCreator("FOCO Estrategico")
				->setLastModifiedBy("FOCO Estrategico");
		
		$objPHPExcel->removeSheetByIndex(
			$objPHPExcel->getIndex(
				$objPHPExcel->getSheetByName('Worksheet')
			)
		);

		$NextSheet = 0;

		$objPHPExcel->createSheet($NextSheet);
		$objPHPExcel->setActiveSheetIndex($NextSheet);
		$objPHPExcel->getActiveSheet()->setTitle('Personas');
		
		$Sql = "select * from Columnas_Asignacion_Dial where Id_Mandante in (select Id_Mandante from mandante_cedente where Id_Cedente='".$Cedente."') ORDER BY Prioridad";
		$ColumnasAsignacion = $db->select($Sql);
		$Col = 0;
		$ArrayStackedColumns = array();
		foreach($ColumnasAsignacion as $ColumnaAsignacion){
			$objPHPExcel->
			setActiveSheetIndex($NextSheet)
					->setCellValueByColumnAndRow($Col,1,$ColumnaAsignacion["Nombre"]);
			if($ColumnaAsignacion["Tipo_Campo"] == "1"){
				array_push($ArrayStackedColumns,$ColumnaAsignacion["Campo"]);
			}
			$Col++;
		}

		$SqlColumnas = "select SIS_Columnas_Estrategias.columna from SIS_Tablas inner join SIS_Columnas_Estrategias on SIS_Columnas_Estrategias.id_tabla = SIS_Tablas.id where SIS_Tablas.nombre = 'Deuda' and FIND_IN_SET('".$Cedente."',SIS_Columnas_Estrategias.Id_Cedente) order by SIS_Columnas_Estrategias.columna";
		$Columnas = $db->select($SqlColumnas);

		$ArrayDeudaSearch = array();
		foreach($Columnas as $Columna){
			$key = array_search($Columna["columna"],$ArrayStackedColumns);
			if($key === FALSE){
				/*$objPHPExcel->
				setActiveSheetIndex($NextSheet)
					->setCellValueByColumnAndRow($Col,1,$Columna["columna"]);
				$Col++;*/
				array_push($ArrayDeudaSearch,$Columna['columna']);
			}
		}
		$ArrayDeudaSearchImplode = implode(",",$ArrayDeudaSearch);

		$Cont = 2;

		$SqlDeudas = "select Rut from Deuda where Id_Cedente = '".$Cedente."' group by Rut";
		$Deudas = $db->select($SqlDeudas);
		foreach($Deudas as $Deuda){
			$Rut = $Deuda["Rut"];
			$SqlFonos = "select fono_cob.*, SIS_Categoria_Fonos.tipo_var as Gestion from SIS_Categoria_Fonos inner join fono_cob on fono_cob.color = SIS_Categoria_Fonos.color where fono_cob.rut = '".$Rut."' and SIS_Categoria_Fonos.sel='0' order by SIS_Categoria_Fonos.prioridad limit 3";
			$Fonos = $db->select($SqlFonos);
			$FonosTmp = array();
			foreach($Fonos as $Fono){
				array_push($FonosTmp,$Fono["formato_subtel"]."_".$Fono["Gestion"]);
			}
			$FonoEspecial = isset($FonosTmp[0]) ? substr($FonosTmp[0],0,strpos($FonosTmp[0],"_")) : "";
			$GestionEspecial = isset($FonosTmp[0]) ? substr($FonosTmp[0],strpos($FonosTmp[0],"_") + 1,strlen($FonosTmp[0])) : "";
			$ColorEspecial = isset($FonosTmp[0]) ? substr($FonosTmp[0],strripos($FonosTmp[0],"_") + 1,strlen($FonosTmp[0])) : "";

			$Fono2 = isset($FonosTmp[1]) ? substr($FonosTmp[1],0,strpos($FonosTmp[1],"_")) : "";
			$Gestion2 = isset($FonosTmp[1]) ? substr($FonosTmp[1],strpos($FonosTmp[1],"_") + 1,strlen($FonosTmp[1])) : "";
			$ColorFono2 = isset($FonosTmp[1]) ? substr($FonosTmp[1],strripos($FonosTmp[1],"_") + 1,strlen($FonosTmp[1])) : "";

			$Fono3 = isset($FonosTmp[2]) ? substr($FonosTmp[2],0,strpos($FonosTmp[2],"_")) : "";
			$Gestion3 = isset($FonosTmp[2]) ? substr($FonosTmp[2],strpos($FonosTmp[2],"_") + 1,strlen($FonosTmp[2])) : "";
			$ColorFono3 = isset($FonosTmp[3]) ? substr($FonosTmp[3],strripos($FonosTmp[3],"_") + 1,strlen($FonosTmp[3])) : "";
			
			$SqlMejorGestion = "select * from Mejor_Gestion_Historica where Rut='".$Rut."'";
			$MejorGestion = $db->select($SqlMejorGestion);
			$MejorGestionTexto = "";
			$MejorGestionFecha = "";
			if(count($MejorGestion) > 0){
				$SqlTipoContacto = "select * from Tipo_Contacto where Id_TipoContacto='".$MejorGestion[0]["Id_TipoGestion"]."'";
				$TipoContacto = $db->select($SqlTipoContacto);
				if(count($TipoContacto) > 0){
					$MejorGestionTexto = $TipoContacto[0]["Nombre"];
					$MejorGestionFecha = $MejorGestion[0]["fechahora"];
				}
			}

			$SqlUltimaGestion = "select * from Ultima_Gestion_Historica where Rut='".$Rut."'";
			$UltimaGestion = $db->select($SqlUltimaGestion);
			$UltimaGestionTexto = "";
			$UltimaGestionFecha = "";
			$UltimaGestionObservacion = "";
			$UltimaGestionUsuario = "";
			if(count($UltimaGestion) > 0){
				$SqlTipoContacto = "select * from Tipo_Contacto where Id_TipoContacto='".$UltimaGestion[0]["Id_TipoGestion"]."'";
				$TipoContacto = $db->select($SqlTipoContacto);
				$UltimaGestionObservacion = "";
				if(count($TipoContacto) > 0){
					$UltimaGestionTexto = $TipoContacto[0]["Nombre"];
					$UltimaGestionFecha = $UltimaGestion[0]["fecha_gestion"];
					$UltimaGestionObservacion = $UltimaGestion[0]["observacion"];
					$UltimaGestionUsuario = $UltimaGestion[0]["nombre_ejecutivo"];	
				}
			}

			$SqlUltimoCompromiso = "select * from Ultimo_Compromiso where Rut='".$Rut."'";
			$UltimoCompromiso = $db->select($SqlUltimoCompromiso);
			$UltimoCompromisoTexto = "";
			$UltimoCompromisoFecha = "";
			$UltimoCompromisoObservacion = "";
			if(count($UltimoCompromiso) > 0){
				$SqlTipoContacto = "select * from Tipo_Contacto where Id_TipoContacto='".$UltimoCompromiso[0]["Id_TipoGestion"]."'";
				$TipoContacto = $db->select($SqlTipoContacto);
				if(count($TipoContacto) > 0){
					$UltimoCompromisoTexto = $TipoContacto[0]["Nombre"];
					$UltimoCompromisoFecha = $UltimoCompromiso[0]["fec_compromiso"];
					$UltimoCompromisoObservacion = $UltimoCompromiso[0]["observacion"];	
				}
			}

			$FechaPeriodo = $this->getFechasPeriodosCargas();

			$SqlCantidadGestiones = "select count(*) as Cantidad from gestion_ult_trimestre where rut_cliente='".$Rut."' and fecha_gestion between '".$FechaPeriodo["Desde"]."' and '".$FechaPeriodo["Hasta"]."' and find_in_set(cedente,(select group_concat(Lista_Vicidial) from mandante_cedente where Id_Cedente='".$Cedente."'))";
			//$SqlCantidadGestiones = "select count(*) as Cantidad from gestion_ult_trimestre where rut_cliente='".$Rut."'";
			$CantidadGestiones = $db->select($SqlCantidadGestiones);
			if(count($CantidadGestiones) > 0){
				$CantidadGestiones = $CantidadGestiones[0]["Cantidad"];
			}

			$Col = 0;
			foreach($ColumnasAsignacion as $ColumnaAsignacion){
				$Operacion = $ColumnaAsignacion["Operacion"];
				$Tabla = $ColumnaAsignacion["Tabla"];
				$Campo = $ColumnaAsignacion["Campo"];
				$Campo = $Operacion != "" ? $Operacion."(".$Campo.")" : $Campo;
				$TipoCampo = $ColumnaAsignacion["Tipo_Campo"];
				$Value = "";
				switch($TipoCampo){
					case '1':
						$Sql = "select ".$Campo." as Val from ".$Tabla." WHERE Rut='".$Rut."' AND FIND_IN_SET(".$Cedente.",Id_Cedente) LIMIT 1;";
						$Vals = $db->select($Sql);
						foreach($Vals as $Val){
							$Value = $Val["Val"];
						}
					break;
					case '2':
						switch($Campo){
							/*
								INICIO VARIABLES FONOS
							*/
							case 'fono_especial':
								$Value = $FonoEspecial;
							break;
							case 'gestion_fono_especial':
								$Value = $GestionEspecial;
							break;
							case 'color_fono_especial':
								$Value = $ColorEspecial;
							break;
							case 'fono_2':
								$Value = $Fono2;
							break;
							case 'gestion_fono_2':
								$Value = $Gestion2;
							break;
							case 'color_fono_2':
								$Value = $ColorFono2;
							break;
							case 'fono_3':
								$Value = $Fono3;
							break;
							case 'gestion_fono_3':
								$Value = $Gestion3;
							break;
							case 'color_fono_3':
								$Value = $ColorFono3;
							break;
							/*
								FIN VARIABLES FONOS
							*/

							/*
								INICIO VARIABLES FONOS
							*/
							case 'mejor_gestion_texto':
								$Value = $MejorGestionTexto;
							break;
							case 'mejor_gestion_fecha':
								$Value = $MejorGestionFecha;
							break;
							case 'ultima_gestion_texto':
								$Value = $UltimaGestionTexto;
							break;
							case 'ultima_gestion_fecha':
								$Value = $UltimaGestionFecha;
							break;
							case 'ultima_gestion_observacion':
								$Value = $UltimaGestionObservacion;
							break;
							case 'ultima_gestion_usuario':
								$Value = $UltimaGestionUsuario;
							break;
							case 'ultimo_compromiso_texto':
								$Value = $UltimoCompromisoTexto;
							break;
							case 'ultimo_compromiso_fecha':
								$Value = $UltimoCompromisoFecha;
							break;
							case 'ultimo_compromiso_observacion':
								$Value = $UltimoCompromisoObservacion;
							break;
							case 'cantidad_gestiones':
								$Value = $CantidadGestiones;
							break;
							/*
								FIN VARIABLES FONOS
							*/
						}
					break;
				}
				$objPHPExcel->setActiveSheetIndex($NextSheet)->setCellValueByColumnAndRow($Col,$Cont,$Value);
				$Col++;
			}
			$Cont++;
		}
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment; filename="'.$fileName.'.xlsx"');
		header('Cache-Control: max-age=0');
		$objWriter=PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel2007');
		$objWriter->save('php://output');
		$objWriter=PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel2007');
		$objWriter->save('php://output');
		$xlsData = ob_get_contents();
		ob_end_clean();
		$response =  array(
			'fileName' => utf8_encode($fileName),
			'file' => "data:application/vnd.ms-excel;base64,".base64_encode($xlsData)
		);
		return $response;
	}
	function getEntidadName($Entidad){
		$db = new DB();
		$ToReturn = $Entidad;
		$ArrayEntidad = explode("_",$Entidad);
		switch($ArrayEntidad[0]){
			case 'S':
			case 'E':
				$Sql = "select Nombre as Nombre from Personal where Id_Personal = '".$ArrayEntidad[1]."'";
			break;
			case 'EE':
				$Sql = "select Nombre as Nombre from empresa_externa where IdEmpresaExterna = '".$ArrayEntidad[1]."'";
			break;
			case 'G':
				$Sql = "select Nombre as Nombre from grupos where IdGrupo = '".$ArrayEntidad[1]."'";
			break;
		}
		$Entidad = $db->select($Sql);
		$ToReturn = $ArrayEntidad[0]."_".$Entidad[0]["Nombre"];
		return $ToReturn;
	}
	function updateAsignaciones($Prefix = "QR_"){
		$db = new DB();
		//$Prefix = "QR_";
		$SqlColas = "select TABLE_NAME as tabla from information_schema.TABLES where  TABLE_SCHEMA='foco' and LENGTH(TABLE_NAME) - LENGTH(REPLACE(TABLE_NAME, '_', '')) = 2 and TABLE_NAME like '".$Prefix."%'";
		$Colas = $db->select($SqlColas);
		$ArrayColasAsignaciones = array();
		foreach($Colas as $Cola){
			$Prefix = $Cola["tabla"];
			$SqlAsignaciones = "select TABLE_NAME as tabla from information_schema.TABLES where  TABLE_SCHEMA='foco' and LENGTH(TABLE_NAME) - LENGTH(REPLACE(TABLE_NAME, '_', '')) = 7 and TABLE_NAME like '".$Prefix."%'";
			$Asignaciones = $db->select($SqlAsignaciones);
			$ColaArray = array();
			foreach($Asignaciones as $Asignacion){
				$SqlDeleteNotIn = "delete from ".$Asignacion["tabla"]." where Rut not in (select Rut from ".$Prefix.")";
				$DeleteNotIn = $db->query($SqlDeleteNotIn);
				$ArrayTmp = array();
				$ArrayTabla = explode("_",$Asignacion["tabla"]);
				$Prefijo = $ArrayTabla[0];
				$Cedente = $ArrayTabla[1];
				$Cola = $ArrayTabla[2];
				$TipoEntidad = $ArrayTabla[3];
				$idEntidad = $ArrayTabla[4];
				$Porcentaje = $ArrayTabla[5];
				$TipoAlgoritmo = $ArrayTabla[6];
				$Foco = $ArrayTabla[7];

				$ArrayTmp["Tabla"] = $Asignacion["tabla"];
				$ArrayTmp["TipoAlgoritmo"] = $TipoAlgoritmo;
				$ArrayTmp["Foco"] = $Foco;

				array_push($ColaArray,$ArrayTmp);
			}
			$ArrayColasAsignaciones[$Prefix] = $ColaArray;
		}
		foreach($ArrayColasAsignaciones as $key => $ColaArray){
			$Cola = $key;
			$CantEntidades = count($ColaArray);
			if($CantEntidades > 0){
				$TipoAlgoritmo = "";
				$Entidades = array();
				$Cont = 0;
				$ColaRuts = array();
				$SqlCola = "select Rut from ".$key." order by Rut";
				$Ruts = $db->select($SqlCola);
				foreach($Ruts as $Rut){
					array_push($ColaRuts,array("Rut"=>$Rut["Rut"]));
				}
				/*echo "<pre>";print_r($ColaRuts);echo"</pre>";
				echo "<br><br><br>";*/
				foreach($ColaArray as $Asignacion){
					$SqlRuts = "select Rut from ".$Asignacion["Tabla"]." order by Rut";
					$Ruts = $db->select($SqlRuts);
					$Entidades[$Cont]["Ruts"] = array();
					//echo "<pre>";print_r($Ruts);echo"</pre>";
					foreach($Ruts as $Rut){
						$ArrayKey = array_search(trim($Rut["Rut"]),array_column($ColaRuts,'Rut'));
						if(($ArrayKey !== FALSE)){
							array_push($Entidades[$Cont]["Ruts"],$Rut["Rut"]);
							unset($ColaRuts[$ArrayKey]);
							$ColaRuts = array_values($ColaRuts);
							//echo "<pre>";print_r($ColaRuts);echo"</pre>";
						}
					}
					
					$ArrayTabla = explode("_",$Asignacion["Tabla"]);
					$Prefijo = $ArrayTabla[0];
					$Cedente = $ArrayTabla[1];
					$Cola = $ArrayTabla[2];
					$TipoEntidad = $ArrayTabla[3];
					$idEntidad = $ArrayTabla[4];
					$Porcentaje = $ArrayTabla[5];
					$TipoAlgoritmo = $ArrayTabla[6];
					$Foco = $ArrayTabla[7];
					$Entidades[$Cont][0] = "";
					$Entidades[$Cont][1] = $Porcentaje;
					$Entidades[$Cont][2] = $TipoEntidad."_".$idEntidad;
					$Entidades[$Cont][3] = $Foco;
					$Entidades[$Cont][4] = count($Entidades[$Cont]["Ruts"]);
					$Entidades[$Cont][5] = $this->getDeudaFromRuts($Entidades[$Cont]["Ruts"]);
					$Cont++;
				}
				//if($key == "QR_45_283"){
					switch($TipoAlgoritmo){
						case '0':
							$this->AutoSeparateByRuts($ColaRuts,$Entidades,$key);
						break;
						case '1':
							$this->AutoSeparateByDeuda($ColaRuts,$Entidades,$key);
						break;
					}
					/*$FTPClass = new FTP();
					$ConnectionID = $FTPClass->Connect();
					$Login = $FTPClass->Login($ConnectionID);
					if($Login){
						$ArrayCola = explode("_",$key);
						$Cedente = $ArrayCola[1];
						$Cola = $ArrayCola[2];
						$FTPClass->createSubDirs($ConnectionID,"ftp",$Cedente."/".$Cola);
						$FTPClass->uploadDirectory($ConnectionID,"ftp/".$Cedente."/".$Cola."/","../task/asignaciones/".$Cedente."/".$Cola);
					}
					$FTPClass->CloseConnection($ConnectionID);*/
					/*$Entidades = array_sort($Entidades, 1, SORT_DESC);
					echo "<pre>";
					print_r($ColaRuts);
					print_r($Entidades);
					echo "</pre>";*/
				//}
			}
		}
		unlinkRecursive("../task/asignaciones/",false);
		/*echo "<pre>";
		print_r($ArrayColasAsignaciones);
		echo "</pre>";*/
	}
	function AutoSeparateByRuts($RutsCola, $Rows, $TableCola){
		$Algoritmo = "0";
		$db = new DB();
		$Ruts = $RutsCola;
		
		$SqlCantRutsCola = "select count(*) as CantRutsCola from ".$TableCola;
		$CantRutsCola = $db->select($SqlCantRutsCola);
		$CantRutsCola = $CantRutsCola[0]["CantRutsCola"];

		$NumRuts = $CantRutsCola;//count($Ruts);
		$CantRutsAvailable = $NumRuts;
		$ArrayAsignacion = array();
		$ArrayCola = explode("_",$TableCola);
		$Cedente = $ArrayCola[1];
		$Cola = $ArrayCola[2];
		$Rows = array_sort($Rows, 4, SORT_ASC);
		/*echo "<pre>";print_r($RutsCola);echo "</pre>";
		exit;*/
		foreach($Rows as $Row){
			$Nombre = $Row[0];
			$Porcentaje = $Row[1];
			$Porcentaje = $Porcentaje / 100;
			$Foco = $Row[3];
			$Id = $Row[2];
			$TableName = "`".$TableCola."_".$Id."_".($Porcentaje * 100)."_".$Algoritmo."_".$Foco."`";
			$TotalRuts = ceil($NumRuts * $Porcentaje);
			if($CantRutsAvailable <= $TotalRuts){
				$TotalRuts = $CantRutsAvailable;
			}
			$CantRutsAvailable = $CantRutsAvailable - $TotalRuts;
			$ArrayAsignacion[$Id]["Porcentaje"] = $Porcentaje * 100;
			$ArrayAsignacion[$Id]["TotalRuts"] = $TotalRuts;
			$ArrayAsignacion[$Id]["Ruts"] = array();

			$SqlCantRutsAsignacion = "select count(*) as CantRutsAsignacion from ".$TableName;
			$CantRutsAsignacion = $db->select($SqlCantRutsAsignacion);
			$CantRutsAsignacion = $CantRutsAsignacion[0]["CantRutsAsignacion"];
			
			$Cont = $CantRutsAsignacion;
			foreach($Ruts as $Key => $Rut){
				if($Cont <= $TotalRuts){
					array_push($ArrayAsignacion[$Id]["Ruts"],$Rut["Rut"]);
					$Cont++;
					unset($Ruts[$Key]);
				}else{
					break;
				}
			}
			$RutsArray = $ArrayAsignacion[$Id]["Ruts"];
			//echo "<pre>";print_r($RutsArray);echo "</pre>";
			foreach($RutsArray as $Key => $Rut){
				$RutsArray[$Key] = "(".$Rut.")";
			}
			$RutsImplode = implode(",",$RutsArray);
			$fecha_traza = date('Y-m-d');
			$RutNotIn = implode(",",$Row["Ruts"]);
			$SearchRutsFromTable = "select Rut from ".$TableName;
			$RutsFromTable = $db->select($SearchRutsFromTable);
			foreach($RutsFromTable as $Rut){
				array_push($ArrayAsignacion[$Id]["Ruts"],$Rut["Rut"]);
			}
			if($RutsImplode != ""){
				$SqlInsert = "INSERT IGNORE INTO $TableName (Rut) values ".$RutsImplode;
				$Insert = $db->query($SqlInsert);
				if($Insert){
					$DropColumn = $db->query("ALTER TABLE ".$TableName." DROP COLUMN id;");
					if($DropColumn){
						$AddColumn = $db->query("ALTER TABLE ".$TableName." ADD id int not null AUTO_INCREMENT PRIMARY KEY");
					}
				}
			}
			//echo "<pre>";print_r($ArrayAsignacion);echo "</pre>";
		}

		/*foreach($ArrayAsignacion as $key => $Entidad){
			$Ruts = $Entidad["Ruts"];
			$File[$key] = $this->CrearArchivoAsignacion($key,$Ruts,$Cedente,$Cola,false);
			$File[$key] = $this->CrearArchivoAsignacionTipo2($key,$Ruts,$Cedente,$Cola,false);
		}*/
	}
	function AutoSeparateByDeuda($RutsCola, $Rows, $TableCola){
		$Algoritmo = "1";
		$db = new DB();
		$ArrayCola = explode("_",$TableCola);
		$Rows = array_sort($Rows, 5, SORT_ASC);
		$Cedente = $ArrayCola[1];
		$Cola = $ArrayCola[2];
		$RutsImplode = array();
		foreach($RutsCola as $Rut){
			array_push($RutsImplode,"'".$Rut["Rut"]."'");
		}
		if(count($RutsImplode) > 0){
			$RutsImplode = implode(",",$RutsImplode);
			$SqlCola = "select Rut as Rut, Sum(Monto_Mora) as Deuda from Deuda where Id_Cedente = '".$Cedente."' and Rut in (".$RutsImplode.") Group By Rut Order by Deuda DESC";
			$Deudas = $db->select($SqlCola);
			if(count($Deudas) > 0){
				
				$SqlSumDeudaCola = "select SUM(Monto_Mora) as SumDeudaCola from Deuda inner join ".$TableCola." QR on QR.Rut = Deuda.Rut";
				$SumDeudaCola = $db->select($SqlSumDeudaCola);
				$SumDeudaCola = $SumDeudaCola[0]["SumDeudaCola"];

				//$CantTotalDeudas = $Deudas[0]["Deuda"] > 0 ? $this->DeudaTotal($Deudas) : 0;
				$CantTotalDeudas = $SumDeudaCola > 0 ? $SumDeudaCola : 0;
				$CantDeudasAvailable = $CantTotalDeudas;
				$ArrayAsignacion = array();
				if($Deudas[0]["Deuda"] > 0){
					foreach($Rows as $Row){
						$Nombre = $Row[0];
						$Porcentaje = $Row[1];
						$Porcentaje = $Porcentaje / 100;
						$Foco = $Row[3];
						$Id = $Row[2];
						$TotalDeudas = ($CantTotalDeudas * $Porcentaje);
						if($CantDeudasAvailable <= $TotalDeudas){
							$TotalDeudas = $CantDeudasAvailable;
						}
						$ArrayAsignacion[$Id]["Ruts"] = array();
						
						$RutsImplodeTmp = implode(",",$Row["Ruts"]);

						$SqlSumDeudaCola = "select SUM(Monto_Mora) as SumDeudaCola from Deuda inner join ".$TableCola." QR on QR.Rut = Deuda.Rut where QR.Rut in(".$RutsImplodeTmp.")";
						$SumDeudaCola = $db->select($SqlSumDeudaCola);
						$SumDeudaCola = $SumDeudaCola[0]["SumDeudaCola"];

						//$SumDeuda = 0;
						$SumDeuda = $SumDeudaCola > 0 ? $SumDeudaCola : 0;
						//echo $SumDeuda." - ".$CantTotalDeudas."<br>";
						foreach($Deudas as $Key => $Deuda){	
							if($SumDeuda <= $TotalDeudas){
								$SumDeuda += $Deuda["Deuda"];
								array_push($ArrayAsignacion[$Id]["Ruts"],$Deuda["Rut"]);
								unset($Deudas[$Key]);
							}else{
								//echo $SumDeuda."<br>";
								break;
							}
						}
						$RutsArray = $ArrayAsignacion[$Id]["Ruts"];
						foreach($RutsArray as $Key => $Rut){
							$RutsArray[$Key] = "(".$Rut.")";
						}
						$RutsImplode = implode(",",$RutsArray);
						//echo $RutsImplode."<br>";
						$TableName = "`".$TableCola."_".$Id."_".($Porcentaje * 100)."_".$Algoritmo."_".$Foco."`";
						/*$SqlDeleteNotIn = "delete from ".$TableName." where Rut not in (select Rut from ".$TableCola.")";
						$DeleteNotIn = $db->query($SqlDeleteNotIn);*/
						$SearchRutsFromTable = "select Rut from ".$TableName;
						$RutsFromTable = $db->select($SearchRutsFromTable);
						foreach($RutsFromTable as $Rut){
							array_push($ArrayAsignacion[$Id]["Ruts"],$Rut["Rut"]);
						}
						if($RutsImplode != ""){
							$SqlInsert = "INSERT IGNORE INTO $TableName (Rut) values ".$RutsImplode;
							$Insert = $db->query($SqlInsert);
							if($Insert){
								$DropColumn = $db->query("ALTER TABLE ".$TableName." DROP COLUMN id;");
								if($DropColumn){
									$AddColumn = $db->query("ALTER TABLE ".$TableName." ADD id int not null AUTO_INCREMENT PRIMARY KEY");
								}
							}
						}
					}
					/*foreach($ArrayAsignacion as $key => $Entidad){
						$Ruts = $Entidad["Ruts"];
						$File[$key] = $this->CrearArchivoAsignacion($key,$Ruts,$Cedente,$Cola,false);
						$File[$key] = $this->CrearArchivoAsignacionTipo2($key,$Ruts,$Cedente,$Cola,false);
					}*/
				}
			}else{ //PREGUNTARRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRR
				foreach($Rows as $Row){
					$Porcentaje = $Row[1];
					$Porcentaje = $Porcentaje / 100;
					$Foco = $Row[3];
					$Id = $Row[2];
					$TableName = "`".$TableCola."_".$Id."_".($Porcentaje * 100)."_".$Algoritmo."_".$Foco."`";
					
					$ArrayAsignacion[$Id]["Ruts"] = array();
					$SearchRutsFromTable = "select Rut from ".$TableName;
					$RutsFromTable = $db->select($SearchRutsFromTable);
					foreach($RutsFromTable as $Rut){
						array_push($ArrayAsignacion[$Id]["Ruts"],$Rut["Rut"]);
					}
					/*foreach($ArrayAsignacion as $key => $Entidad){
						$Ruts = $Entidad["Ruts"];
						$File[$key] = $this->CrearArchivoAsignacion($key,$Ruts,$Cedente,$Cola,false);
						$File[$key] = $this->CrearArchivoAsignacionTipo2($key,$Ruts,$Cedente,$Cola,false);
					}*/
				}
			}
		}else{
			foreach($Rows as $Row){
				$Porcentaje = $Row[1];
				$Porcentaje = $Porcentaje / 100;
				$Foco = $Row[3];
				$Id = $Row[2];
				$TableName = "`".$TableCola."_".$Id."_".($Porcentaje * 100)."_".$Algoritmo."_".$Foco."`";
				
				$ArrayAsignacion[$Id]["Ruts"] = array();
				$SearchRutsFromTable = "select Rut from ".$TableName;
				$RutsFromTable = $db->select($SearchRutsFromTable);
				foreach($RutsFromTable as $Rut){
					array_push($ArrayAsignacion[$Id]["Ruts"],$Rut["Rut"]);
				}
				/*foreach($ArrayAsignacion as $key => $Entidad){
					$Ruts = $Entidad["Ruts"];
					$File[$key] = $this->CrearArchivoAsignacion($key,$Ruts,$Cedente,$Cola,false);
					$File[$key] = $this->CrearArchivoAsignacionTipo2($key,$Ruts,$Cedente,$Cola,false);
				}*/
			}
		}
	}
	function getColumnasAsignacion(){
		$db = new DB();
		$SqlColumnas = "select * from Columnas_Asignacion_Dial where Id_Mandante='".$_SESSION['mandante']."' order by Prioridad";
		$Columnas = $db->select($SqlColumnas);
		$ToReturn = array();
		$Cont = 1;
		foreach($Columnas as $Columna){
			$ArrayTmp = array();
			$ArrayTmp["Prioridad"] = $Columna["Prioridad"];
			$ArrayTmp["Titulo"] = $Columna["Nombre"];
			$ArrayTmp["TipoCampo"] = $Columna["Tipo_Campo"] == "1" ? "Tabla" : "Especial";
			$ArrayTmp["Tabla"] = $Columna["Tabla"];
			$ArrayTmp["Campo"] = $Columna["Campo"];
			$ArrayTmp["Operacion"] = $Columna["Operacion"];
			$ArrayTmp["Accion"] = $Columna["id"];
			array_push($ToReturn,$ArrayTmp);
			$Cont++;
		}
		return $ToReturn;
	}
	function addColumnaAsignacion($Nombre,$TipoCampo,$Tabla,$Campo,$Operacion){
		$ToReturn = array();
		$db = new DB();
		$SqlQuery = "insert into Columnas_Asignacion_Dial (Nombre,Tabla,Campo,Operacion,Tipo_Campo,Id_Mandante) values ('".$Nombre."','".$Tabla."','".$Campo."','".$Operacion."','".$TipoCampo."','".$_SESSION['mandante']."')";
		$Query = $db->query($SqlQuery);
		if($Query){
			$ToReturn["result"] = true;
		}else{
			$ToReturn['result'] = false;
		}
		return $ToReturn;
	}
	function updatePrioridad($Value,$ID){
		$ToReturn = array();
		$db = new DB();
		$SqlUpdate = "update Columnas_Asignacion_Dial set Prioridad='".$Value."' where id='".$ID."'";
		$Update = $db->query($SqlUpdate);
		if($Update){
			$ToReturn['result'] = true;
		}else{
			$ToReturn['result'] = false;
		}
		return $ToReturn;
	}
	function deleteColumna($ID){
		$ToReturn = array();
		$db = new DB();
		$SqlDelete = "delete from Columnas_Asignacion_Dial where id='".$ID."'";
		$Delete = $db->query($SqlDelete);
		if($Delete){
			$ToReturn['result'] = true;
		}else{
			$ToReturn['result'] = false;
		}
		$ToReturn['query'] = $SqlDelete;
		return $ToReturn;
	}
	function getColumnaData($ID){
		$ToReturn = array();
		$db = new DB();
		$configTablasClass = new configTablas();
		$SqlRows = "select * from Columnas_Asignacion_Dial where id='".$ID."'";
		$Rows = $db->select($SqlRows);
		foreach($Rows as $Row){
			$ToReturn["data"]["id"] = $Row['id'];
			$ToReturn["data"]["Nombre"] = $Row['Nombre'];
			$ToReturn["data"]["TipoCampo"] = $Row['Tipo_Campo'];
			switch($ToReturn["data"]["TipoCampo"]){
				case '1':
					$ToReturn["data"]["Tabla"] = $configTablasClass->getIdTablaByNombre($Row['Tabla']);
					$ToReturn["data"]["Campo"] = $configTablasClass->getIdCampoByNombreAndTabla($ToReturn["data"]["Tabla"],$Row['Campo']);
				break;
				default:
					$ToReturn["data"]["Tabla"] = $Row['Tabla'];
					$ToReturn["data"]["Campo"] = $Row['Campo'];
				break;
			}
			$ToReturn["data"]["Operacion"] = $Row['Operacion'];
		}
		return $ToReturn;
	}
	function updateColumnaAsignacion($ID,$Nombre,$TipoCampo,$Tabla,$Campo,$Operacion){
		$ToReturn = array();
		$db = new DB();
		$SqlQuery = "update Columnas_Asignacion_Dial set Nombre='".$Nombre."' ,Tabla='".$Tabla."',Campo='".$Campo."',Operacion='".$Operacion."',Tipo_Campo='".$TipoCampo."' where id='".$ID."' and Id_Mandante='".$_SESSION['mandante']."'";
		$Query = $db->query($SqlQuery);
		if($Query){
			$ToReturn["result"] = true;
		}else{
			$ToReturn['result'] = false;
		}
		$ToReturn['query'] = $SqlQuery;
		return $ToReturn;
	}
	function ExisteCola($idCola){
		$ToReturn = false;
		$db = new DB();
		$Cola = "QR_".$_SESSION["cedente"]."_".$idCola;
		$SqlCola = "select TABLE_NAME as tabla from information_schema.TABLES where  TABLE_SCHEMA='foco' and LENGTH(TABLE_NAME) - LENGTH(REPLACE(TABLE_NAME, '_', '')) = 2 and TABLE_NAME like '".$Cola."%'";
		$Cola = $db->select($SqlCola);
		if(count($Cola) > 0){
			$ToReturn = true;
		}
		return $ToReturn;
	}
	function ExisteAsignacion($idCola){
		$ToReturn = false;
		$db = new DB();
		$Cola = "QR_".$_SESSION["cedente"]."_".$idCola;
		$SqlCola = "select TABLE_NAME as tabla from information_schema.TABLES where  TABLE_SCHEMA='foco' and LENGTH(TABLE_NAME) - LENGTH(REPLACE(TABLE_NAME, '_', '')) > 2 and TABLE_NAME like '".$Cola."%'";
		$Cola = $db->select($SqlCola);
		if(count($Cola) > 0){
			$ToReturn = true;
		}
		return $ToReturn;
	}
	function descargarGestiones($Cedente,$startDate,$endDate){
		$db = new DB();
		$objPHPExcel = new PHPExcel();
		$fileName = "Reporte asignacion ".date("d_m_Y");
		ob_start();
		$objPHPExcel->
			getProperties()
				->setCreator("FOCO Estrategico")
				->setLastModifiedBy("FOCO Estrategico");
		
		$objPHPExcel->removeSheetByIndex(
			$objPHPExcel->getIndex(
				$objPHPExcel->getSheetByName('Worksheet')
			)
		);

		$NextSheet = 0;

		$objPHPExcel->createSheet($NextSheet);
		$objPHPExcel->setActiveSheetIndex($NextSheet);
		$objPHPExcel->getActiveSheet()->setTitle('Gestiones');
		
		$Columnas = array();
		$Columnas[0][0] = "Rut";
		$Columnas[0][1] = "Rut";
		$Columnas[1][0] = "Nombre";
		$Columnas[1][1] = "Nombre_Completo";
		$Columnas[2][0] = "Fecha Gestion";
		$Columnas[2][1] = "fechahora";
		$Columnas[3][0] = "Fono Discado";
		$Columnas[3][1] = "fono_discado";
		$Columnas[4][0] = "Observacion";
		$Columnas[4][1] = "observacion";
		$Columnas[5][0] = "Ejecutivo";
		$Columnas[5][1] = "nombre_ejecutivo";
		$Columnas[6][0] = "Gestion";
		$Columnas[6][1] = "Gestion";
		$Columnas[7][0] = "Fecha de Compromiso";
		$Columnas[7][1] = "fec_compromiso";
		$Columnas[8][0] = "Monto Compromiso";
		$Columnas[8][1] = "monto_comp";
		$Columnas[9][0] = "Origen";
		$Columnas[9][1] = "origen";
		$Columnas[10][0] = "Nivel 1";
		$Columnas[10][1] = "n1";
		$Columnas[11][0] = "Nivel 2";
		$Columnas[11][1] = "n2";
		$Columnas[12][0] = "Nivel 3";
		$Columnas[12][1] = "n3";
		$Columnas[13][0] = "Status Name";
		$Columnas[13][1] = "status_name";

		$Col = 0;
		foreach($Columnas as $Columna){
			$Titulo = $Columna[0];
			$objPHPExcel->setActiveSheetIndex($NextSheet)->setCellValueByColumnAndRow($Col,1,$Titulo);
			$Col++;
		}

		$Cont = 2;
		$SqlGestiones = "select
							Persona.Rut,
							Persona.Nombre_Completo,
							Tipo_Contacto.Nombre as Gestion,
							gestion_ult_trimestre.*
						from
							gestion_ult_trimestre
								inner join Persona on Persona.Rut = gestion_ult_trimestre.rut_cliente
								inner join Tipo_Contacto on Tipo_Contacto.Id_TipoContacto = gestion_ult_trimestre.Id_TipoGestion
						where
							find_in_set(cedente,(select group_concat(Lista_Vicidial) from mandante_cedente where Id_Cedente='".$Cedente."')) AND
							fecha_gestion BETWEEN '".$startDate."' and '".$endDate."'
						order by
							fechahora ASC";
		$Gestiones = $db->select($SqlGestiones);
		foreach($Gestiones as $Gestion){
			$Col = 0;
			foreach($Columnas as $Columna){
				$Campo = $Columna[1];
				$objPHPExcel->setActiveSheetIndex($NextSheet)->setCellValueByColumnAndRow($Col,$Cont,$Gestion[$Campo]);
				$Col++;
			}
			$Cont++;
		}
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment; filename="'.$fileName.'.xlsx"');
		header('Cache-Control: max-age=0');
		$objWriter=PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel2007');
		$objWriter->save('php://output');
		$xlsData = ob_get_contents();
		ob_end_clean();
		$response =  array(
			'fileName' => utf8_encode($fileName),
			'file' => "data:application/vnd.ms-excel;base64,".base64_encode($xlsData)
		);
		return $response;
	}
	function getColas(){
		$db = new DB();

		$Prefix = "QR_".$_SESSION["cedente"];
		$SqlColas = "select TABLE_NAME as tabla from information_schema.TABLES where  TABLE_SCHEMA='foco' and LENGTH(TABLE_NAME) - LENGTH(REPLACE(TABLE_NAME, '_', '')) = 7 and TABLE_NAME like '".$Prefix."%' and TABLE_NAME like '%_G_%' and TABLE_NAME like '%1' order by TABLE_NAME";
		$Colas = $db->select($SqlColas);
		return $Colas;
	}
	function crearQueryDiscador($Cola,$TipoTelefono,$Canales,$TlfxRut,$Salida){
		$ToReturn = array();
		$ToReturn["result"] = false;

		$db = new DB();
		$dbDiscador = new DB("discador");

		$TipoTelefono = implode(",",$TipoTelefono);
		//$SqlRuts = "select QR.Rut, fono_cob.formato_subtel as Fono from ".$Cola." QR inner join fono_cob on fono_cob.Rut = QR.Rut inner join SIS_Categoria_Fonos on SIS_Categoria_Fonos.color = fono_cob.color where SIS_Categoria_Fonos.color in (".$TipoTelefono.")";
		$SqlRuts = "select
						QR.Rut,
						group_concat(fono_cob.formato_subtel) as Fono
					from
						".$Cola." QR
							inner join fono_cob on fono_cob.Rut = QR.Rut
							inner join SIS_Categoria_Fonos on SIS_Categoria_Fonos.color = fono_cob.color
					where
						SIS_Categoria_Fonos.color in (".$TipoTelefono.") and
						fono_cob.vigente = '1' and
						CHARACTER_LENGTH(fono_cob.formato_subtel)=9
					group by
						QR.rut
					order by
						SIS_Categoria_Fonos.prioridad";
		$Ruts = $db->select($SqlRuts);
		$ToReturn = $this->crearColaDiscador($Cola,$Canales,$TlfxRut,$TipoTelefono,$Salida);
		if($ToReturn["result"]){
			$SqlCreate = "CREATE TABLE IF NOT EXISTS DR_".$ToReturn["Queue"]."_".$Cola." (
							id int(11) NOT NULL auto_increment,
							Fono int(11) NOT NULL,
							Rut int(11) NOT NULL,
							Cedente  varchar(100) NOT NULL,
							llamado int(11) NOT NULL,
							PRIMARY KEY (id)
						)";
			$Create = $dbDiscador->query($SqlCreate);
			$SqlTruncate = "TRUNCATE TABLE DR_".$ToReturn["Queue"]."_".$Cola;
			$Truncate = $dbDiscador->query($SqlTruncate);
			if($Truncate){
				$ArraySoloRuts = array();
				$ArrayRuts = array();
				foreach($Ruts as $Rut){
					array_push($ArraySoloRuts,$Rut["Rut"]);
					$Fonos = $Rut["Fono"];
					$ArrayFonos = explode(",",$Fonos);
					$Cont = 1;
					$BreakFor = false;
					for($i=0;$i<=count($ArrayFonos);$i++){
						if(!$BreakFor){
							$Fono = isset($ArrayFonos[$i]) ? $ArrayFonos[$i] : "";
							if($Fono != ""){
								$Values = "('".$Fono."','".$Rut["Rut"]."','".$_SESSION["cedente"]."')";
								array_push($ArrayRuts,$Values);
							}else{
								$BreakFor = true;
							}
							if($Cont == $TlfxRut){
								$BreakFor = true;
							}
							$Cont++;
						}else{
							break;
						}
					}
				}
				$ValuesImplode = implode(",",$ArrayRuts);
				$SqlInsert = "INSERT INTO DR_".$ToReturn["Queue"]."_".$Cola." (Fono,Rut,Cedente) values ".$ValuesImplode;
				$Insert = $dbDiscador->query($SqlInsert);
				if($Insert){
					$this->CopyPersonaDataToPredictivo($ArraySoloRuts);
					$this->CopyDeudaDataToPredictivo($ArraySoloRuts);
				}
			}
		}
		return $ToReturn;
	}
	function ExistColaDiscador($Cola){
		$ToReturn = false;
		$dbDiscador = new DB("discador");
		$SqlExist = "select * from Asterisk_Discador_Cola where Cola  = '".$Cola."' and Id_Cedente='".$_SESSION["cedente"]."'";
		$Exist = $dbDiscador->select($SqlExist);
		if(count($Exist) > 0){
			$ToReturn = true;
		}
		return $ToReturn;
	}
	function crearColaDiscador($Cola,$Canales,$TlfxRut,$TipoTelefono,$Salida){
		$ToReturn = array();
		$ToReturn = $this->insertColaDiscador($Cola,$Canales,$TlfxRut,$TipoTelefono,$Salida);
		return $ToReturn;
	}
	function insertColaDiscador($Cola,$Canales,$TlfxRut,$TipoTelefono,$Salida){
		$ToReturn = array();
		$ToReturn["result"] = false;
		$ToReturn["Queue"] = "";
		$dbDiscador = new DB("discador");
		$SqlInsert = "INSERT INTO Asterisk_Discador_Cola (Cola,numero_canales,telfxrut,tipo_telefono,Id_Cedente,Salida) values ('".$Cola."','".$Canales."','".$TlfxRut."','".$TipoTelefono."','".$_SESSION["cedente"]."','".$Salida."')";
		$Insert = $dbDiscador->query($SqlInsert);
		if($Insert){
			$id = $dbDiscador->getLastIDFromTable("id","Asterisk_Discador_Cola");
			$SqlQueue = "update Asterisk_All_Queues set id_discador='".$id."' where id_discador='0' limit 1";
			$Queue = $dbDiscador->query($SqlQueue);
			if($Queue){
				$ToReturn["result"] = true;
				$ToReturn["Queue"] = $this->getQueueByDiscador($id);
			}
		}
		return $ToReturn;
	}
	function getQueueByDiscador($Discador){
		$dbDiscador = new DB("discador");
		$SqlQueue = "select Queue from Asterisk_All_Queues where id_discador = '".$Discador."'";
		$Queue = $dbDiscador->select($SqlQueue);
		return $Queue[0]["Queue"];
	}
	function updateColaDiscador($Cola,$Canales,$TlfxRut,$TipoTelefono){
		$dbDiscador = new DB("discador");
		$SqlUpdate = "UPDATE Asterisk_Discador_Cola set numero_canales = '".$Canales."', telfxrut = '".$TlfxRut."', tipo_telefono = '".$TipoTelefono."' where Cola = '".$Cola."' and Id_Cedente = '".$_SESSION['cedente']."'";
		$Update = $dbDiscador->query($SqlUpdate);
	}
	function getColasDiscadores(){
		$ToReturn = array();
		$dbDiscador = new DB("discador");
		$SqlColas = "select Cola.*, Q.Queue, (select group_concat(tipo_var) from SIS_Categoria_Fonos where find_in_set(color,Cola.tipo_telefono)) as Contacto  from Asterisk_Discador_Cola Cola inner join Asterisk_All_Queues Q on Q.id_discador = Cola.id where Cola.Id_Cedente='".$_SESSION['cedente']."'";
		$Colas = $dbDiscador->select($SqlColas);
		foreach($Colas as $Cola){
			$ArrayTmp = array();
			
			$TablaColaDiscador = "DR_".$Cola["Queue"]."_".$Cola["Cola"];
			$SqlCantRuts = "select count(*) as Cantidad from ".$TablaColaDiscador;
			$CantRuts = $dbDiscador->select($SqlCantRuts);
			$CantRuts = $CantRuts[0]["Cantidad"];

			$SqlCantRutsLlamados = "select count(*) as Cantidad from ".$TablaColaDiscador." where llamado = '1' ";
			$CantRutsLlamados = $dbDiscador->select($SqlCantRutsLlamados);
			$CantRutsLlamados = $CantRutsLlamados[0]["Cantidad"];

			$ArrayAsignacion = explode("_",$Cola["Cola"]);
			$TipoEntidad = $ArrayAsignacion[3];
			$idEntidad = $ArrayAsignacion[4];
			$Foco = $ArrayAsignacion[7];
			$Nombre = "";
			switch($TipoEntidad){
				case 'E':
				case 'S':
				break;
				case 'EE':
				break;
				case 'G':
					$GrupoClass = new Grupos();
					$Grupo = $GrupoClass->getGroup($idEntidad);
					$Nombre = utf8_encode($Grupo["Nombre"]);
				break;
			}
			$ArrayTmp["Cola"] = $Nombre;
			$ArrayTmp["Queue"] = $Cola["Queue"];
			$ArrayTmp["Canales"] = $Cola["numero_canales"];
			$ArrayTmp["TlfxRut"] = $Cola["telfxrut"];
			$ArrayTmp["tipoTelefono"] = $Cola["Contacto"];
			$ArrayTmp["Reproduccion"] = $Cola["id"]."_".$Cola["Estado"];
			$ArrayTmp["Progreso"] = $CantRutsLlamados."/".$CantRuts;
			$ArrayTmp["Status"] = $Cola["id"]."_".$Cola["Status"];
			$ArrayTmp["Accion"] = $Cola["id"];
			array_push($ToReturn,$ArrayTmp);
		}
		return $ToReturn;
	}
	function CambiarEstadoColaDiscado($Cola,$Value){
		$dbDiscador = new DB("discador");
		$SqlUpdate = "update Asterisk_Discador_Cola set Estado='".$Value."' where id='".$Cola."'";
		$Update = $dbDiscador->query($SqlUpdate);
		if($Value==1){
			shell_exec("php /var/www/html/produccion/includes/predictivo/ExeQueue.php '$Cola'  > /dev/null 2>&1 &");
		}
	}
	function EliminarColaDiscador($Discador){
		$ToReturn["result"] = false;
		$dbDiscador = new DB("discador");
		$Queue = $this->getQueueByDiscador($Discador);
		$ToReturn["Queue"] = $Queue;
		
		$Prefix = "DR_".$Queue;
		$SqlSelectTable = "select TABLE_NAME as tabla from information_schema.TABLES where  TABLE_SCHEMA='foco' and LENGTH(TABLE_NAME) - LENGTH(REPLACE(TABLE_NAME, '_', '')) >= 7 and TABLE_NAME like '".$Prefix."%' order by TABLE_NAME";
		$SelectTable = $dbDiscador->select($SqlSelectTable);
		foreach($SelectTable as $Table){
			$SqlDropTable = "DROP TABLE ".$Table["tabla"];
			$DropTable = $dbDiscador->query($SqlDropTable);
			if($DropTable){
				$SqlDelete = "delete from Asterisk_Discador_Cola where id='".$Discador."'";
				$Delete = $dbDiscador->query($SqlDelete);
				if($Delete){
					$SqlUpdateQueue = "update Asterisk_All_Queues set id_discador='0' where id_discador='".$Discador."'";
					$UpdateQueue = $dbDiscador->query($SqlUpdateQueue);
					if($UpdateQueue){
						$ToReturn["result"] = true;
					}
				}
			}
		}
		return $ToReturn;
	}
	function ReiniciarColaDiscado($Discador){
		$dbDiscador = new DB("discador");
		$Queue = $this->getQueueByDiscador($Discador);
		$SqlSelectTable = "select TABLE_NAME as tabla from information_schema.TABLES where  TABLE_SCHEMA='foco' and LENGTH(TABLE_NAME) - LENGTH(REPLACE(TABLE_NAME, '_', '')) >= 7 and TABLE_NAME like 'DR_".$Queue."%' order by TABLE_NAME";
		$SelectTable = $dbDiscador->select($SqlSelectTable);
		foreach($SelectTable as $Table){
			$SqlReinicio = "update ".$Table["tabla"]." set llamado='0'";
			$Reinicio = $dbDiscador->query($SqlReinicio);
		}
		$this->DetenerColaDiscado($Discador);
	}
	function CambiarStatusColaDiscado($Cola,$Value){
		$dbDiscador = new DB("discador");
		$SqlUpdate = "update Asterisk_Discador_Cola set Status='".$Value."' where id='".$Cola."'";
		$Update = $dbDiscador->query($SqlUpdate);

		if($Value == 1){
			shell_exec("php /var/www/html/produccion/discador/AGI/EntrarCola.php '$Cola'");

		}
		else{
			shell_exec("php /var/www/html/produccion/discador/AGI/SalirCola.php '$Cola'");

		}
	}
	function IniciarColaDiscado($Discador){
		$dbDiscador = new DB("discador");
		$SqlUpdate = "update Asterisk_Discador_Cola set FeMin = NOW(), Id_Usuario='".$_SESSION['id_usuario']."' where id = '".$Discador."'";
		$Update = $dbDiscador->query($SqlUpdate);
	}
	function DetenerColaDiscado($Discador){
		$dbDiscador = new DB("discador");
		$SqlUpdate = "update Asterisk_Discador_Cola set FeMin = '0000-00-00 00:00:00',FeFin = '0000-00-00 00:00:00', Id_Usuario='0' where id = '".$Discador."'";
		$Update = $dbDiscador->query($SqlUpdate);
	}
	function FindQueueFinished(){
		$ToReturn = array();
		$dbDiscador = new DB("discador");
		$SqlFinishedQueue = "select Asterisk_Discador_Cola.*,Asterisk_All_Queues.Queue from Asterisk_Discador_Cola inner join Asterisk_All_Queues on Asterisk_All_Queues.id_discador = Asterisk_Discador_Cola.id where FeMin <= FeFin and Estado='0' and FeMin > '0000-00-00 00:00:00' and Id_Cedente='".$_SESSION['cedente']."'";
		$FinishedQueue = $dbDiscador->select($SqlFinishedQueue);
		foreach($FinishedQueue as $Queue){
			$ArrayAsignacion = explode("_",$Queue["Cola"]);
			$TipoEntidad = $ArrayAsignacion[3];
			$idEntidad = $ArrayAsignacion[4];
			$Foco = $ArrayAsignacion[7];
			$Nombre = "";
			switch($TipoEntidad){
				case 'E':
				case 'S':
				break;
				case 'EE':
				break;
				case 'G':
					$GrupoClass = new Grupos();
					$Grupo = $GrupoClass->getGroup($idEntidad);
					$Nombre = utf8_encode($Grupo["Nombre"]);
				break;
			}
			$ArrayTmp = array();
			$ArrayTmp['Queue'] = $Queue["Queue"];
			$ArrayTmp['Cola'] = $Nombre;
			if($_SESSION['id_usuario'] == $Queue["Id_Usuario"]){
				array_push($ToReturn,$ArrayTmp);
				$this->DetenerColaDiscado($Queue["id"]);
				$this->ReiniciarColaDiscado($Queue["id"]);
			}
		}
		return $ToReturn;
	}
	function getDeudaFromRuts($Ruts){
		$db = new DB();
		$RutsImplode = implode(",",$Ruts);
		$SqlDeuda = "select SUM(Monto_Mora) as Deuda from Deuda where Rut in (".$RutsImplode.")";
		$Deuda = $db->select($SqlDeuda);
		$Deuda = $Deuda[0]["Deuda"];
		return $Deuda;
	}
	function getFechasPeriodosCargas(){
		$db = new DB();
		$ToReturn = array();
		$SqlFechas = "select * from Historico_Carga where Id_Cedente='".$_SESSION['cedente']."' and fecha_fin > NOW() order by fecha DESC LIMIT 1";
		$Fechas = $db->select($SqlFechas);
		if(count($Fechas) > 0){
			$Fechas = $Fechas[0];
			$ToReturn["Desde"] = $Fechas["fecha"];
			$ToReturn["Hasta"] = $Fechas["fecha_fin"];
		}else{
			$SqlFechas = "select * from Historico_Carga where Id_Cedente='".$_SESSION['cedente']."' order by fecha DESC LIMIT 1";
			$Fechas = $db->select($SqlFechas);
			if(count($Fechas) > 0){
				$Fechas = $Fechas[0];
				$ToReturn["Desde"] = $Fechas["fecha"];
				$ToReturn["Hasta"] = date("Ymd");
			}else{
				$ToReturn["Desde"] = date("Ym01");
				$ToReturn["Hasta"] = date("Ymd");
			}
		}
		return $ToReturn;
	}
	function CopyPersonaDataToPredictivo($Ruts){
		if($_SESSION["planDiscado"] == "1"){
			$db = new DB();
			$dbDiscador = new DB("discador");
			$RutsImplode = implode(",",$Ruts);
			$ColumnsPersona = $this->getCamposTabla("Persona","foco","Id_Cedente,Mandante");
			$ColumnasPersonaImplode = implode(",",$ColumnsPersona);
			$SqlPersonas = "select ".$ColumnasPersonaImplode." from Persona where Rut in (".$RutsImplode.")";
			$Personas = $db->select($SqlPersonas);
			$ArrayInsertValues = array();
			foreach($Personas as $Persona){
				foreach($Persona as $key => $Field){
					$Persona[$key] = "'".$Field."'";
				}
				$ValuesImplode = implode(",",$Persona);
				array_push($ArrayInsertValues,"(".$ValuesImplode.",'".$_SESSION['cedente']."','".$_SESSION['mandante']."')");
			}
			$ArrayInsertValuesImplode = implode(",",$ArrayInsertValues);
			$SqlInsertPersonaDiscador = "INSERT IGNORE INTO Persona (".$ColumnasPersonaImplode.",Id_Cedente,Mandante) values ".$ArrayInsertValuesImplode." ON DUPLICATE KEY UPDATE Persona.Id_Cedente = CONCAT(REPLACE(Persona.Id_Cedente,',".$_SESSION['cedente']."',''),',','".$_SESSION['cedente']."'), Persona.Mandante = CONCAT(REPLACE(Persona.Mandante,',".$_SESSION['mandante']."',''),',','".$_SESSION['mandante']."')";
			$Insert = $dbDiscador->query($SqlInsertPersonaDiscador);
			if($Insert){
				
			}else{
				
			}
		}
	}
	function CopyDeudaDataToPredictivo($Ruts){
		if($_SESSION["planDiscado"] == "1"){
			$db = new DB();
			$dbDiscador = new DB("discador");
			$RutsImplode = implode(",",$Ruts);
			$ColumnsDeuda = $this->getCamposTabla("Deuda");
			$ColumnasDeudaImplode = implode(",",$ColumnsDeuda);
			$SqlDeudas = "select ".$ColumnasDeudaImplode." from Deuda where Rut in (".$RutsImplode.")";
			$Deudas = $db->select($SqlDeudas);
			$ArrayInsertValues = array();
			foreach($Deudas as $Deuda){
				foreach($Deuda as $key => $Field){
					$Deuda[$key] = "'".$Field."'";
				}
				$ValuesImplode = implode(",",$Deuda);
				array_push($ArrayInsertValues,"(".$ValuesImplode.")");
			}
			$ArrayInsertValuesImplode = implode(",",$ArrayInsertValues);
			$SqlInsertDeudaDiscador = "INSERT IGNORE INTO Deuda (".$ColumnasDeudaImplode.") values ".$ArrayInsertValuesImplode;
			$Insert = $dbDiscador->query($SqlInsertDeudaDiscador);
			if($Insert){
				
			}else{
				
			}
		}
	}
	function getCamposTabla($Tabla,$Link = "foco",$Exclusiones = ""){
		$ToReturn = array();
		$db = new DB($Link);
		$SqlColumns = "DESCRIBE ".$Tabla;
		$Columns = $db->select($SqlColumns);
		$ArrayExclusiones = explode(",",$Exclusiones);
		foreach($Columns as $Column){
			$Flag = true;
			foreach($ArrayExclusiones as $Exclusion){
				if($Column["Field"] == $Exclusion){
					$Flag = false;
				}
			}
			if($Flag){
				if($Column["Key"] != "PRI"){
					array_push($ToReturn,$Column["Field"]);
				}
			}
		}
		return $ToReturn;
	}
	function actualizarColaDiscador($idDiscador){
		$dbDiscador = new DB("discador");
		$SqlColaDiscador = "SELECT * FROM Asterisk_Discador_Cola where id='".$idDiscador."'";
		$ColaDiscador = $dbDiscador->select($SqlColaDiscador);
		foreach($ColaDiscador as $Cola){
			$SqlRuts = "select
						QR.Rut,
						group_concat(fono_cob.formato_subtel) as Fono
					from
						".$Cola["Cola"]." QR
							inner join fono_cob on fono_cob.Rut = QR.Rut
							inner join SIS_Categoria_Fonos on SIS_Categoria_Fonos.color = fono_cob.color
					where
						SIS_Categoria_Fonos.color in (".$Cola["tipo_telefono"].") and
						fono_cob.vigente = '1' and
						CHARACTER_LENGTH(fono_cob.formato_subtel)=9
					group by
						QR.rut
					order by
						SIS_Categoria_Fonos.prioridad";
			$Ruts = $db->select($SqlRuts);
			/* $SqlTruncate = "TRUNCATE TABLE DR_".$ToReturn["Queue"]."_".$Cola;
			$Truncate = $db->query($SqlTruncate);
			if($Truncate){
				$ArraySoloRuts = array();
				$ArrayRuts = array();
				foreach($Ruts as $Rut){
					array_push($ArraySoloRuts,$Rut["Rut"]);
					$Fonos = $Rut["Fono"];
					$ArrayFonos = explode(",",$Fonos);
					$Cont = 1;
					$BreakFor = false;
					for($i=0;$i<=count($ArrayFonos);$i++){
						if(!$BreakFor){
							$Fono = isset($ArrayFonos[$i]) ? $ArrayFonos[$i] : "";
							if($Fono != ""){
								$Values = "('".$Fono."','".$Rut["Rut"]."','".$_SESSION["cedente"]."')";
								array_push($ArrayRuts,$Values);
							}else{
								$BreakFor = true;
							}
							if($Cont == $Cola["telfxrut"]){
								$BreakFor = true;
							}
							$Cont++;
						}else{
							break;
						}
					}
				}
				$ValuesImplode = implode(",",$ArrayRuts);
				$SqlInsert = "INSERT INTO DR_".$ToReturn["Queue"]."_".$Cola." (Fono,Rut,Cedente) values ".$ValuesImplode;
				$Insert = $db->query($SqlInsert);
				if($Insert){
					$this->CopyPersonaDataToPredictivo($ArraySoloRuts);
					$this->CopyDeudaDataToPredictivo($ArraySoloRuts);
				}
			} */
		}
	}
	function getCola($idCola){
		$db = new DB();
		$SqlCola = "select * from SIS_Querys_Estrategias where id='".$idCola."'";
		$Cola = $db->select($SqlCola);
		$Cola = $Cola[0];
		return $Cola;
	}
	function getEjecutivosActivos(){
		$db = new DB();
		$SqlEjecutivos = "select Usuarios.id as idUsuario, Personal.Nombre as Nombre from Personal inner join Usuarios on Usuarios.id = Personal.id_usuario where Personal.Activo = '1' and Usuarios.nivel = '4' order by Personal.Nombre";
		$Ejecutivos = $db->select($SqlEjecutivos);
		return $Ejecutivos;
	}
	function updateColaCautiva($idCola,$idUserCautiva,$StatusCautiva){
		$ToReturn = array();
		$ToReturn["result"] = false;
		$db = new DB();
		$idUserCautiva = $StatusCautiva == "0" ? "" : $idUserCautiva;
		$SqlInsert = "update SIS_Querys_Estrategias set cautiva='".$StatusCautiva."', idUserCautiva='".$idUserCautiva."' where id='".$idCola."'";
		$Insert = $db->query($SqlInsert);
		if($Insert){
			$ToReturn["result"] = true;
		}
		return $ToReturn;
	}
}
?>