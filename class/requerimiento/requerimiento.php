<?php
class Requerimiento{

  public function guardarRequerimiento($datos){
    $db = new Db();
    $usuario = $_SESSION['MM_Username'];
    $fecha = date("Y-m-d H:i:s");
    $SqlInsert = "INSERT INTO requerimiento(tipo, modulo, descripcion, prioridad, usuario, fecha) values('".$datos['tipo']."', '".$datos['modulo']."', '".$datos['descripcion']."', '".$datos['prioridad']."', '".$usuario."', '".$fecha."')";
    $db -> query($SqlInsert);
  } 

  public function getRequerimientos(){
    $db = new Db();
    $requerimientosArray = array();
    $Sql = "SELECT * FROM requerimiento ORDER BY fecha ASC";
    $requerimientos = $db -> select($Sql);
    foreach($requerimientos as $requerimiento){
      $Array = array();
        if ($requerimiento["tipo"] == '1'){
          $tipo = "Mejora";
        }else{
          $tipo = "Error";
        }
        $Array['tipo'] = $tipo;
        $Array['modulo'] = utf8_encode($requerimiento["modulo"]);
        switch ($requerimiento["prioridad"]){
          case 1: 
          $prioridad = "Alta";
          break;
          case 2: 
          $prioridad = "Media";
          break;
          default:
          $prioridad = "Baja";          
        }
        $Array['prioridad'] = $prioridad;
        $Array['usuario'] = utf8_encode($requerimiento["usuario"]);
        $Array['fecha'] = utf8_encode($requerimiento["fecha"]);
        if ($_SESSION['MM_Username'] == $requerimiento["usuario"]){ 
          $id = $requerimiento["id_requerimiento"];
        }else{
          if ($_SESSION['MM_UserGroup'] == 1){
            $id = $requerimiento["id_requerimiento"];
          }else{
            $id = 0;
          }
        }
        $Array['Actions'] = $id;
        array_push($requerimientosArray,$Array);
      }
    return $requerimientosArray;
  }

  public function modificaRequerimiento($datos){
        $db = new Db();
        $SqlUpdate = "UPDATE requerimiento set tipo = '".$datos['tipo']."', modulo = '".$datos['modulo']."', descripcion = '".$datos['descripcion']."', prioridad = '".$datos['prioridad']."' WHERE id_requerimiento='".$datos['idRequerimiento']."'";
        $db -> query($SqlUpdate);
  }

  public function muestraDatosRequerimiento($idRequerimiento){
		$db = new Db();
    $datosArray = array();
    $SqlDatos = "SELECT * FROM requerimiento WHERE id_requerimiento = '".$idRequerimiento."'";
    $Datos = $db -> select($SqlDatos);
    return $Datos;  
	} 

  public function eliminaRequerimiento($idRequerimiento){
      $db = new Db();
      $SqlEliminar = "delete from requerimiento where id_requerimiento = '".$idRequerimiento."'";
      $db -> query($SqlEliminar);        
  } 

}   
?>