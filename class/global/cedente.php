<?php
class Cedente
{
	public $Id_Cedente;
	function __construct(){

	}
  public function formCedente($cedente, $mandante)
	{
      echo '<div class="row">';
			echo '<div class="col-md-12">';
			echo '<form class="form-horizontal">';
			echo '<div class="form-group">';
			echo '<label class="col-md-4 control-label" for="name">Cedente</label>';
			echo '<div class="col-md-6 ">';
			echo "<select class='selectpicker' name='Tipo_Carga' title='".$this->getCedenteName($cedente)."' data-live-search='true' data-width='100%' id='cedenteSeleccionado' name='cedenteSeleccionado'>";
	        $q=mysql_query("SELECT Cedente.Nombre_Cedente,Cedente.Id_Cedente FROM Cedente inner join mandante_cedente on mandante_cedente.Id_Cedente = Cedente.Id_Cedente inner join mandante on mandante.id = mandante_cedente.Id_Mandante WHERE NOT Cedente.Id_Cedente = $cedente and mandante.id = '".$mandante."' ORDER BY Cedente.Nombre_Cedente ASC");
 
	        while($row=mysql_fetch_array($q))
	        {
	        	echo "<option value='$row[1]'>"; echo utf8_encode($row[0]); echo "</option>";
	        }
	        echo "</select>";
  		echo '</div>';
			echo '</div>';
			echo '</form>';
			echo '</div>';
			echo '</div>';
	}
	function getCedenteName($cedente){
		$ToReturn = "";
		$db = new Db();
		$cedentes = $db -> select("SELECT Cedente.Nombre_Cedente as Nombre FROM Cedente WHERE Cedente.Id_Cedente = '".$cedente."'");
		foreach($cedentes as $cedente){
			$ToReturn = $cedente["Nombre"];
		}
		return $ToReturn;
	}
	function getMandanteName($mandante){
		$ToReturn = "";
		$db = new Db();
		$mandantes = $db -> select("SELECT mandante.nombre as Nombre FROM mandante WHERE mandante.id = '".$mandante."'");
		foreach($mandantes as $mandante){
			$ToReturn = $mandante["Nombre"];
		}
		return $ToReturn;
	}
	function getCedentesMandante($mandante){
		$db = new Db();
	    $cedentes = $db -> select("SELECT m.id_cedente as idCedente, c.Nombre_Cedente as NombreCedente FROM mandante_cedente as m, Cedente as c WHERE m.id_mandante = '".$mandante."' AND c.id_cedente = m.Id_Cedente");
	    //print_r($cedentes);
		return $cedentes;
	}
	function getMandantes(){
		$db = new Db();
		$estatus = 1;
	    $mandantes = $db -> select("SELECT id, nombre FROM mandante WHERE estatus = '$estatus' order by nombre");
	    return $mandantes;
	}
	function getCedentes(){
		$db = new Db();
        $CedentesArray = array();
        $Sql = "select * from Cedente";
        $cedentes = $db -> select($Sql);
        foreach($cedentes as $cedente){
        	$Array = array();
            $Array['nombre'] = utf8_encode($cedente["Nombre_Cedente"]);
            $Array['Actions'] = $cedente["Id_Cedente"];
            array_push($CedentesArray,$Array);
         }
         return $CedentesArray;  
	}
	function creaCedente($datos){
        $db = new Db();
        $SqlInsertCedente = "insert into Cedente (Nombre_Cedente, Fecha_Ingreso, tipo, planDiscado) values('".$datos['nombreCedente']."', '".$datos['fechaIngreso']."', '".$datos['plan']."', '".$datos['discado']."')";
        $InsertCedente = $db -> query($SqlInsertCedente); 
		$cedente = $db -> select("SELECT Id_Cedente FROM Cedente WHERE Nombre_Cedente = '".$datos['nombreCedente']."'");

		$SqlInsertCedenteMandante = "insert into mandante_cedente (Id_Cedente, Id_Mandante) values('".$cedente[0]['Id_Cedente']."', '".$datos['idMandante']."')";               
        $InsertCedente = $db -> query($SqlInsertCedenteMandante); 
        return $cedente[0]['Id_Cedente'];     
	}
	function creaMandante($datos){
        $db = new Db();
        $SqlInsert = "insert into mandante (nombre, Empieza) values('".$datos['nombre']."', '".$datos['evaluar']."')";               
        $Insert = $db -> query($SqlInsert);   
        return $db->getLastID();     
	}
	public function eliminaCedente($idCedente){
        $db = new Db();
        $SqlEliminarCedente = "delete from Cedente where Id_Cedente = '".$idCedente."'";
        $db -> query($SqlEliminarCedente); 
		$SqlEliminaMandanteCedente = "Delete from mandante_cedente where Id_Cedente = '".$idCedente."'";
        $db -> query($SqlEliminaMandanteCedente);        
    }
	public function eliminaMandante($idMandante){
        $db = new Db();
		$estatus = 0;
		$SqlUpdate = "UPDATE mandante set estatus = '".$estatus."' WHERE id='".$idMandante."'";
     	$db -> query($SqlUpdate);      
    }
	public function modificarMandante($datos){
		$db = new Db();
		$SqlUpdate = "UPDATE mandante set nombre = '".$datos['nombre']."', Empieza = '".$datos['evaluar']."' WHERE id='".$datos['id']."' ";
     	$db -> query($SqlUpdate);
	} 
	public function modificarCedente($datos){ // tipo, planDiscado
		$db = new Db();
		echo $SqlUpdate = "UPDATE Cedente set Nombre_Cedente = '".$datos['nombre']."', Fecha_Ingreso = '".$datos['fecha']."', tipo = '".$datos['plan']."', planDiscado = '".$datos['discado']."' WHERE Id_Cedente='".$datos['id']."' ";
     	$db -> query($SqlUpdate);
	} 
	public function mostrarMandante($idMandante){
		$db = new Db();
	    $mandante = $db -> select("SELECT * FROM mandante WHERE id = '".$idMandante."'");
	    return $mandante;	
	} 
	public function mostrarCedente($idCedente){
		$db = new Db();
	    $cedente = $db -> select("SELECT * FROM Cedente WHERE Id_Cedente = '".$idCedente."'");
	    return $cedente;	
	}
	public function getMandanteFromCedente($Cedente){
		$db = new DB();
		$SqlMandante = "select mandante.* from mandante inner join mandante_cedente on mandante_cedente.Id_Mandante = mandante.id where mandante_cedente.Id_Cedente='".$Cedente."'";
		$Mandante = $db->select($SqlMandante);
		$Mandante = $Mandante[0];
		return $Mandante;
	}
}
?>
