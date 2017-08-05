<?php
class Trabajador{

  public function muestraDatosGeneralesTrabajador($idTrabajador){
    $db = new Db();
    $Array = array();


    // datos generales del trabajador
     $SqlDatosTrabajador = "SELECT * FROM Personal WHERE Id_Personal = '".$idTrabajador."'";
    $Datos = $db -> select($SqlDatosTrabajador);   
    
    foreach($Datos as $dato){          
      $Array['nombre'] = $dato["Nombre"];
      $Array['rut'] = $dato["Rut"];
      $Array['fechaNacimiento'] = $dato["Fecha_Nacimiento"];
      $Array['idNacionalidad'] = $dato["id_nacionalidad"];
      $Array['idComuna'] = $dato["id_comuna"];
      $Array['direccion'] = $dato["direccion"];
      $Array['fonoParticular'] = $dato["fono_particular"];
      $Array['fonoMovil'] = $dato["fono_movil"];
      $Array['email'] = $dato["email"];
      $Array['afp'] = $dato["AFP"];
      $Array['salud'] = $dato["sistema_salud"];       
      $Array['uf'] = $dato["UF"];
      $Array['ges'] = $dato["GES"];
      $Array['pensionado'] = $dato["pensionado"];
      $Array['remuneracion'] = $dato["remuneracion"];
      $Array['idCargo'] = $dato["id_cargo"];
      $Array['sexo'] = $dato["id_sexo"];
      $Array['estadoCivil'] = $dato["id_estado"];
      $Array['hijos'] = $dato["hijos"];
      $Array['tipoEjecutivo'] = $dato["id_tipo_ejecutivo"];
      $Array['tipoContrato'] = $dato["id_contrato"];
      $Array['fechaIngreso'] = $dato["fecha_ingreso"];      
      $Array['idEstatusEgreso'] = $dato["id_estatus_egreso"];     
     
    }    

    if ($dato["fecha_termino"] == "0000-00-00"){
      $Array['fechaTermino'] = "";
    }else{
      $Array['fechaTermino'] = $dato["fecha_termino"];
    }

    // busco el nombre del cargo del trabajador
   /* $SqlDatosCargo = "SELECT cargo FROM RH_cargo WHERE id_cargo = '".$Datos[0]['id_cargo']."'";
    $Cargo = $db -> select($SqlDatosCargo);  
    $Array['cargo'] = count($Cargo) > 0 ? $Cargo[0]['cargo'] : 0;  */

    // busco el nombre de la nacionalidad
  /*  $SqlDatosNacionalidad = "SELECT nacionalidad FROM RH_nacionalidad WHERE id_nacionalidad = '".$Datos[0]['id_nacionalidad']."'";
    $nacionalidad = $db -> select($SqlDatosNacionalidad);  
    $Array['nacionalidad'] = count($nacionalidad) > 0 ? $nacionalidad[0]['nacionalidad'] : 0; */

    // busco el nombre de la comuna y id de la provincia
    $SqlDatosComuna = "SELECT comuna, id_provincia FROM RH_comuna WHERE id_comuna = '".$Datos[0]['id_comuna']."'";
    $comuna = $db -> select($SqlDatosComuna);  
    $Array['idProvincia'] = $comuna[0]['id_provincia'];     

    // busco el nombre de la provincia y id de la region
    $SqlDatosProvincia = "SELECT provincia, id_region FROM RH_provincia WHERE id_provincia = '".$comuna[0]['id_provincia']."'";
    $provincia = $db -> select($SqlDatosProvincia);  
    $Array['idRegion'] = $provincia[0]['id_region'];

    //echo $provincia[0]['id_region'];

    // busco el nombre de la region 
 /*   $SqlDatosRegion = "SELECT region FROM RH_region WHERE id_region = '".$provincia[0]['id_region']."'";
    $region = $db -> select($SqlDatosRegion);  
    $Array['region'] = utf8_encode($region[0]['region']); */

    // busco los datos de los contactos 
    $SqlDatosContacto = "SELECT * FROM RH_contacto_personal WHERE id_personal = '".$idTrabajador."'";
    $contactos = $db -> select($SqlDatosContacto);  

    $ArrayContacto = array();
    if (count($contactos) > 0){
      foreach($contactos as $contacto){          
        $ArrayContacto['nombreContacto'] = $contacto["nombre"];
        $ArrayContacto['parentesco'] = $contacto["parentesco"];
        $ArrayContacto['celular1'] = $contacto["celular1"];
        $ArrayContacto['celular2'] = $contacto["celular2"];
        array_push($Array,$ArrayContacto);
      }     
    } 
         
    return $Array;
  }

  public function getTrabajadores(){
		$db = new Db();
    $trabajadoresArray = array();
    $Sql = "SELECT * FROM Personal WHERE Activo = '1' ORDER BY Nombre ASC";
    $trabajadores = $db -> select($Sql);
    foreach($trabajadores as $trabajador){
      $Array = array();
      $Array['nombre'] = utf8_encode($trabajador["Nombre"]);
      $Array['usuario'] = $trabajador["Nombre_Usuario"];
      $Array['email'] = $trabajador["email"];
      $Array['Actions'] = $trabajador["Id_Personal"];
      array_push($trabajadoresArray,$Array);
    }
    return $trabajadoresArray;  
	} 

  public function crearTrabajador($datos){
    $db = new Db();
    // 1 = Se registro
    // 2 = Rut ya existe por lo tanto no se registro

    $isValid = 1;
    if(!($this->validarRutExiste($datos['rut'],""))){
      $SqlInsert = "INSERT INTO Personal(Nombre, Rut, Fecha_Nacimiento, id_nacionalidad, id_comuna, direccion, fono_particular, fono_movil, email, AFP, sistema_salud, UF, GES, pensionado, remuneracion, id_cargo, id_sexo, id_estado, hijos, id_tipo_ejecutivo, id_contrato, fecha_ingreso) values('".$datos['nombre']."', '".$datos['rut']."', '".$datos['fechaNacimiento']."', '".$datos['nacionalidad']."', '".$datos['comuna']."', '".$datos['direccion']."', '".$datos['telefonoParticular']."', '".$datos['telefono']."', '".$datos['email']."', '".$datos['afp']."', '".$datos['salud']."', '".$datos['uf']."', '".$datos['ges']."', '".$datos['pensionado']."', '".$datos['remuneracion']."', '".$datos['cargo']."', '".$datos['sexo']."', '".$datos['estadoCivil']."', '".$datos['hijos']."', '".$datos['tipoEjecutivo']."', '".$datos['tipoContrato']."', '".$datos['fechaIngreso']."')";
      $db -> query($SqlInsert);

      // busco el id del personal insertado
      $SqlidPersonal = "SELECT Id_Personal FROM Personal WHERE Rut = '".$datos['rut']."'";
      $personal = $db -> select($SqlidPersonal);
      $idPersonal = $personal[0]['Id_Personal'];  

      if(($datos['contacto'] != "") && ($datos['parentesco'] != "") && ($datos['celular1'] != "")){
        $this->insertarContacto($datos['contacto'],$datos['parentesco'],$datos['celular1'],$datos['celular2'],$idPersonal);
      } 

      if(($datos['contacto1'] != "") && ($datos['parentesco1'] != "") && ($datos['celular11'] != "")){
        $this->insertarContacto($datos['contacto1'],$datos['parentesco1'],$datos['celular11'],$datos['celular21'],$idPersonal); 
      }
    }
    else{
      $isValid = 2;
    }
    return $isValid;            
  }

  public function validarRutExiste($rut,$id){
    $db = new Db();
    $isValid = false;
    if ($id == "")
      $sql_num = "SELECT * FROM Personal WHERE Rut='".$rut."'";
    else {
      $sql_num = "SELECT * FROM Personal WHERE Rut='".$rut."' AND Id_Personal != '".$id."'";
    }
    $resultado = $db->select($sql_num);
    if(count($resultado) > 0){
      $isValid = true;
    }
    
    return $isValid;
  }

  public function insertarContacto($contacto, $parentesco, $celular1, $celular2, $idPersonal){
    $db = new Db();
    $SqlInsertContacto = "INSERT INTO RH_contacto_personal(nombre, parentesco, celular1, celular2, id_personal) values('".$contacto."', '".$parentesco."', '".$celular1."', '".$celular2."', '".$idPersonal."')";
    $db -> query($SqlInsertContacto);
  }

  public function eliminaTrabajador($idTrabajador){
    $db = new Db();
    /*$SqlEliminar = "DELETE FROM Personal WHERE Id_Personal = '".$idTrabajador."'";
    $db -> query($SqlEliminar);*/
    $SqlUpdate = "UPDATE Personal set Activo = '2' WHERE Id_Personal='".$idTrabajador."' ";
    $db -> query($SqlUpdate);
  }

  public function modificaTrabajador($datos){


    $db = new Db();
    $isValid = 1;
    if(!($this->validarRutExiste($datos['rut'],$datos['idTrabajador']))){
      $SqlUpdate = "UPDATE Personal set Nombre = '".$datos['nombre']."', Rut = '".$datos['rut']."', Fecha_Nacimiento = '".$datos['fechaNacimiento']."', id_nacionalidad = '".$datos['nacionalidad']."', id_comuna = '".$datos['comuna']."', direccion = '".$datos['direccion']."', fono_particular = '".$datos['telefonoParticular']."', fono_movil = '".$datos['telefono']."', email = '".$datos['email']."', AFP = '".$datos['afp']."', sistema_salud = '".$datos['salud']."', UF = '".$datos['uf']."', GES = '".$datos['ges']."', pensionado = '".$datos['pensionado']."', remuneracion = '".$datos['remuneracion']."', id_cargo = '".$datos['cargo']."', id_sexo = '".$datos['sexo']."', id_estado = '".$datos['estadoCivil']."', hijos = '".$datos['hijos']."', id_tipo_ejecutivo = '".$datos['tipoEjecutivo']."', id_contrato = '".$datos['tipoContrato']."', fecha_ingreso = '".$datos['fechaIngreso']."', fecha_termino = '".$datos['fechaTermino']."', id_estatus_egreso = '".$datos['idEstatusEgreso']."' WHERE Id_Personal='".$datos['idTrabajador']."' ";
      $db -> query($SqlUpdate);

      $SqlEliminar = "DELETE FROM RH_contacto_personal WHERE id_personal = '".$datos['idTrabajador']."'";
      $db -> query($SqlEliminar); 

      if(($datos['contacto'] != "") && ($datos['parentesco'] != "") && ($datos['celular1'] != "")){
        $this->insertarContacto($datos['contacto'],$datos['parentesco'],$datos['celular1'],$datos['celular2'],$datos['idTrabajador']);
      } 

      if(($datos['contacto1'] != "") && ($datos['parentesco1'] != "") && ($datos['celular11'] != "")){
        $this->insertarContacto($datos['contacto1'],$datos['parentesco1'],$datos['celular11'],$datos['celular21'],$datos['idTrabajador']); 
      }



    } else{
      $isValid = 2;
    }
    return $isValid;     
  }

  public function getListarCargos(){
    $db = new Db();
    $cargosArray = array();
    $Sql = "SELECT * FROM RH_cargo ORDER BY cargo ASC";
    $cargos = $db -> select($Sql);
    foreach($cargos as $cargo){
      $Array = array();
      $Array['cargo'] = utf8_encode($cargo["cargo"]);
      $Array['id_cargo'] = $cargo["id_cargo"];
      array_push($cargosArray,$Array);
    }
    return $cargosArray;
  }

  public function getListarRegiones(){
    $db = new Db();
    $regionesArray = array();
    $Sql = "SELECT * FROM RH_region ORDER BY region ASC";
    $regiones = $db -> select($Sql);
    foreach($regiones as $region){
      $Array = array();
      $Array['region'] = utf8_encode($region["region"]);
      $Array['id_region'] = $region["id_region"];
      array_push($regionesArray,$Array);
    }
    return $regionesArray;
  }


  public function getListarProvincias($idRegion){
    $db = new Db();
    $provinciasArray = array();
    $Sql = "SELECT * FROM RH_provincia WHERE id_region = '".$idRegion."' ORDER BY provincia ASC";
    $provincias = $db -> select($Sql);
    foreach($provincias as $provincia){
      $Array = array();
      $Array['provincia'] = utf8_encode($provincia["provincia"]);
      $Array['id_provincia'] = $provincia["id_provincia"];
      array_push($provinciasArray,$Array);
    }
    return $provinciasArray;
  }

  public function getListarComunas($idProvincia){
    $db = new Db();
    $comunasArray = array();
    $Sql = "SELECT * FROM RH_comuna WHERE id_provincia = '".$idProvincia."' ORDER BY comuna ASC";
    $comunas = $db -> select($Sql);
    foreach($comunas as $comuna){
      $Array = array();
      $Array['comuna'] = utf8_encode($comuna["comuna"]);
      $Array['id_comuna'] = $comuna["id_comuna"];
      array_push($comunasArray,$Array);
    }
    return $comunasArray;
  }


   public function getListarNacionalidad(){
    $db = new Db();
    $nacionalidadesArray = array();
    $Sql = "SELECT * FROM RH_nacionalidad ORDER BY nacionalidad ASC";
    $nacionalidades = $db -> select($Sql);
    foreach($nacionalidades as $nacionalidad){
      $Array = array();
      $Array['nacionalidad'] = utf8_encode($nacionalidad["nacionalidad"]);
      $Array['id_nacionalidad'] = $nacionalidad["id_nacionalidad"];
      array_push($nacionalidadesArray,$Array);
    }
    return $nacionalidadesArray;
  }

  public function getListarSexo(){
    $db = new Db();
    $sexoArray = array();
    $Sql = "SELECT * FROM RH_sexo ORDER BY sexo ASC";
    $sexos = $db -> select($Sql);
    foreach($sexos as $sexo){
      $Array = array();
      $Array['sexo'] = $sexo["sexo"];
      $Array['id_sexo'] = $sexo["id_sexo"];
      array_push($sexoArray,$Array);
    }
    return $sexoArray;
  }

  public function getListarTipoEjecutivo(){
    $db = new Db();
    $ejecutivoArray = array();
    $Sql = "SELECT * FROM RH_tipo_ejecutivo";
    $ejecutivos = $db -> select($Sql);
    foreach($ejecutivos as $ejecutivo){
      $Array = array();
      $Array['tipoEjecutivo'] = utf8_encode($ejecutivo["tipo"]);
      $Array['id_tipoEjecutivo'] = $ejecutivo["id_tipo_ejecutivo"];
      array_push($ejecutivoArray,$Array);
    }
    return $ejecutivoArray;
  }

  public function getListarTipoContrato(){
    $db = new Db();
    $contratoArray = array();
    $Sql = "SELECT * FROM RH_tipo_contrato";
    $contratos = $db -> select($Sql);
    foreach($contratos as $contrato){
      $Array = array();
      $Array['contrato'] = utf8_encode($contrato["contrato"]);
      $Array['id_contrato'] = $contrato["id_tipo_contrato"];
      array_push($contratoArray,$Array);
    }
    return $contratoArray;
  }

  public function getListarAntiguedad(){
    $db = new Db();
    $antiguedadArray = array();
    $Sql = "SELECT * FROM RH_antiguedad";
    $antiguedades = $db -> select($Sql);
    foreach($antiguedades as $antiguedad){
      $Array = array();
      $Array['antiguedad'] = utf8_encode($antiguedad["antiguedad"]);
      $Array['id_antiguedad'] = $antiguedad["id_antiguedad"];
      array_push($antiguedadArray,$Array);
    }
    return $antiguedadArray;
  }

  public function getListarEstadoCivil(){
    $db = new Db();
    $estadoArray = array();
    $Sql = "SELECT * FROM RH_estado_civil";
    $estados = $db -> select($Sql);
    foreach($estados as $estado){
      $Array = array();
      $Array['estado'] = utf8_encode($estado["estado"]);
      $Array['id_estado'] = $estado["id_estado"];
      array_push($estadoArray,$Array);
    }
    return $estadoArray;
  }

  public function getListarMotivoEgreso(){
    $db = new Db();
    $estatusArray = array();
    $Sql = "SELECT * FROM RH_estatus_egreso";
    $estatusE = $db -> select($Sql);
    foreach($estatusE as $estatus){
      $Array = array();
      $Array['estatusEgreso'] = utf8_encode($estatus["nombre"]);
      $Array['idEstatusEgreso'] = $estatus["id"];
      array_push($estatusArray,$Array);
    }
    return $estatusArray;
  }

}
?>