<?php
    class Calidad{
        public $dir;
        public $dirTmp;

        public $Filename;
        public $Date;
        public $Phone;
        public $Cartera;
        public $User;

        public $Id_Evaluacion;
        public $Evaluacion_Final;
        public $Aspectos_Fortalecer;
        public $Aspectos_Corregir;
        public $Compromiso_Ejecutivo;

        public $TipoCierre;

        public $Id_Personal;
        public $Id_Usuario;
        public $Id_Grabacion;


        public $Description;
        public $Esperado;
        public $Ponderacion;
        public $Nota;
        public $CalificacionPonderada;
        public $Observacion;
        public $Resumen;

        public $startDate;
        public $endDate;

        public $Id_Mandante;
        public $Id_Cedente;

        public $EvaluatedColum;
        public $EvaluatedValue;

        public $Id_Cierre;

        public $Tipificacion;

        public $NotaMaximaEvaluacion;

        function __construct(){
            $this->dir = "../../Records/";
            $this->dirTmp = "../../Records/Tmp/";
            $this->Id_Usuario = isset($_SESSION['id_usuario']) ? $_SESSION['id_usuario']: "";
            $this->Id_Mandante = isset($_SESSION['mandante']) ? $_SESSION['mandante'] : "";
            $this->Id_Cedente = isset($_SESSION['cedente']) ? $_SESSION['cedente']: "";
            if(isset($_SESSION['MM_UserGroup'])){
                if($this->isUserMandante()){
                    switch($_SESSION['MM_UserGroup']){
                        case 2:
                            $this->EvaluatedColum = ",bySupervisorMandante";
                            $this->EvaluatedValue = ",1";
                        break;
                        case 4:
                            $this->EvaluatedColum = ",byEjecutivoMandante";
                            $this->EvaluatedValue = ",1";
                        break;
                        case 6:
                            $this->EvaluatedColum = ",byCalidadMandante";
                            $this->EvaluatedValue = ",1";
                        break;
                    }
                }else{
                    switch($_SESSION['MM_UserGroup']){
                        case 2:
                            $this->EvaluatedColum = ",bySupervisorSystem";
                            $this->EvaluatedValue = ",1";
                        break;
                        case 4:
                            $this->EvaluatedColum = ",byEjecutivoSystem";
                            $this->EvaluatedValue = ",1";
                        break;
                        case 6:
                            $this->EvaluatedColum = ",byCalidadSystem";
                            $this->EvaluatedValue = ",1";
                        break;
                    }
                }
            }
            $this->NotaMaximaEvaluacion = 5;
        }

        function getRecordListAjax(){
            $db = new Db();
            $RecordsArray = array();
            $Cont = 0;
            $WhereTipificacion = $this->Tipificacion != "" ? " and gestion_ult_trimestre.Id_TipoGestion='".$this->Tipificacion."' " : "";
            $SqlRecord = "select grabacion_2.*,Tipo_Contacto.Nombre as Contacto from grabacion_2 inner join Cedente on Cedente.Nombre_Cedente = grabacion_2.Cartera inner join mandante_cedente on mandante_cedente.Id_Cedente = Cedente.Id_Cedente inner join gestion_ult_trimestre on gestion_ult_trimestre.nombre_grabacion = SUBSTR(grabacion_2.Nombre_Grabacion, 1, POSITION('-all' IN grabacion_2.Nombre_Grabacion) - 1) inner join Tipo_Contacto on Tipo_Contacto.Id_TipoContacto = gestion_ult_trimestre.Id_TipoGestion where grabacion_2.usuario = '".$this->User."' and mandante_cedente.Id_Mandante = '".$this->Id_Mandante."' and grabacion_2.Fecha BETWEEN '".$this->startDate."' and '".$this->endDate."' ".$WhereTipificacion." order by Fecha";
		    $Records = $db -> select($SqlRecord);
            foreach($Records as $Record){
                $this->Id_Grabacion = $Record['id'];
                $RecordArrayTmp = array();
                $RecordArrayTmp["Filename"] = $Record["Nombre_Grabacion"];
                $RecordArrayTmp["Date"] = $Record["Fecha"];
                $RecordArrayTmp["Cartera"] = $Record["Cartera"];
                $RecordArrayTmp["User"] = $Record["Usuario"];
                $RecordArrayTmp["Phone"] = $Record["Telefono"];
                $RecordArrayTmp["Listen"] = $this->dir.$this->getRutaGrabaciones($Record["Nombre_Grabacion"])."/".$Record["Nombre_Grabacion"];
                $RecordArrayTmp["Status"] = $this->hasEvaluation() ? "Evaluada" : "";//$Record["Estado"] == "1" ? "Evaluada" : "";
                $RecordArrayTmp["Evaluar"] = $Record["id"];
                $RecordArrayTmp["Imprimir"] = $Record["id"];
                $RecordArrayTmp["Tipificacion"] = $Record["Contacto"];
                $RecordsArray[$Cont] = $RecordArrayTmp;
                $Cont++;
            }
            return $RecordsArray;
        }
        function getTipificacionGrabaciones(){
            $db = new DB();
            $SqlTipificacion = "select gestion_ult_trimestre.Id_TipoGestion as id,Tipo_Contacto.Nombre as Contacto from grabacion_2 inner join Cedente on Cedente.Nombre_Cedente = grabacion_2.Cartera inner join mandante_cedente on mandante_cedente.Id_Cedente = Cedente.Id_Cedente inner join gestion_ult_trimestre on gestion_ult_trimestre.nombre_grabacion = SUBSTR(grabacion_2.Nombre_Grabacion, 1, POSITION('-all' IN grabacion_2.Nombre_Grabacion) - 1) inner join Tipo_Contacto on Tipo_Contacto.Id_TipoContacto = gestion_ult_trimestre.Id_TipoGestion where grabacion_2.usuario = '".$this->User."' and mandante_cedente.Id_Mandante = '".$this->Id_Mandante."' and grabacion_2.Fecha BETWEEN '".$this->startDate."' and '".$this->endDate."' GROUP BY gestion_ult_trimestre.Id_TipoGestion order by Tipo_Contacto.Nombre";
		    $Tipificacion = $db -> select($SqlTipificacion);
            return $Tipificacion;
        }
        function getRecordList(){
            $db = new Db();
            $SqlRecord = "select * from grabacion_2 order by Fecha";
		    $Records = $db -> select($SqlRecord);
            return $Records;
        }
        public function InsertRecordsToDataBase(){
            $Cedentes = $this->getCedenteArray();
            $files = scandir($this->dirTmp);
            $files = array_diff(scandir($this->dirTmp), array('.', '..'));
            $Cont = 1;
            foreach($files as $File){
                $Filename = $File;
                $Name = substr($Filename,0,strpos($Filename,"."));
                $Extension = substr($Filename,strpos($Filename,"."),strlen($Filename));
                $Date = substr($Name,0,strpos($Name,"-"));
                $ArrayDataTmp = explode("_",substr($Name,strpos($Name,"-") + 1));
                $DataTmp1 = $ArrayDataTmp[0];
                $Phone = $ArrayDataTmp[1];
                $Cartera = $ArrayDataTmp[2];
                $User = substr($ArrayDataTmp[3],0,strpos($ArrayDataTmp[3],"-"));
                $DataTmp2 = substr($ArrayDataTmp[3],strpos($ArrayDataTmp[3],"-") + 1,strlen($ArrayDataTmp[3]));
                $this->Filename = $Filename;
                $this->Date = $Date;
                $this->User = $User;
                $this->Phone = $Phone;

                if(isset($Cedentes[$Cartera])){
                    $Cartera = $Cedentes[$Cartera];
                    $this->Cartera = $Cartera;
                    $this->addRecord();
                    //
                        //rename($this->dirTmp.$Filename, $this->dir.$Filename);
                        unlink($this->dirTmp.$Filename);
                    //
                }else{
                    echo "No paso: ".$Cartera." - ".$Filename."<br>";
                }
                $Cont++;
                
            }
        }
        /* function getCedenteArray(){
            $ToReturn = array();
            $db = new DB();
            $SqlCedentes = "select vicidial_campaigns.campaign_id as campaign, Cedente.Nombre_Cedente as nombre from Cedente inner join Cedente_Campaign on Cedente_Campaign.id_cedente = Cedente.Id_Cedente inner join vicidial_campaigns on vicidial_campaigns.campaign_id = Cedente_Campaign.id_campaign";
            $Cedentes = $db->select($SqlCedentes);
            foreach($Cedentes as $Cedente){
                $ToReturn[$Cedente["campaign"]] = $Cedente["nombre"];
            }
            return $ToReturn;
        } */
        function getCedenteArray(){
            $ToReturn = array();
            $db = new DB();
            $SqlCedentes = "select Cedente.Nombre_Cedente as NombreCedente, mandante_cedente.Lista_Vicidial as Campanas from mandante_cedente inner join Cedente on Cedente.Id_Cedente = mandante_cedente.Id_Cedente";
            $Cedentes = $db->select($SqlCedentes);
            foreach($Cedentes as $Cedente){
                $NombreCedente = utf8_encode($Cedente['NombreCedente']);
                $Campanas = $Cedente['Campanas'];
                $ArrayCampanas = explode(",",$Campanas);
                if(count($ArrayCampanas) > 0){
                    foreach($ArrayCampanas as $Campana){
                        $NuevaCampana = $Campana;
                        if(strlen($NuevaCampana) == 1){
                            $NuevaCampana = "00".$Campana;
                        }
                        if(strlen($NuevaCampana) == 2){
                            $NuevaCampana = "0".$Campana;
                        }
                        $ToReturn[$NuevaCampana] = $NombreCedente;
                    }
                }
            }
            return $ToReturn;
        }
        function addRecord(){
            $db = new Db();
            $ToReturn = false;
            $SqlInsertRecord = "insert into grabacion_2 (Nombre_Grabacion, Fecha, Cartera, Usuario, Telefono) values('".$this->Filename."','".$this->Date."','".$this->Cartera."','".$this->User."','".$this->Phone."')";
            $InsertRecord = $db -> query($SqlInsertRecord);
            if($InsertRecord !== false){
                $ToReturn = true;
            }else{
                $ToReturn = false;
            }
            return $ToReturn;
        }
        function AddEvaluation(){
            $db = new Db();
            $ToReturn = false;
            $Cedente = "";
            $Nivel = $_SESSION["MM_UserGroup"];
            switch($Nivel){
                case '4':
                $Cedente = $this->getIdCedenteFromGrabacion($this->Id_Grabacion);
                break;
                default:
                $Cedente = $_SESSION["cedente"];
                break;
            }
            $SqlInsertEvaluation = "insert into evaluaciones (Id_Personal, Id_Usuario, Id_Grabacion, Evaluacion_Final, Fecha_Evaluacion, Id_Cedente".$this->EvaluatedColum.") values('".$this->Id_Personal."','".$this->Id_Usuario."','".$this->Id_Grabacion."','".$this->Evaluacion_Final."',".$this->getFechaEvaluacion($this->Id_Grabacion).",'".$Cedente."'".$this->EvaluatedValue.")";
            $InsertEvaluation = $db -> query($SqlInsertEvaluation);
            if($InsertEvaluation !== false){
                $ToReturn = $this->getLastEvaluationAdded();
            }else{
                $ToReturn = false;
            }
            return $this->getLastEvaluationAdded();
        }
        function getFechaEvaluacion($idGrabacion){
            $db = new DB();
            $ToReturn = "";
            $SqlFecha = "select Fecha from grabacion_2 where id='".$idGrabacion."'";
            $Fecha = $db->select($SqlFecha);
            $Fecha = $Fecha[0]["Fecha"];
            $ActualMonth = date('m');
            $GrabacionMonth = date('m',strtotime($Fecha));
            $ActualYear = date('y');
            $GrabacionYear = date('y',strtotime($Fecha));
            $ToReturn = "'".date('Y-m-t',strtotime($Fecha))."'";
            if($ActualYear == $GrabacionYear){
                if($ActualMonth == $GrabacionMonth){
                    $ToReturn = "NOW()";
                }
            }
            return $ToReturn;
        }
        function getIdCedenteFromGrabacion($IdGrabacion){
            $ToReturn = "";
            $db = new Db();
            $SqlCedente = "select Cedente.Id_Cedente as cedente from grabacion_2 inner join Cedente on Cedente.Nombre_Cedente = grabacion_2.Cartera where grabacion_2.id='".$IdGrabacion."'";
		    $Cedentes = $db -> select($SqlCedente);
            $ToReturn = $Cedentes[0]["cedente"];
            return $ToReturn;
        }
        function getLastEvaluationAdded(){
            $ToReturn = false;
            $db = new Db();
            $SqlEvaluation = "select max(id) as id from evaluaciones where Id_Usuario = '".$this->Id_Usuario."' and Id_Grabacion = '".$this->Id_Grabacion."' and Id_Personal = '".$this->Id_Personal."' LIMIT 1";
		    $Evaluations = $db -> select($SqlEvaluation);
            $Evaluation = $Evaluations[0]["id"];
            return $Evaluation;
        }
        function hasEvaluation(){
            $ToReturn = false;
            $db = new Db();
            $SqlEvaluation = "select * from evaluaciones where Id_Usuario = '".$this->Id_Usuario."' and Id_Grabacion = '".$this->Id_Grabacion."'";
		    $Evaluations = $db -> select($SqlEvaluation);
            if(count($Evaluations) > 0){
                $ToReturn = true;
            }
            return $ToReturn;
        }
        function getEvaluation(){
            $db = new Db();
            $SqlEvaluation = "select * from evaluaciones where Id_Grabacion = '".$this->Id_Grabacion."' and Id_Usuario = '".$this->Id_Usuario."'";
		    $Evaluations = $db -> select($SqlEvaluation);
            return $Evaluations;
        }
        function getEvaluationByUser(){
            $db = new Db();
            $SqlEvaluation = "select evaluaciones.*, grabacion_2.Nombre_Grabacion from evaluaciones inner join grabacion_2 on grabacion_2.id = evaluaciones.Id_Grabacion where Id_Grabacion = '".$this->Id_Grabacion."' and Id_Usuario = '".$this->Id_Usuario."'";
		    $Evaluations = $db -> select($SqlEvaluation);
            return $Evaluations;
        }
/*function getEvaluationDetails(){
    $db = new Db();
    $EvaluationsArray = array();
    $Cont = 0;
    $SqlEvaluation = "select * from detalle_evaluaciones where Id_Evaluacion = '".$this->Id_Evaluacion."' order by resumen ASC";
    $Evaluations = $db -> select($SqlEvaluation);
    foreach($Evaluations as $Evaluation){
        $EvaluationArray = array();
        $EvaluationArray['Nombre'] = $Evaluation["resumen"];
        $EvaluationArray['Descripcion'] = $Evaluation["Descripcion"];
        $EvaluationArray['Esperado'] = ($Evaluation["Esperado"]);
        $EvaluationArray['Ponderacion'] = number_format($Evaluation["Ponderacion"], 2, '.', '');
        $EvaluationArray['Nota'] = number_format($Evaluation["Nota"], 2, '.', '');
        $EvaluationArray['CalificacionPonderada'] = number_format(($Evaluation["Ponderacion"] * $Evaluation["Nota"]) / 100, 2,'.','');
        $EvaluationArray['ID'] = "";
        $EvaluationArray['Actions'] = "";
        $EvaluationsArray[$Cont] = $EvaluationArray;
        $Cont++;
    }
    return $EvaluationsArray;
}*/
        function getEvaluationTemplate(){
            $db = new Db();
            $EvaluationsArray = array();
            $Cont = 0;
            $SqlEvaluation = "select
                                    competencias.id,
                                    competencias.nombre,
                                    competencias.tag,
                                    competencias.descripcion,
                                    competencias.ponderacion,
                                    group_concat(' ',afirmaciones.descripcion_simple) as PalabrasClaves
                                from
                                    competencias_calidad competencias
                                        inner join dimensiones_competencias_calidad dimensiones on dimensiones.id_competencia = competencias.id
                                        inner join afirmaciones_dimensiones_competencias_calidad afirmaciones on afirmaciones.id_dimension = dimensiones.id
                                group by
                                    competencias.id
                                order by
                                    competencias.nombre";
		    $Evaluations = $db -> select($SqlEvaluation);
            foreach($Evaluations as $Evaluation){
                $EvaluationArray = array();
                $EvaluationArray['Nombre'] = utf8_encode($Evaluation["nombre"]);
                $EvaluationArray['Tag'] = utf8_encode($Evaluation["tag"]);
                $EvaluationArray['Descripcion'] = utf8_encode($Evaluation["PalabrasClaves"]);
                $EvaluationArray['Esperado'] = utf8_encode($Evaluation["descripcion"]);
                $EvaluationArray['Ponderacion'] = number_format($Evaluation["ponderacion"], 2, '.', '');
                $EvaluationArray['Nota'] = number_format(0, 2, '.', '');
                $EvaluationArray['ID'] = $Evaluation["id"];
                $EvaluationsArray[$Cont] = $EvaluationArray;
                $Cont++;
            }
            return $EvaluationsArray;
        }
        function getEvaluationTemplateByPerfil($idPerfil){
            $db = new Db();
            $EvaluationsArray = array();
            $Cont = 0;
            //$SqlEvaluation = "select distinct mantenedor_evaluaciones.* from mantenedor_evaluaciones inner join perfil_personal on perfil_personal.id = mantenedor_evaluaciones.id_perfil inner join Personal on Personal.id_perfil = perfil_personal.id where perfil_personal.id = '".$idPerfil."' order by resumen";
            $SqlEvaluation = "select * from competencias_calidad order by nombre";
		    $Evaluations = $db -> select($SqlEvaluation);
            foreach($Evaluations as $Evaluation){
                $EvaluationArray = array();
                $EvaluationArray['Nombre'] = utf8_encode($Evaluation["nombre"]);
                $EvaluationArray['Descripcion'] = utf8_encode($Evaluation["descripcion"]);
                $EvaluationArray['Esperado'] = utf8_encode($Evaluation["Esperado"]);
                $EvaluationArray['Ponderacion'] = number_format($Evaluation["ponderacion"], 2, '.', '');
                $EvaluationArray['Nota'] = number_format(0, 2, '.', '');
                $EvaluationArray['CalificacionPonderada'] = number_format(0, 2, '.', '');
                $EvaluationArray['ID'] = "";
                $EvaluationArray['Actions'] = "";
                $EvaluationsArray[$Cont] = $EvaluationArray;
                $Cont++;
            }
            return $EvaluationsArray;
        }
        function deleteEvaluationDetails(){
            $db = new Db();
            $ToReturn = false;
            $SqlDeleteEvaluacionDetail = "delete from respuesta_opciones_afirmaciones_calidad where Id_Evaluacion = ".$this->Id_Evaluacion;
            $DeleteEvaluacionDetail = $db -> query($SqlDeleteEvaluacionDetail);
            if($DeleteEvaluacionDetail !== false){
                $ToReturn = true;
            }else{
                $ToReturn = false;
            }
            return $ToReturn;
        }
        function addEvaluationDetails($Competencias){
            $db = new Db();
            foreach($Competencias as $Competencia){
                foreach($Competencia as $Afirmacion){
                    $ArrayAfirmacion = explode("|",$Afirmacion);
                    $idAfirmacion = $ArrayAfirmacion[0];
                    $notaAfirmacion = number_format($ArrayAfirmacion[1],5);
                    $valorAfirmacion = $ArrayAfirmacion[2];
                    $SqlInsertAfirmacion = "insert into respuesta_opciones_afirmaciones_calidad (Id_Evaluacion, id_afirmacion, Id_Mandante, Valor, Nota) values('".$this->Id_Evaluacion."', '".$idAfirmacion."', '".$this->Id_Mandante."', '".$valorAfirmacion."', '".$notaAfirmacion."')";
                    $InsertAfirmacion = $db -> query($SqlInsertAfirmacion);
                }
            }

        }
        function updateEvaluation(){
            $db = new Db();
            $ToReturn = false;
            $SqlUpdateEvaluation = "update evaluaciones set Evaluacion_Final = '".$this->Evaluacion_Final."' where Id_Grabacion='".$this->Id_Grabacion."' and Id_Usuario = '".$this->Id_Usuario."'";
            $UpdateEvaluation = $db -> query($SqlUpdateEvaluation);
            $ToReturn = $this->getEvaluationID();
            return $ToReturn;
        }
        function getEvaluationID(){
            $db = new Db();
            $SqlEvaluation = "select id from evaluaciones where Id_Grabacion = '".$this->Id_Grabacion."' and Id_Usuario = '".$this->Id_Usuario."'";
		    $Evaluations = $db -> select($SqlEvaluation);
            return $Evaluations[0]["id"];
        }
        function getEvaluations_Managment(){
            $db = new Db();
            $EvaluationsArray = array();
            $Cont = 0;
            $SqlEvaluation = "select * from mantenedor_evaluaciones order by id";
		    $Evaluations = $db -> select($SqlEvaluation);
            foreach($Evaluations as $Evaluation){
                $EvaluationArray = array();
                $EvaluationArray['Descripcion'] = utf8_encode($Evaluation["Descripcion"]);
                $EvaluationArray['Ponderacion'] = number_format($Evaluation["Ponderacion"], 2, '.', '');
                $EvaluationArray['Actions'] = $Evaluation["id"];
                $EvaluationsArray[$Cont] = $EvaluationArray;
                $Cont++;
            }
            return $EvaluationsArray;
        }
        function AddEvaluation_Managment(){
            $db = new Db();
            $ToReturn = false;
            $SqlInsertEvaluation = "insert into mantenedor_evaluaciones (Descripcion, Ponderacion) values('".$this->Description."','".$this->Ponderacion."')";
            $InsertEvaluation = $db -> query($SqlInsertEvaluation);
            if($InsertEvaluation !== false){
                $ToReturn = $db->getLastID();
            }else{
                $ToReturn = false;
            }
            return $db->getLastID();
        }
        function updateEvaluation_Managment(){
            $db = new Db();
            $ToReturn = false;
            $SqlUpdateEvaluation = "update mantenedor_evaluaciones set Descripcion = '".$this->Description."', Ponderacion = '".$this->Ponderacion."' where id='".$this->Id_Evaluacion."' ";
            $UpdateEvaluation = $db -> query($SqlUpdateEvaluation);
            if($UpdateEvaluation !== false){
                $ToReturn = true;
            }else{
                $ToReturn = false;
            }
            return $ToReturn;
        }
        function deleteEvaluation_Managment(){
            $db = new Db();
            $ToReturn = false;
            $SqlDeleteEvaluacion = "delete from mantenedor_evaluaciones where id = ".$this->Id_Evaluacion;
            $DeleteEvaluacion = $db -> query($SqlDeleteEvaluacion);
            if($DeleteEvaluacion !== false){
                $ToReturn = true;
            }else{
                $ToReturn = false;
            }
            return $ToReturn;
        }
        function getCarteraList(){
            $db = new Db();
            $SqlCartera = "select distinct Cartera from grabacion_2 INNER JOIN Cedente on Cedente.Nombre_Cedente = grabacion_2.Cartera INNER JOIN mandante_cedente on mandante_cedente.Id_Cedente = Cedente.Id_Cedente INNER JOIN mandante on mandante.id = mandante_cedente.Id_Mandante where mandante.id = '".$this->Id_Mandante."' order by grabacion_2.Cartera";
		    $Carteras = $db -> select($SqlCartera);
            return $Carteras;
        }
        function getEvaluationsFromRecords($Records){
            $db = new Db();
            $SqlRecord = "select * from evaluaciones where Id_Grabacion in (".$Records.") and Id_Usuario = '".$this->Id_Usuario."' order by id";
		    $Records = $db -> select($SqlRecord);
            return $Records;
        }
        function getRecordListEvaluadosAjax($Periodo = ""){
            $db = new Db();
            $PersonalClass = new Personal();
            $PersonalClass->Username = $this->User;
            $Id_Personal = $PersonalClass->getPersonalIDFromUsername();
            $RecordsArray = array();
            $Cont = 0;
            $WhereTipificacion = $this->Tipificacion != "" ? " and gestion_ult_trimestre.Id_TipoGestion='".$this->Tipificacion."' " : "";
            $Desde = date('Ym01',strtotime($Periodo));
            $Hasta = date('Ymt',strtotime($Desde));
            $Nivel = $_SESSION["MM_UserGroup"];
            $WhereCedente = "";
            switch($Nivel){
                case '4':
                break;
                default:
                //$WhereCedente = " Cedente.Id_Cedente = '".$this->Id_Cedente."' and ";
                break;
            }
            $HaveCierre = $this->HizoCierre($Periodo);
            $WhereEvaluacionesCierres = "";
            if($HaveCierre){
                $WhereEvaluacionesCierres = " and find_in_set(evaluaciones.id,(select group_concat(Id_Evaluaciones) from cierre_evaluaciones where Id_Usuario = '".$this->Id_Usuario."' and Id_Personal = '".$Id_Personal."' and Id_Evaluaciones <> '')) <= 0 ";
            }
            $SqlRecord = "select
                            grabacion_2.id as id,
                            grabacion_2.Nombre_Grabacion as Nombre_Grabacion,
                            grabacion_2.Fecha as Fecha,
                            grabacion_2.Cartera as Cartera,
                            grabacion_2.Usuario as Usuario,
                            grabacion_2.Telefono as Telefono,
                            Tipo_Contacto.Nombre as Tipificacion
                        from evaluaciones
                            inner join grabacion_2 on grabacion_2.id = evaluaciones.Id_Grabacion
                            inner join Cedente on Cedente.Id_Cedente = evaluaciones.Id_Cedente
                            inner join mandante_cedente on mandante_cedente.Id_Cedente = Cedente.Id_Cedente
                            inner join mandante on mandante.id = mandante_cedente.Id_Mandante
                            inner join gestion_ult_trimestre on gestion_ult_trimestre.nombre_grabacion = SUBSTR(grabacion_2.Nombre_Grabacion, 1, POSITION('-all' IN grabacion_2.Nombre_Grabacion) - 1)
                            inner join Tipo_Contacto on Tipo_Contacto.Id_TipoContacto = gestion_ult_trimestre.Id_TipoGestion
                        where
                            mandante.id = '".$this->Id_Mandante."' and
                            ".$WhereCedente."
                            grabacion_2.Usuario = '".$this->User."' and
                            grabacion_2.Fecha BETWEEN '".$Desde."' and '".$Hasta."' /*and
                            evaluaciones.Id_Usuario='".$this->Id_Usuario."'*/
                            ".$WhereEvaluacionesCierres."
                            ".$WhereTipificacion."
                        group by
                            grabacion_2.id,
                            grabacion_2.Nombre_Grabacion,
                            grabacion_2.Cartera,
                            grabacion_2.Usuario,
                            grabacion_2.Telefono,
                            gestion_ult_trimestre.Id_TipoGestion";
		    $Records = $db -> select($SqlRecord);
            foreach($Records as $Record){
                $this->Id_Grabacion = $Record['id'];
                $RecordArrayTmp = array();
                $RecordArrayTmp["Filename"] = $Record["Nombre_Grabacion"];
                $RecordArrayTmp["Date"] = $Record["Fecha"];
                $RecordArrayTmp["Cartera"] = $Record["Cartera"];
                $RecordArrayTmp["User"] = $Record["Usuario"];
                $RecordArrayTmp["Phone"] = $Record["Telefono"];
                $RecordArrayTmp["Listen"] = $this->dir.$this->getRutaGrabaciones($Record["Nombre_Grabacion"])."/".$Record["Nombre_Grabacion"];
                $RecordArrayTmp["Status"] = $this->hasEvaluation() ? "Evaluada" : "";//$Record["Estado"] == "1" ? "Evaluada" : "";
                $RecordArrayTmp["Evaluar"] = $Record["id"];
                $RecordArrayTmp["Imprimir"] = $Record["id"];
                $RecordArrayTmp["Tipificacion"] = $Record["Tipificacion"];
                $RecordsArray[$Cont] = $RecordArrayTmp;
                $Cont++;
            }
            return $RecordsArray;
        }
        function getTipificacionGrabacionesEvaluadas($Periodo){
            $db = new DB();
            $Desde = date('Ym01',strtotime($Periodo));
            $Hasta = date('Ymt',strtotime($Desde));
            $SqlTipificacion = "select
                            gestion_ult_trimestre.Id_TipoGestion as id,
                            Tipo_Contacto.Nombre as Tipificacion
                        from evaluaciones
                            inner join grabacion_2 on grabacion_2.id = evaluaciones.Id_Grabacion
                            inner join Cedente on Cedente.Id_Cedente = evaluaciones.Id_Cedente
                            inner join mandante_cedente on mandante_cedente.Id_Cedente = Cedente.Id_Cedente
                            inner join mandante on mandante.id = mandante_cedente.Id_Mandante
                            inner join gestion_ult_trimestre on gestion_ult_trimestre.nombre_grabacion = SUBSTR(grabacion_2.Nombre_Grabacion, 1, POSITION('-all' IN grabacion_2.Nombre_Grabacion) - 1)
                            inner join Tipo_Contacto on Tipo_Contacto.Id_TipoContacto = gestion_ult_trimestre.Id_TipoGestion
                        where
                            mandante.id = '".$this->Id_Mandante."' and
                            grabacion_2.Usuario = '".$this->User."' and
                            grabacion_2.Fecha BETWEEN '".$Desde."' and '".$Hasta."'
                        group by
                            gestion_ult_trimestre.Id_TipoGestion";
            $Tipificacion = $db->select($SqlTipificacion);
            return $Tipificacion;
        }
        function isUserMandante(){
            $ToReturn = false;
            $db = new Db();
            $SqlUser = "select * from Usuarios where id = '".$this->Id_Usuario."'";
		    $Users = $db -> select($SqlUser);
            foreach($Users as $User){
                if($User["mandante"] != ""){
                    $ToReturn = true;
                }
            }
            return $ToReturn;
        }
        function Empiezo(){
            $ToReturn = false;
            $db = new Db();
            $SqlMandante = "select * from mandante where id = '".$this->Id_Mandante."'";
		    $Mandantes = $db -> select($SqlMandante);
            foreach($Mandantes as $Mandante){
                if($this->isUserMandante()){
                    if($Mandante["Empieza"] == "1"){
                        $ToReturn = true;
                    }
                }else{
                    if($Mandante["Empieza"] == "0"){
                        $ToReturn = true;
                    }
                }
            }
            return $ToReturn;
        }
        function PuedeHacerCierreDeProceso($Periodo = ""){
            $ToReturn = false;
            $db = new Db();
            $Desde = date('Ym01',strtotime($Periodo));
            $Hasta = date('Ymt',strtotime($Desde));
            $SqlEvaluation = "select
                                    grabacion_2.id as id,
                                    grabacion_2.Nombre_Grabacion as Nombre_Grabacion,
                                    grabacion_2.Fecha as Fecha,
                                    grabacion_2.Cartera as Cartera,
                                    grabacion_2.Usuario as Usuario,
                                    grabacion_2.Telefono as Telefono
                                from evaluaciones
                                    inner join grabacion_2 on grabacion_2.id = evaluaciones.Id_Grabacion
                                    inner join Cedente on Cedente.Id_Cedente = evaluaciones.Id_Cedente
                                    inner join mandante_cedente on mandante_cedente.Id_Cedente = Cedente.Id_Cedente
                                    inner join mandante on mandante.id = mandante_cedente.Id_Mandante
                                where
                                    evaluaciones.Id_Usuario = '".$this->Id_Usuario."' and
                                    mandante.id = '".$this->Id_Mandante."' and
                                    grabacion_2.Usuario = '".$this->User."' and
                                    grabacion_2.Fecha BETWEEN '".$Desde."' and '".$Hasta."'
                                group by
                                    grabacion_2.id,
                                    grabacion_2.Nombre_Grabacion,
                                    grabacion_2.Cartera,
                                    grabacion_2.Usuario,
                                    grabacion_2.Telefono";
		    $Evaluations = $db -> select($SqlEvaluation);
            if(count($Evaluations) > 0){
                $ToReturn = true;
            }
            return $ToReturn;
        }
        function CierreDeProceso($Periodo){
            $Evaluations = $this->GetEvaluatedEvaluations($Periodo);
            $this->InsertCierreDeProceso($Evaluations,$Periodo);
        }
        function GetEvaluatedEvaluations($Periodo){
            $Desde = date('Ym01',strtotime($Periodo));
            $Hasta = date('Ymt',strtotime($Desde));
            $db = new Db();
            $PersonalClass = new Personal();
            $PersonalClass->Username = $this->User;
            $Id_Personal = $PersonalClass->getPersonalIDFromUsername();
            $HaveCierre = $this->HizoCierre($Periodo);
            $WhereEvaluacionesCierres = "";
            if($HaveCierre){
                $WhereEvaluacionesCierres = " and find_in_set(evaluaciones.id,(select group_concat(Id_Evaluaciones) from cierre_evaluaciones where Id_Usuario = '".$this->Id_Usuario."' and Id_Personal = '".$Id_Personal."' and Id_Evaluaciones <> '')) <= 0 ";
            }
            $SqlEvaluation = "select
                                    evaluaciones.*
                                from evaluaciones
                                    inner join grabacion_2 on grabacion_2.id = evaluaciones.Id_Grabacion
                                    inner join Cedente on Cedente.Id_Cedente = evaluaciones.Id_Cedente
                                    inner join mandante_cedente on mandante_cedente.Id_Cedente = Cedente.Id_Cedente
                                    inner join mandante on mandante.id = mandante_cedente.Id_Mandante
                                where
                                    evaluaciones.Id_Usuario = '".$this->Id_Usuario."' and
                                    mandante.id = '".$this->Id_Mandante."' and
                                    grabacion_2.Usuario = '".$this->User."' and
                                    grabacion_2.Fecha BETWEEN '".$Desde."' and '".$Hasta."'
                                    ".$WhereEvaluacionesCierres."
                                group by
                                    grabacion_2.id,
                                    grabacion_2.Nombre_Grabacion,
                                    grabacion_2.Cartera,
                                    grabacion_2.Usuario,
                                    grabacion_2.Telefono";
		    $Evaluations = $db -> select($SqlEvaluation);
            return $Evaluations;
        }
        function InsertCierreDeProceso($Evaluations,$Periodo){
            $db = new Db();
            $PersonalClass = new Personal();
            $PersonalClass->Username = $this->User;
            $Id_Personal = $PersonalClass->getPersonalIDFromUsername();
            $ToReturn = false;
            $Nota = 0;
            $Ponderacion = 0;
            $CalfPonderada = 0;
            $Id_Evaluaciones = "";
            foreach($Evaluations as $Evaluation){
                $this->Id_Evaluacion = $Evaluation['id'];
                $Id_Evaluaciones = $Id_Evaluaciones ."". $Evaluation['id'].",";
                $ArrayResumenDetalle = $this->GetDetalleEvaluaciones_Resumen();
                $Nota += $ArrayResumenDetalle["Nota"];
            }
            $Id_Evaluaciones = substr($Id_Evaluaciones,0,strlen($Id_Evaluaciones) - 1);
            $Nota = $Nota / count($Evaluations);
            $Fecha = "'".date('Y-m-t',strtotime($Periodo))."'";
            $ActualMonth = date('m');
            $ActualYear = date('y');
            $PeriodoMonth = date('m', strtotime($Periodo));
            $PeriodoYear = date('y', strtotime($Periodo));
            if($ActualYear == $PeriodoYear){
                if($ActualMonth == $PeriodoMonth){
                    $Fecha = "NOW()";
                }
            }
            $SqlInsertCierre = "insert into cierre_evaluaciones (Id_Evaluaciones,tipo_cierre,Nota,Ponderacion,Calf_Ponderada,Id_Usuario,Id_Mandante,Id_Cedente,Id_Personal,Aspectos_Fortalecer,Aspectos_Corregir,Compromiso_Ejecutivo,fecha) values('".$Id_Evaluaciones."','".$this->TipoCierre."','".$Nota."','".$Ponderacion."','".$CalfPonderada."','".$this->Id_Usuario."','".$this->Id_Mandante."','".$this->Id_Cedente."','".$Id_Personal."','".$this->Aspectos_Fortalecer."','".$this->Aspectos_Corregir."','".$this->Compromiso_Ejecutivo."',".$Fecha.")";
            $InsertCierre = $db->query($SqlInsertCierre);
        }
        function GetDetalleEvaluaciones_Resumen(){
            $ToReturn = array();
            $db = new Db();
            $Query = "select
                        AVG(Nota) as Nota,
                    from
                        respuesta_opciones_afirmaciones_calidad
                    where
                        Id_Evaluacion = '".$this->Id_Evaluacion."'";
            $EvaluationResume = $db -> select($Query);
            foreach($EvaluationResume as $Evaluation){
                $ToReturn["Nota"] = $Evaluation["Nota"];
            }
            return $ToReturn;
        }
        function HizoCierre($Periodo = ""){
            $ToReturn = false;
            $db = new Db();
            $PersonalClass = new Personal();
            $PersonalClass->Username = $this->User;
            $Id_Personal = $PersonalClass->getPersonalIDFromUsername();
            $Query = "select
                            id
                        from
                            cierre_evaluaciones
                        where
                            Id_Usuario = '".$this->Id_Usuario."' and
                            Id_Mandante = '".$this->Id_Mandante."' and
                            Id_Personal = '".$Id_Personal."' and
                            year(fecha) = year(".$Periodo.") and
                            month(fecha) = month(".$Periodo.") and
                            tipo_cierre = '1'";
            $Evaluations = $db -> select($Query);
            if(count($Evaluations) > 0){
                $ToReturn = true;
            }
            return $ToReturn;
        }
        function getCierres($Month){
            $db = new Db();
            $PersonalClass = new Personal();
            $PersonalClass->Username = $this->User;
            $Id_Personal = $PersonalClass->getPersonalIDFromUsername();
            $CierresArray = array();
            $Cont = 0;
            $Desde = date('Ym01',strtotime($Month));
            $Hasta = date('Ymt',strtotime($Desde));
            $SqlCierre = "select
                            *
                        from cierre_evaluaciones
                        where
                            Id_Mandante = '".$this->Id_Mandante."' and
                            Id_Usuario = '".$this->Id_Usuario."' and
                            Id_Personal = '".$Id_Personal."' and
                            fecha BETWEEN '".$Desde."' and '".$Hasta."' ";
		    $Cierres = $db -> select($SqlCierre);
            foreach($Cierres as $Cierre){
                $this->Id_Grabacion = $Cierre['id'];
                $CierreArrayTmp = array();
                $Nota = number_format($this->getNotaFromCierre($Cierre['id']),2);
                $Perfil = $this->getPerfilEjecutivoByNota($Nota);
                $CierreArrayTmp["NotaPeriodo"] = $Nota;
                $CierreArrayTmp["PerfilEjecutivo"] = $Perfil["nombre"];
                $CierreArrayTmp["TipoCierre"] = $Cierre["tipo_cierre"];
                $CierreArrayTmp["Date"] = $Cierre["fecha"];
                $CierreArrayTmp["Visualizar"] = $Cierre["id"];
                $CierreArrayTmp["Imprimir"] = $Cierre["id"];
                $CierresArray[$Cont] = $CierreArrayTmp;
                $Cont++;
            }
            return $CierresArray;
        }
        function getCierre(){
            $db = new Db();
            $SqlCierre = "select * from cierre_evaluaciones where id = '".$this->Id_Cierre."'";
		    $Cierres = $db -> select($SqlCierre);
            return $Cierres;
        }
        function getEvaluationDetailsCierre($Evaluations){
            $db = new Db();
            $EvaluationsArray = array();
            $Cont = 0;
            $SqlEvaluation = "select
                                    evaluaciones.id,
                                    grabacion_2.Nombre_Grabacion as Grabacion,
                                    AVG(respuesta_opciones_afirmaciones_calidad.valor) as Nota
                                from evaluaciones
                                    inner join respuesta_opciones_afirmaciones_calidad on respuesta_opciones_afirmaciones_calidad.Id_Evaluacion = evaluaciones.id
                                    inner join grabacion_2 on grabacion_2.id = evaluaciones.Id_Grabacion
                                where
                                    evaluaciones.id in(".$Evaluations.")
                                group by
                                    evaluaciones.id
                                order by 
                                    evaluaciones.id,
                                    grabacion_2.Nombre_Grabacion";
		    $Evaluations = $db -> select($SqlEvaluation);
            foreach($Evaluations as $Evaluation){
                $EvaluationArray = array();
                $EvaluationArray['Nombre_Grabacion'] = $Evaluation["Grabacion"];
                $EvaluationArray['Grabacion'] = $this->dir.$this->getRutaGrabaciones($Evaluation["Grabacion"])."/".$Evaluation["Grabacion"];//$this->dir.$Evaluation["Grabacion"];
                $EvaluationArray['Nota'] = number_format($Evaluation["Nota"], 2, '.', '');
                $EvaluationsArray[$Cont] = $EvaluationArray;
                $Cont++;
            }
            return $EvaluationsArray;
        }
        function getGeneralGraphDataByUserType($UserType,$Mandante,$Ejecutivo,$Type){
            $Headers = "";
            $Columns = "";
            $RestaMes = "";
            $CantXAxis = 0;
            switch($Type){
                case 'Mes':
                    $Headers = "WEEK(evaluaciones.Fecha_Evaluacion) as Week";
                    $Columns = "WEEK(evaluaciones.Fecha_Evaluacion)";
                    $RestaMes = "0";
                    $CantXAxis = 4;
                break;
                case 'Historico':
                    $Headers = "year(evaluaciones.Fecha_Evaluacion) as Year, MONTH(evaluaciones.Fecha_Evaluacion) as Month";
                    $Columns = "year(evaluaciones.Fecha_Evaluacion), MONTH(evaluaciones.Fecha_Evaluacion)";
                    $RestaMes = "6";
                    $CantXAxis = 6;
                break;
            }
            $WhereSoloActivos = $Ejecutivo == "" ? " and Personal.Activo = '1' " : "";
            $WhereMandante = $Mandante != "" ? " and mandante_cedente.Id_Mandante='".$Mandante."'" : "";
            $WhereEjecutivo = $Ejecutivo != "" ? " and Personal.Id_Personal = '".$Ejecutivo."'" : "";
            $ByUser = "";
            switch($UserType){
                case '1':
                    //Calidad Sistema
                    $ByUser = "byCalidadSystem";
                break;
                case '2':
                    //Calidad Mandante
                    $ByUser = "byCalidadMandante";
                break;
                case '3':
                    //Ejecutivo Sistema
                    $ByUser = "byEjecutivoSystem";
                break;
                case '4':
                    //Ejecutivo Mandantte
                    $ByUser = "byEjecutivoMandante";
                break;
                case '5':
                break;
                case '6':
                break;
            }
            $db = new Db();
            $DateArray = $this->getDateFromServer();
            $Now = $DateArray["date"];
            $Now = new DateTime($Now);
            $Now->modify('last day of this month');
            $Now = $Now->format('Ymd');
            $SixMonthsAgo = strtotime ( '-'.$RestaMes.' months' , strtotime ( $Now ) ) ;
            $SixMonthsAgo = date ( 'Ym01' , $SixMonthsAgo );
            $Array = array();
            $Cont = 0;
            $Month = 1;
            $Inicio = date("Ymd",strtotime ('+1 months',strtotime($SixMonthsAgo)));
            $fechainicial = new DateTime($Inicio);
            $fechafinal = new DateTime($Now);
            $diferencia = $fechainicial->diff($fechafinal);
            $CantMeses = ( $diferencia->y * 12 ) + $diferencia->m;
            $Meses = array();
            $MesActual = date("Ym01",strtotime($Inicio));
            for($i=1;$i<=$CantMeses;$i++){
                $DataArray = array();
                $DataArray[0] = $Month;
                $DataArray[1] = 0;
                //$DataArray[2] = date("Ym",strtotime($MesActual));
                //$Array[$Cont] = $DataArray;
                //array_push($Meses,$DataArray);
                $Meses[date("Ym",strtotime($MesActual))] = $DataArray;
                $Month++;
                $MesActual = strtotime ( '+1 months' , strtotime ( $MesActual ) ) ;
                $MesActual = date ( 'Ym01' , $MesActual );
            }
            $SqlEvaluation = "select
                                    ".$Headers.", ROUND(AVG(valor),2) as Nota
                                from evaluaciones
                                    INNER JOIN respuesta_opciones_afirmaciones_calidad on respuesta_opciones_afirmaciones_calidad.Id_Evaluacion = evaluaciones.id
                                    INNER JOIN mandante_cedente on mandante_cedente.Id_Cedente = evaluaciones.Id_Cedente
                                    INNER JOIN Personal on Personal.Id_Personal = evaluaciones.Id_Personal
                                where
                                    Fecha_Evaluacion BETWEEN '".$SixMonthsAgo."' and '".$Now."' and
                                    ".$ByUser." = 1 ".$WhereEjecutivo." ".$WhereMandante." ".$WhereSoloActivos."
                                GROUP by ".$Columns."
                                ORDER BY ".$Columns;
		    /*if($UserType == "1"){
                echo $SqlEvaluation;
            }*/
            $Evaluations = $db -> select($SqlEvaluation);
            if(count($Evaluations) > 0){
                $Cant = count($Evaluations);
                $CantEmpty =0;
                switch($Type){
                    case 'Mes':
                    break;
                    case 'Historico':
                        $CantEmpty = 6 - $Cant;
                        $haveEvaluationThisMonth = $this->haveEvaluationThisMonth($UserType,$Mandante,$Ejecutivo);
                        if(!$haveEvaluationThisMonth){
                            $CantEmpty--;
                        }
                    break;
                }
                if($UserType == "2"){
                    //echo $CantEmpty;
                }
                if($CantEmpty > 0){
                    for($i=1;$i<=$CantEmpty;$i++){
                        $DataArray = array();
                        $DataArray[0] = $Month;
                        $DataArray[1] = 0;
                        $Array[$Cont] = $DataArray;
                        $Month++;
                        $Cont++;
                    }
                }
                foreach($Evaluations as $Evaluation){
                    $MonthDB = strlen($Evaluation["Month"]) > 1 ? $Evaluation["Month"] : "0".$Evaluation["Month"];
                    $YearDB = $Evaluation["Year"];
                    $Meses[$YearDB.$MonthDB][1] = $Evaluation["Nota"];
                    if($UserType == "2"){
                        //echo $Month . " - ". $CantEmpty;
                        //print_r($Meses[$Month - 1]);
                        //$Meses[$Month - 1][1] = $Evaluation["Nota"];
                    }
                    $DataArray = array();
                    $DataArray[0] = $Month;
                    $DataArray[1] = $Evaluation["Nota"];
                    $Array[$Cont] = $DataArray;
                    $Month++;
                    $Cont++;
                }
                //print_r($Meses);
                if(!$haveEvaluationThisMonth){
                    $DataArray = array();
                    $DataArray[0] = $Month;
                    $DataArray[1] = 0;
                    $Array[$Cont] = $DataArray;
                    $Month++;
                    $Cont++;
                }
            }else{
                /*$SqlEvaluation = "select
                                    ".$Headers.", ROUND(AVG(valor),2) as Nota
                                from evaluaciones
                                    INNER JOIN respuesta_opciones_afirmaciones_calidad on respuesta_opciones_afirmaciones_calidad.Id_Evaluacion = evaluaciones.id
                                    INNER JOIN mandante_cedente on mandante_cedente.Id_Cedente = evaluaciones.Id_Cedente
                                where
                                    YEAR(Fecha_Evaluacion) = YEAR(NOW()) and
                                    MONTH(Fecha_Evaluacion) = MONTH(NOW()) and
                                    ".$ByUser." = 1 ".$WhereEjecutivo." ".$WhereMandante."
                                GROUP by ".$Columns."
                                ORDER BY ".$Columns;
                $Evaluations = $db -> select($SqlEvaluation);*/
                for($i=1;$i<=$CantXAxis;$i++){
                    $DataArray = array();
                    $DataArray[0] = $Month;
                    $DataArray[1] = 0;
                    $Array[$Cont] = $DataArray;
                    if($i < $CantEmpty){
                        $Month++;
                        $Cont++;
                    }
                }
                /*foreach($Evaluations as $Evaluation){
                    $DataArray = array();
                    $DataArray[0] = $Month;
                    $DataArray[1] = $Evaluation["Nota"];
                    $Array[$Cont] = $DataArray;
                    $Cont++;
                }*/
            }
            //return $Array;
            
            
            $MesesTmp = array();
            foreach($Meses as $Mes){
                array_push($MesesTmp,$Mes);
            }
            /*if($UserType == "1"){
                print_r($Meses);
                print_r($MesesTmp);
            }*/
            return $MesesTmp;
        }
        function getGeneralByEvaluationGraphDataByUserType($UserType,$Mandante,$Ejecutivo){
            
            $WhereMandante = $Mandante != "" ? " and mandante_cedente.Id_Mandante='".$Mandante."'" : "";
            $WhereEjecutivo = $Ejecutivo != "" ? " and Personal.Id_Personal = '".$Ejecutivo."'" : "";

            $WhereSoloActivos = $Ejecutivo == "" ? " and Personal.Activo = '1' " : "";

            $ByUser = "";
            $UserTypeName = "";
            switch($UserType){
                case '1':
                    //Calidad Sistema
                    $ByUser = "byCalidadSystem";
                    $UserTypeName = "calidad";
                break;
                case '2':
                    //Calidad Mandante
                    $ByUser = "byCalidadMandante";
                    $UserTypeName = "empresa";
                break;
                case '3':
                    //Ejecutivo Sistema
                    $ByUser = "byEjecutivoSystem";
                    $UserTypeName = "ejecutivo";
                break;
                case '4':
                    //Ejecutivo Mandantte
                    $ByUser = "byEjecutivoMandante";
                    $UserTypeName = "";
                break;
                case '5':
                break;
                case '6':
                break;
            }
            $db = new Db();
            $DateArray = $this->getDateFromServer();
            $Now = $DateArray["date"];
            $Now = new DateTime($Now);
            $Now->modify('last day of this month');
            $Now = $Now->format('Ymd');
            $SixMonthsAgo = strtotime ( '-6 months' , strtotime ( $Now ) ) ;
            $SixMonthsAgo = date ( 'Ymd' , $SixMonthsAgo );
            $Array = array();
            $Cont = 0;
            $Month = 1;
            $SqlEvaluation = "select
                                    year(evaluaciones.Fecha_Evaluacion) as Year, MONTH(evaluaciones.Fecha_Evaluacion) as Month, ROUND(AVG(valor),2) as Nota, competencias_calidad.nombre as Resumen
                                from evaluaciones
                                    INNER JOIN respuesta_opciones_afirmaciones_calidad on respuesta_opciones_afirmaciones_calidad.Id_Evaluacion = evaluaciones.id
                                    INNER JOIN afirmaciones_dimensiones_competencias_calidad on afirmaciones_dimensiones_competencias_calidad.id = respuesta_opciones_afirmaciones_calidad.id_afirmacion
                                    INNER JOIN dimensiones_competencias_calidad on dimensiones_competencias_calidad.id = afirmaciones_dimensiones_competencias_calidad.id_dimension
                                    INNER JOIN competencias_calidad on competencias_calidad.id = dimensiones_competencias_calidad.id_competencia
                                    INNER JOIN mandante_cedente on mandante_cedente.Id_Cedente = evaluaciones.Id_Cedente
                                    INNER JOIN Personal on Personal.Id_Personal = evaluaciones.Id_Personal
                                where
                                    YEAR(Fecha_Evaluacion) = YEAR((select MAX(Fecha_Evaluacion) from evaluaciones where 1=1 ".$WhereEjecutivo." ".$WhereMandante.")) and
                                    MONTH(Fecha_Evaluacion) = MONTH((select MAX(Fecha_Evaluacion) from evaluaciones where 1=1 ".$WhereEjecutivo." ".$WhereMandante.")) and
                                    ".$ByUser." = 1 ".$WhereEjecutivo." ".$WhereMandante." ".$WhereSoloActivos."
                                GROUP by year(evaluaciones.Fecha_Evaluacion), MONTH(evaluaciones.Fecha_Evaluacion), competencias_calidad.nombre
                                ORDER BY year(evaluaciones.Fecha_Evaluacion) ASC, MONTH(evaluaciones.Fecha_Evaluacion) ASC, competencias_calidad.nombre ASC";
		    $Evaluations = $db -> select($SqlEvaluation);
            if(count($Evaluations) > 0){
                foreach($Evaluations as $Evaluation){
                    $DataArray = array();
                    $DataArray["Evaluacion"] = utf8_encode($Evaluation["Resumen"]);
                    $DataArray["Nota"] = $Evaluation["Nota"];
                    $DataArray["UserTypeName"] = $UserTypeName;
                    $Array[$Cont] = $DataArray;
                    $Month++;
                    $Cont++;
                }
            }else{
                $SqlEvaluation = "select
                                    year(evaluaciones.Fecha_Evaluacion) as Year, MONTH(evaluaciones.Fecha_Evaluacion) as Month, ROUND(AVG(valor),2) as Nota, competencias_calidad.nombre as Resumen
                                from evaluaciones
                                    INNER JOIN respuesta_opciones_afirmaciones_calidad on respuesta_opciones_afirmaciones_calidad.Id_Evaluacion = evaluaciones.id
                                    INNER JOIN afirmaciones_dimensiones_competencias_calidad on afirmaciones_dimensiones_competencias_calidad.id = respuesta_opciones_afirmaciones_calidad.id_afirmacion
                                    INNER JOIN dimensiones_competencias_calidad on dimensiones_competencias_calidad.id = afirmaciones_dimensiones_competencias_calidad.id_dimension
                                    INNER JOIN competencias_calidad on competencias_calidad.id = dimensiones_competencias_calidad.id_competencia
                                    INNER JOIN mandante_cedente on mandante_cedente.Id_Cedente = evaluaciones.Id_Cedente
                                where
                                    YEAR(Fecha_Evaluacion) = YEAR((select MAX(Fecha_Evaluacion) from evaluaciones where 1=1 ".$WhereEjecutivo." ".$WhereMandante.")) and
                                    MONTH(Fecha_Evaluacion) = MONTH((select MAX(Fecha_Evaluacion) from evaluaciones where 1=1 ".$WhereEjecutivo." ".$WhereMandante.")) and
                                    ".$ByUser." = 1 ".$WhereEjecutivo." ".$WhereMandante."
                                GROUP by year(evaluaciones.Fecha_Evaluacion), MONTH(evaluaciones.Fecha_Evaluacion), competencias_calidad.nombre
                                ORDER BY year(evaluaciones.Fecha_Evaluacion) ASC, MONTH(evaluaciones.Fecha_Evaluacion) ASC, competencias_calidad.nombre ASC";
                //$Evaluations = $db -> select($SqlEvaluation);
                for($i=1;$i<=6;$i++){
                    $DataArray = array();
                    $DataArray["Evaluacion"] = "";
                    $DataArray["Nota"] = 0;
                    $DataArray["UserTypeName"] = $UserTypeName;
                    $Array[$Cont] = $DataArray;
                    $Month++;
                    $Cont++;
                }
            }
            return $Array;
        }
        function getByEvaluationGraphDataByUserType($UserType,$Mandante,$Ejecutivo,$Type){

            $Headers = "";
            $Columns = "";
            $RestaMes = "";
            $WhereNow = "";
            $CantXAxis = 0;
            switch($Type){
                case 'Mes':
                    $Headers = "WEEK(evaluaciones.Fecha_Evaluacion) as Week";
                    $Columns = "WEEK(evaluaciones.Fecha_Evaluacion)";
                    $RestaMes = "0";
                    $CantXAxis = 4;
                break;
                case 'Historico':
                    $Headers = "year(evaluaciones.Fecha_Evaluacion) as Year, MONTH(evaluaciones.Fecha_Evaluacion) as Month";
                    $Columns = "year(evaluaciones.Fecha_Evaluacion), MONTH(evaluaciones.Fecha_Evaluacion)";
                    $RestaMes = "6";
                    $CantXAxis = 6;
                break;
            }

            $WhereMandante = $Mandante != "" ? " and mandante_cedente.Id_Mandante='".$Mandante."'" : "";
            $WhereEjecutivo = $Ejecutivo != "" ? " and Personal.Id_Personal = '".$Ejecutivo."'" : "";

            $WhereSoloActivos = $Ejecutivo == "" ? " and Personal.Activo = '1' " : "";

            $ByUser = "";
            $UserTypeName = "";
            switch($UserType){
                case '1':
                    //Calidad Sistema
                    $ByUser = "byCalidadSystem";
                    $UserTypeName = "calidad";
                break;
                case '2':
                    //Calidad Mandante
                    $ByUser = "byCalidadMandante";
                    $UserTypeName = "empresa";
                break;
                case '3':
                    //Ejecutivo Sistema
                    $ByUser = "byEjecutivoSystem";
                    $UserTypeName = "ejecutivo";
                break;
                case '4':
                    //Ejecutivo Mandantte
                    $ByUser = "byEjecutivoMandante";
                    $UserTypeName = "";
                break;
                case '5':
                break;
                case '6':
                break;
            }
            $db = new Db();
            $DateArray = $this->getDateFromServer();
            $Now = $DateArray["date"];
            $Now = new DateTime($Now);
            $Now->modify('last day of this month');
            $Now = $Now->format('Ymd');
            $SixMonthsAgo = strtotime ( '-'.$RestaMes.' months' , strtotime ( $Now ) ) ;
            $SixMonthsAgo = date ( 'Ym01' , $SixMonthsAgo );
            $Array = array();
            $Cont = 0;
            $Month = 1;
            $SqlEvaluation = "select
                                    ".$Headers.", ROUND(AVG(valor),2) as Nota, competencias_calidad.nombre as Resumen
                                from evaluaciones
                                    INNER JOIN respuesta_opciones_afirmaciones_calidad on respuesta_opciones_afirmaciones_calidad.Id_Evaluacion = evaluaciones.id
                                    INNER JOIN afirmaciones_dimensiones_competencias_calidad on afirmaciones_dimensiones_competencias_calidad.id = respuesta_opciones_afirmaciones_calidad.id_afirmacion
                                    INNER JOIN dimensiones_competencias_calidad on dimensiones_competencias_calidad.id = afirmaciones_dimensiones_competencias_calidad.id_dimension
                                    INNER JOIN competencias_calidad on competencias_calidad.id = dimensiones_competencias_calidad.id_competencia
                                    INNER JOIN mandante_cedente on mandante_cedente.Id_Cedente = evaluaciones.Id_Cedente
                                    INNER JOIN Personal on Personal.Id_Personal = evaluaciones.Id_Personal
                                where
                                    Fecha_Evaluacion BETWEEN '".$SixMonthsAgo."' and '".$Now."' and
                                    ".$ByUser." = 1 ".$WhereEjecutivo." ".$WhereMandante." ".$WhereSoloActivos." 
                                GROUP by ".$Columns.", competencias_calidad.nombre
                                ORDER BY competencias_calidad.nombre ASC, ".$Columns;
            /*$SqlEvaluation = "select
                                    year(evaluaciones.Fecha_Evaluacion) as Year, MONTH(evaluaciones.Fecha_Evaluacion) as Month, ROUND(AVG(valor),2) as Nota, competencias_calidad.nombre as Resumen
                                from evaluaciones
                                    INNER JOIN respuesta_opciones_afirmaciones_calidad on respuesta_opciones_afirmaciones_calidad.Id_Evaluacion = evaluaciones.id
                                    INNER JOIN afirmaciones_dimensiones_competencias_calidad on afirmaciones_dimensiones_competencias_calidad.id = respuesta_opciones_afirmaciones_calidad.id_afirmacion
                                    INNER JOIN dimensiones_competencias_calidad on dimensiones_competencias_calidad.id = afirmaciones_dimensiones_competencias_calidad.id_dimension
                                    INNER JOIN competencias_calidad on competencias_calidad.id = dimensiones_competencias_calidad.id_competencia
                                    INNER JOIN mandante_cedente on mandante_cedente.Id_Cedente = evaluaciones.Id_Cedente
                                where
                                    Fecha_Evaluacion BETWEEN '".$SixMonthsAgo."' and '".$Now."' and
                                    ".$ByUser." = 1 ".$WhereEjecutivo." ".$WhereMandante."
                                GROUP by year(evaluaciones.Fecha_Evaluacion), MONTH(evaluaciones.Fecha_Evaluacion), competencias_calidad.nombre
                                ORDER BY competencias_calidad.nombre ASC, year(evaluaciones.Fecha_Evaluacion) DESC, MONTH(evaluaciones.Fecha_Evaluacion) DESC";*/
		    $Evaluations = $db -> select($SqlEvaluation);
            $EvaluationResumen = "";
            $originales = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿŔŕ';
            $modificadas = 'aaaaaaaceeeeiiiidnoooooouuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr';
            if(count($Evaluations) > 0){
                if($ByUser == "byCalidadSystem"){
                //echo "<br><br><br><br>";
                }
                //$EvaluationResumen = "";
                $Months = $this->MonthsIndex();
                
                foreach($Evaluations as $Evaluation){
                    if($EvaluationResumen != utf8_encode($Evaluation["Resumen"])){
                        $Month = 6;
                        $Cont = 0;
                        $EvaluationResumen = utf8_encode($Evaluation["Resumen"]);
                    }
                    $DataArray = array();
                    switch($Type){
                        case 'Mes':
                            $Month = $Cont + 1;//$Evaluation["Week"];
                            $YearDB = $Evaluation["Week"];
                        break;
                        case 'Historico':
                            $YearDB = $Evaluation["Year"];
                            $MonthDB = strlen($Evaluation["Month"]) == 2 ? $Evaluation["Month"] : "0".$Evaluation["Month"];
                            $Month = $Months[$YearDB."_".$MonthDB];
                        break;
                    }
                    $cadena = utf8_encode($Evaluation["Resumen"]);
                    $cadena = utf8_decode($cadena);
                    $cadena = strtr($cadena, utf8_decode($originales), $modificadas);
                    $cadena = utf8_encode($cadena);
                    $DataArray[0] = $Month;
                    $DataArray[1] = $Evaluation["Nota"];
                    //$DataArray[2] = $YearDB;
                    if($ByUser == "byCalidadSystem"){
                    //print_r($DataArray);
                    }
                    
                    $Array[$cadena][$Cont] = $DataArray;
                    $Month--;
                    $Cont++;
                }
            }else{
                $Evaluations = $this->getEvaluationTemplateByPerfil('1');
                
                foreach($Evaluations as $Evaluation){
                    for($i=1;$i<=$CantXAxis;$i++){
                        if($EvaluationResumen != $Evaluation["Nombre"]){
                            $Month = 1;
                            $Cont = 0;
                            $EvaluationResumen = $Evaluation["Nombre"];
                        }
                        $cadena = $Evaluation["Nombre"];
                        $cadena = utf8_decode($cadena);
                        $cadena = strtr($cadena, utf8_decode($originales), $modificadas);
                        $cadena = utf8_encode($cadena);
                        $DataArray = array();
                        $DataArray[0] = $Month;
                        $DataArray[1] = 0;
                        $Array[$cadena][$Cont] = $DataArray;
                        $Month++;
                        $Cont++;
                    }
                    
                }
            }
            if($ByUser == "byCalidadSystem"){
            /*print_r($DataArray);
            echo "<br><br><br><br>";*/
            }
            return $Array;
        }
        function getDateFromServer($Separator = ""){
            $db = new Db();
            $SqlDate = "select DATE_FORMAT(NOW(),'%Y".$Separator."%m".$Separator."%d') as date, DATE_FORMAT(NOW(),'%T:%f') as hour";
		    $Dates = $db -> select($SqlDate);
            return $Dates[0];
        }
        function haveEvaluationThisMonth($UserType,$Mandante,$Ejecutivo){
            $WhereMandante = $Mandante != "" ? " and mandante_cedente.Id_Mandante='".$Mandante."'" : "";
            $WhereEjecutivo = $Ejecutivo != "" ? " and Id_Personal = '".$Ejecutivo."'" : "";

            $ByUser = "";
            $UserTypeName = "";
            switch($UserType){
                case '1':
                    //Calidad Sistema
                    $ByUser = "byCalidadSystem";
                    $UserTypeName = "calidad";
                break;
                case '2':
                    //Calidad Mandante
                    $ByUser = "byCalidadMandante";
                    $UserTypeName = "empresa";
                break;
                case '3':
                    //Ejecutivo Sistema
                    $ByUser = "byEjecutivoSystem";
                    $UserTypeName = "ejecutivo";
                break;
                case '4':
                    //Ejecutivo Mandantte
                    $ByUser = "byEjecutivoMandante";
                    $UserTypeName = "";
                break;
                case '5':
                break;
                case '6':
                break;
            }
            $db = new Db();
            $SqlEvaluations = "select * from evaluaciones 
                                    INNER JOIN respuesta_opciones_afirmaciones_calidad on respuesta_opciones_afirmaciones_calidad.Id_Evaluacion = evaluaciones.id
                                    INNER JOIN mandante_cedente on mandante_cedente.Id_Cedente = evaluaciones.Id_Cedente
                                where year(evaluaciones.Fecha_Evaluacion) = year(NOW()) and month(evaluaciones.Fecha_Evaluacion) = month(NOW()) and ".$ByUser." = 1 ".$WhereEjecutivo." ".$WhereMandante." ";
            if($ByUser == "byEjecutivoSystem"){
                 $SqlEvaluations;
            }
            $Evaluations = $db->select($SqlEvaluations);
            $ToReturn = false;
            if(count($Evaluations) > 0){
                $ToReturn = true;
            }
            return $ToReturn;
        }
        function getRankingData($Mandante,$Cedente,$Periodo){
            $WhereCedente = $Cedente != "" ? " and mandante_cedente.Id_Cedente='".$Cedente."'" : "";
            if($Periodo != ""){
                $Desde = date('Ym01',strtotime($Periodo));
                $Hasta = date('Ymt',strtotime($Desde));
                $WherePeriodo = " and evaluaciones.Fecha_Evaluacion between '".$Desde."' and '".$Hasta."' ";
            }else{
                $WherePeriodo = "";
            }
            $db = new DB();
            $SqlRanking = "SELECT
                                Personal.nombre as Nombre,
                                round(avg(respuesta_opciones_afirmaciones_calidad.valor),2) as Nota
                            from
                                evaluaciones
                                    inner join Personal on Personal.Id_Personal = evaluaciones.Id_Personal
                                    inner join respuesta_opciones_afirmaciones_calidad on respuesta_opciones_afirmaciones_calidad.Id_Evaluacion = evaluaciones.id
                                    inner join mandante_cedente on mandante_cedente.Id_Cedente = evaluaciones.Id_Cedente
                            where
                                mandante_cedente.Id_Mandante = '".$Mandante."' and 
                                Personal.Activo = '1'
                                ".$WhereCedente."
                                ".$WherePeriodo."
                            group by
                                Personal.Id_Personal
                            order by
                                Personal.Nombre";
            $Ranking = $db->select($SqlRanking);
            return $Ranking;
        }
        function MonthsIndex(){
            $DateArray = $this->getDateFromServer();
            $Now = $DateArray["date"];
            $Now = new DateTime($Now);
            $Now->modify('first day of this month');
            $Now = $Now->format('Ymd');
            $SixMonthsAgo_strtotime = strtotime ( '-6 months' , strtotime ( $Now ) ) ;
            $SixMonthsAgo = date ( 'Ymd' , $SixMonthsAgo_strtotime );
            $Month = date ( 'm' , $SixMonthsAgo );
            $Months = array();
            for($i=1;$i<=6;$i++){
                $SixMonthsAgo_strtotime = strtotime ( '+1 months' , strtotime ( $SixMonthsAgo ) ) ;
                $Year = date ( 'Y' , $SixMonthsAgo_strtotime );
                $Month = date ( 'm' , $SixMonthsAgo_strtotime );
                $Months[$Year."_".$Month] = $i;
                $SixMonthsAgo = date ( 'Ymd' , $SixMonthsAgo_strtotime );
            }
            return $Months;
        }
        function getCierresByMonthsAndYears(){
            $db = new DB();
            $ToReturn = array();
            $Months = array();
            $Months[1] = "Enero";
            $Months[2] = "Febrero";
            $Months[3] = "Marzo";
            $Months[4] = "Abril";
            $Months[5] = "Mayo";
            $Months[6] = "Junio";
            $Months[7] = "Julio";
            $Months[8] = "Agosto";
            $Months[9] = "Septiembre";
            $Months[10] = "Octubre";
            $Months[11] = "Noviembre";
            $Months[12] = "Diciembre";

            $SqlCierres = "select
                                month(fecha) as Month,
                                year(fecha) as Year
                            from
                                cierre_evaluaciones
                            where
                                Id_Usuario='".$this->Id_Usuario."' and 
                                Id_Mandante='".$_SESSION['mandante']."'
                            group by
                                month(fecha),
                                year(fecha)
                            order by
                                fecha DESC";
            $Cierres = $db->select($SqlCierres);
            foreach($Cierres as $Cierre){
                $ArrayTmp = array();
                $ArrayTmp["Month"] = $Cierre["Month"];
                $ArrayTmp["MonthText"] = $Months[$Cierre["Month"]];
                $ArrayTmp["Year"] = $Cierre["Year"];
                array_push($ToReturn,$ArrayTmp);
            }
            return $ToReturn;
        }
        function getEvaluacionesByMonthsAndYears(){
            $db = new DB();
            $ToReturn = array();
            $Months = array();
            $Months[1] = "Enero";
            $Months[2] = "Febrero";
            $Months[3] = "Marzo";
            $Months[4] = "Abril";
            $Months[5] = "Mayo";
            $Months[6] = "Junio";
            $Months[7] = "Julio";
            $Months[8] = "Agosto";
            $Months[9] = "Septiembre";
            $Months[10] = "Octubre";
            $Months[11] = "Noviembre";
            $Months[12] = "Diciembre";

            $SqlEvaluaciones = "select
                                    month(Fecha_Evaluacion) as Month,
                                    year(Fecha_Evaluacion) as Year
                                from
                                    evaluaciones
                                where
                                    Id_Personal='".$this->Id_Personal."'
                                group by
                                    month(Fecha_Evaluacion),
                                    year(Fecha_Evaluacion)
                                order by
                                    Fecha_Evaluacion DESC";
            $Evaluaciones = $db->select($SqlEvaluaciones);
            foreach($Evaluaciones as $Evaluacion){
                $ArrayTmp = array();
                $ArrayTmp["Month"] = $Evaluacion["Month"];
                $ArrayTmp["MonthText"] = $Months[$Evaluacion["Month"]];
                $ArrayTmp["Year"] = $Evaluacion["Year"];
                array_push($ToReturn,$ArrayTmp);
            }
            return $ToReturn;
        }
        function getEvaluacionesByMonthsAndYearsAndMandante(){
            $db = new DB();
            $ToReturn = array();
            $Months = array();
            $Months[1] = "Enero";
            $Months[2] = "Febrero";
            $Months[3] = "Marzo";
            $Months[4] = "Abril";
            $Months[5] = "Mayo";
            $Months[6] = "Junio";
            $Months[7] = "Julio";
            $Months[8] = "Agosto";
            $Months[9] = "Septiembre";
            $Months[10] = "Octubre";
            $Months[11] = "Noviembre";
            $Months[12] = "Diciembre";

            $SqlEvaluaciones = "select
                                    month(Fecha_Evaluacion) as Month,
                                    year(Fecha_Evaluacion) as Year
                                from
                                    evaluaciones
                                        inner join mandante_cedente on mandante_cedente.Id_Cedente = evaluaciones.Id_Cedente
                                where
                                    mandante_cedente.Id_Mandante='".$_SESSION["mandante"]."'
                                group by
                                    month(Fecha_Evaluacion),
                                    year(Fecha_Evaluacion)
                                order by
                                    Fecha_Evaluacion DESC";
            $Evaluaciones = $db->select($SqlEvaluaciones);
            foreach($Evaluaciones as $Evaluacion){
                $ArrayTmp = array();
                $ArrayTmp["Month"] = $Evaluacion["Month"];
                $ArrayTmp["MonthText"] = $Months[$Evaluacion["Month"]];
                $ArrayTmp["Year"] = $Evaluacion["Year"];
                array_push($ToReturn,$ArrayTmp);
            }
            return $ToReturn;
        }
        function getCierreEjecutivos($Month){
            $ToReturn = array();
            $db = new DB();
            $Desde = date('Ym01',strtotime($Month));
            $Hasta = date('Ymt',strtotime($Desde));
            $SqlCierres = "select
                                cierre_evaluaciones.*,
                                pexterno.Nombre as Ejecutivo,
                                (select cierre_evaluaciones.Aspectos_Fortalecer from cierre_evaluaciones inner join Usuarios on Usuarios.id = cierre_evaluaciones.Id_Usuario inner join Personal p on p.Id_Personal = cierre_evaluaciones.Id_Personal where cierre_evaluaciones.Id_Mandante='".$_SESSION['mandante']."' and Usuarios.nivel='6' and Usuarios.mandante='' and p.Id_Personal = pexterno.Id_Personal and fecha between '".$Desde."' and '".$Hasta."' order by p.Nombre) as AFCalidad,
                                (select cierre_evaluaciones.Aspectos_Corregir from cierre_evaluaciones inner join Usuarios on Usuarios.id = cierre_evaluaciones.Id_Usuario inner join Personal p on p.Id_Personal = cierre_evaluaciones.Id_Personal where cierre_evaluaciones.Id_Mandante='".$_SESSION['mandante']."' and Usuarios.nivel='6' and Usuarios.mandante='' and p.Id_Personal = pexterno.Id_Personal and fecha between '".$Desde."' and '".$Hasta."' order by p.Nombre) as ACCalidad,
                                (select cierre_evaluaciones.Compromiso_Ejecutivo from cierre_evaluaciones inner join Usuarios on Usuarios.id = cierre_evaluaciones.Id_Usuario inner join Personal p on p.Id_Personal = cierre_evaluaciones.Id_Personal where cierre_evaluaciones.Id_Mandante='".$_SESSION['mandante']."' and Usuarios.nivel='6' and Usuarios.mandante='' and p.Id_Personal = pexterno.Id_Personal and fecha between '".$Desde."' and '".$Hasta."' order by p.Nombre) as CECalidad
                            from
                                cierre_evaluaciones
                                inner join Usuarios on Usuarios.id = cierre_evaluaciones.Id_Usuario
                                inner join Personal pexterno on pexterno.Id_Personal = cierre_evaluaciones.Id_Personal
                            where
                                cierre_evaluaciones.Id_Mandante='".$_SESSION['mandante']."' and
                                Usuarios.nivel='4' and
                                fecha between '".$Desde."' and '".$Hasta."'
                            order by
                                pexterno.Nombre";
            $Cierres = $db->select($SqlCierres);
            $Cont = 1;
            foreach($Cierres as $Cierre){
                $ArrayTmp = array();
                $ArrayTmp["Number"] = $Cont;
                $ArrayTmp["Ejecutivo"] = $Cierre["Ejecutivo"];
                $ArrayTmp["AspectosFortalecer"] = "<p style='text-align:center;font-weight:bold;width:100%'>Ejecutivo</p>".$Cierre["Aspectos_Fortalecer"]."<p style='text-align:center;font-weight:bold;width:100%'>Calidad</p>".$Cierre["AFCalidad"];
                $ArrayTmp["AspectosCorregir"] = "<p style='text-align:center;font-weight:bold;width:100%'>Ejecutivo</p>".$Cierre["Aspectos_Corregir"]."<p style='text-align:center;font-weight:bold;width:100%'>Calidad</p>".$Cierre["ACCalidad"];
                $ArrayTmp["CompromisoEjecutivo"] = "<p style='text-align:center;font-weight:bold;width:100%'>Ejecutivo</p>".$Cierre["Compromiso_Ejecutivo"]."<p style='text-align:center;font-weight:bold;width:100%'>Calidad</p>".$Cierre["CECalidad"];
                $ArrayTmp["Accion"] = $Cierre["Id_Personal"];
                $Cont++;
                array_push($ToReturn,$ArrayTmp);
            }
            return $ToReturn;
        }
/* function getCompetencias(){
    $ToReturn = array();
    $db = new DB();
    $SqlCompetencias = "select id,resumen from mantenedor_evaluaciones order by resumen";
    $Competencias = $db->select($SqlCompetencias);
    foreach($Competencias as $Competencia){
        $ArrayTmp = array();
        $ArrayTmp["id"] = $Competencia["id"];
        $ArrayTmp["Resumen"] = $Competencia["resumen"];
        array_push($ToReturn,$ArrayTmp);
    }
    return $ToReturn;
} */
        function getModulos($Competencia){
            $ToReturn = array();
            $db = new DB();
            $SqlModulos = "select id,nombre from modulos_plan_accion where id_competencia='".$Competencia."' order by nombre";
            $Modulos = $db->select($SqlModulos);
            foreach($Modulos as $Modulo){
                $ArrayTmp = array();
                $ArrayTmp["id"] = $Modulo["id"];
                $ArrayTmp["Nombre"] = $Modulo["nombre"];
                array_push($ToReturn,$ArrayTmp);
            }
            return $ToReturn;
        }
        function getTopicos($Modulo){
            $ToReturn = array();
            $db = new DB();
            $SqlTopicos = "select id,nombre from topicos_modulos_plan_accion where id_modulo='".$Modulo."' order by nombre";
            $Topicos = $db->select($SqlTopicos);
            foreach($Topicos as $Topico){
                $ArrayTmp = array();
                $ArrayTmp["id"] = $Topico["id"];
                $ArrayTmp["Nombre"] = $Topico["nombre"];
                array_push($ToReturn,$ArrayTmp);
            }
            return $ToReturn;
        }
        function addPlan($Competencia,$Modulo,$Topico,$Ejecutivo,$Month){
            $ToReturn = array();
            $db = new DB();
            $ActualMonth = date('m');
            $PeriodoMonth = date('m',strtotime($Month));
            $ActualYear = date('y');
            $PeriodoYear = date('y',strtotime($Month));
            $Date = "'".date('Y-m-t',strtotime($Month))."'";
            if($ActualYear == $PeriodoYear){
                if($ActualMonth == $PeriodoMonth){
                    $Date = "NOW()";
                }
            }
            $SqlInsert = "insert into plan_accion_ejecutivo (Id_Usuario,id_competencia,id_modulo,id_topico,Id_Personal,fecha,Id_Mandante) values('".$this->Id_Usuario."','".$Competencia."','".$Modulo."','".$Topico."','".$Ejecutivo."',".$Date.",'".$this->Id_Mandante."')";
            $Insert = $db->query($SqlInsert);
            if($Insert){
                $ToReturn['result'] = true;
                $ToReturn['id'] = $this->getLastPlanInserted($Ejecutivo);
            }else{
                $ToReturn['result'] = false;
            }
            return $ToReturn;
        }
        function getLastPlanInserted($Ejecutivo){
            $ToReturn = "";
            $db = new DB();
            $SqlLast = "select id from plan_accion_ejecutivo where Id_Mandante='".$this->Id_Mandante."' and Id_Personal='".$Ejecutivo."' order by id desc LIMIT 1";
            $Last = $db->select($SqlLast);
            $ToReturn = $Last[0]["id"];
            return $ToReturn;
        }
        function getPlans($Ejecutivo,$Month){
            $ToReturn = array();
            $db = new DB();
            $Desde = date('Ym01',strtotime($Month));
            $Hasta = date('Ymt',strtotime($Desde));
            $SqlPlans = "select
                            mantenedor_evaluaciones.resumen as Competencia,
                            modulos_plan_accion.nombre as Modulo,
                            topicos_modulos_plan_accion.nombre as Topico,
                            plan_accion_ejecutivo.id
                        from
                            plan_accion_ejecutivo
                            inner join mantenedor_evaluaciones on mantenedor_evaluaciones.id = plan_accion_ejecutivo.id_competencia
                            inner join modulos_plan_accion on modulos_plan_accion.id = plan_accion_ejecutivo.id_modulo
                            inner join topicos_modulos_plan_accion on topicos_modulos_plan_accion.id = plan_accion_ejecutivo.id_topico
                        where
                            plan_accion_ejecutivo.Id_Personal='".$Ejecutivo."' and
                            plan_accion_ejecutivo.Id_Mandante='".$this->Id_Mandante."' and
                            plan_accion_ejecutivo.fecha between '".$Desde."' and '".$Hasta."'
                        order by
                            mantenedor_evaluaciones.resumen,
                            modulos_plan_accion.nombre,
                            topicos_modulos_plan_accion.nombre";
            $Plans = $db->select($SqlPlans);
            foreach($Plans as $Plan){
                $ArrayTmp = array();
                $ArrayTmp["Competencia"] = utf8_encode($Plan["Competencia"]);
                $ArrayTmp["Modulo"] = utf8_encode($Plan["Modulo"]);
                $ArrayTmp["Topico"] = utf8_encode($Plan["Topico"]);
                $ArrayTmp["Accion"] = $Plan["id"];
                array_push($ToReturn,$ArrayTmp);
            }
            return $ToReturn;
        }
        function deletePlan($ID){
            $ToReturn = array();
            $ToReturn["result"] = false;
            $db = new DB();
            $SqlDelete = "delete from plan_accion_ejecutivo where id='".$ID."'";
            $Delete = $db->query($SqlDelete);
            if($Delete){
                $ToReturn["result"] = true;
            }
            $ToReturn["query"] = $SqlDelete;
            return $ToReturn;
        }
        function canAddPlan($Ejecutivo,$Competencia,$Modulo,$Topico,$Month){
            $ToReturn = array();
            $ToReturn["result"] = true;
            $db = new DB();
            $Desde = date('Ym01',strtotime($Month));
            $Hasta = date('Ymt',strtotime($Desde));
            $SqlPlan = "select * from plan_accion_ejecutivo where id_competencia='".$Competencia."' and id_modulo='".$Modulo."' and id_topico='".$Topico."' and Id_Personal='".$Ejecutivo."' and fecha between '".$Desde."' and '".$Hasta."'";
            $Plan = $db->select($SqlPlan);
            if(count($Plan) > 0){
                $ToReturn["result"] = false;
            }
            $ToReturn["query"] = $SqlPlan;
            return $ToReturn;
        }
        function getPeriodoCierreEjecutivos(){
            $db = new DB();
            $SqlPeriodos = "select
                                Month(cierre_evaluaciones.fecha) as Month, Year(cierre_evaluaciones.fecha) as Year
                            from
                                cierre_evaluaciones
                                inner join Usuarios on Usuarios.id = cierre_evaluaciones.Id_Usuario
                            where
                                cierre_evaluaciones.Id_Mandante='".$this->Id_Mandante."' and
                                Usuarios.nivel='4'
                            GROUP BY
                                Month(cierre_evaluaciones.fecha), Year(cierre_evaluaciones.fecha)
                            ORDER BY
                                fecha DESC";
            $Periodos = $db->select($SqlPeriodos);
            return $Periodos;
        }
        function cellsToMergeByColsRow($start = NULL, $end = NULL, $row = NULL){
            $merge = 'A1:A1';
            if($start && $end && $row){
                $start = PHPExcel_Cell::stringFromColumnIndex($start);
                $end = PHPExcel_Cell::stringFromColumnIndex($end);
                $merge = "$start{$row}:$end{$row}";
            }
            return $merge;
        }
        function DownloadInformeGeneral($Periodo){
            $ToReturn = "";
            $db = new DB();
            $Desde = date('Ym01',strtotime($Periodo));
            $Hasta = date('Ymt',strtotime($Desde));

            $ArrayEjecutivos = array();

            $SqlEjecutivos = "select
                                    Personal.Id_Personal,
                                    Personal.Nombre
                                from
                                    evaluaciones
                                        inner join Personal on Personal.Id_Personal = evaluaciones.Id_Personal
                                where
                                    ByCalidadSystem='1' and
                                    Fecha_Evaluacion between '".$Desde."' and '".$Hasta."' and
                                    Id_Cedente in (select Id_Cedente from mandante_cedente where Id_Mandante='".$this->Id_Mandante."')
                                group by
                                    Personal.Nombre
                                order by
                                    Personal.Nombre";
            $Ejecutivos = $db->select($SqlEjecutivos);
            foreach($Ejecutivos as $Ejecutivo){
                $ArrayTmpEjecutivos["Nombre"] = $Ejecutivo["Nombre"];
                $ArrayTmpEjecutivos["NotasCalidad"] = array();
                $ArrayTmpEjecutivos["NotasEjecutivo"] = array();
                $SqlNotasCompetenciasCalidad = "select
                                                    competencias_calidad.nombre as Competencia,
                                                    ROUND(AVG(respuesta_opciones_afirmaciones_calidad.valor),2) as Nota
                                                from
                                                    evaluaciones
                                                        INNER JOIN respuesta_opciones_afirmaciones_calidad on respuesta_opciones_afirmaciones_calidad.Id_Evaluacion = evaluaciones.id
                                                        INNER JOIN afirmaciones_dimensiones_competencias_calidad on afirmaciones_dimensiones_competencias_calidad.id = respuesta_opciones_afirmaciones_calidad.id_afirmacion
                                                        INNER JOIN dimensiones_competencias_calidad on dimensiones_competencias_calidad.id = afirmaciones_dimensiones_competencias_calidad.id_dimension
                                                        INNER JOIN competencias_calidad on competencias_calidad.id = dimensiones_competencias_calidad.id_competencia
                                                where
                                                    evaluaciones.ByCalidadSystem='1' and
                                                    evaluaciones.Id_Personal='".$Ejecutivo["Id_Personal"]."'
                                                group by
                                                    competencias_calidad.nombre
                                                order by
                                                    competencias_calidad.nombre";
                $SqlNotasCompetenciasEjecutivo = "select
                                                    competencias_calidad.nombre as Competencia,
                                                    ROUND(AVG(respuesta_opciones_afirmaciones_calidad.valor),2) as Nota
                                                from
                                                    evaluaciones
                                                        INNER JOIN respuesta_opciones_afirmaciones_calidad on respuesta_opciones_afirmaciones_calidad.Id_Evaluacion = evaluaciones.id
                                                        INNER JOIN afirmaciones_dimensiones_competencias_calidad on afirmaciones_dimensiones_competencias_calidad.id = respuesta_opciones_afirmaciones_calidad.id_afirmacion
                                                        INNER JOIN dimensiones_competencias_calidad on dimensiones_competencias_calidad.id = afirmaciones_dimensiones_competencias_calidad.id_dimension
                                                        INNER JOIN competencias_calidad on competencias_calidad.id = dimensiones_competencias_calidad.id_competencia
                                                where
                                                    evaluaciones.ByEjecutivoSystem='1' and
                                                    evaluaciones.Id_Personal='".$Ejecutivo["Id_Personal"]."'
                                                group by
                                                    competencias_calidad.nombre
                                                order by
                                                    competencias_calidad.nombre";
                $NotasCompetenciasCalidad = $db->select($SqlNotasCompetenciasCalidad);
                $NotasCompetenciasEjecutivo = $db->select($SqlNotasCompetenciasEjecutivo);
                foreach($NotasCompetenciasCalidad as $Nota){
                    $ArrayTmp = array();
                    $ArrayTmp["Competencia"] = utf8_encode($Nota["Competencia"]);
                    $ArrayTmp["Nota"] = number_format($Nota["Nota"],2);
                    array_push($ArrayTmpEjecutivos["NotasCalidad"],$ArrayTmp);
                }
                foreach($NotasCompetenciasEjecutivo as $Nota){
                    $ArrayTmp = array();
                    $ArrayTmp["Competencia"] = utf8_encode($Nota["Competencia"]);
                    $ArrayTmp["Nota"] = number_format($Nota["Nota"],2);
                    array_push($ArrayTmpEjecutivos["NotasEjecutivo"],$ArrayTmp);
                }
                array_push($ArrayEjecutivos,$ArrayTmpEjecutivos);
            }
            /*echo "<pre>";
            print_r($ArrayEjecutivos);
            echo "</pre>";*/

            $Competencias = $this->getEvaluationTemplateByPerfil('1');

            $fileName = "Prueba";
            $objPHPExcel = new PHPExcel();
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
            $objPHPExcel->getActiveSheet()->setTitle('Informe General');

            $style = array(
                'alignment' => array(
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                )
            );

            $columnLetter = PHPExcel_Cell::stringFromColumnIndex(0);


            $objPHPExcel->getActiveSheet()->getStyle('B1:M1')->getFont()->setBold(true);
            $objPHPExcel->getActiveSheet()->getStyle('B1:M1')->getFont()->setSize(11);

            //$objPHPExcel->setActiveSheetIndex(0)->mergeCells('B1:G1');
            $objPHPExcel->setActiveSheetIndex(0)->mergeCells($this->cellsToMergeByColsRow(1,count($Competencias) + 1,1));
            //$objPHPExcel->getActiveSheet()->getStyle('B1:G1')->applyFromArray($style);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(1,1)->applyFromArray($style);
            //$objPHPExcel->getActiveSheet()->getStyle('B1:G1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('00FF00');
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(1,1)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('00FF00');
            $objPHPExcel->
            setActiveSheetIndex($NextSheet)
                    ->setCellValueByColumnAndRow(1,1,"CALIDAD");
            
            //$objPHPExcel->setActiveSheetIndex(0)->mergeCells('H1:M1');
            $objPHPExcel->setActiveSheetIndex(0)->mergeCells($this->cellsToMergeByColsRow((count($Competencias)*2) - 1,((count($Competencias)*2) + count($Competencias) - 1),1));
            //$objPHPExcel->getActiveSheet()->getStyle('H1:M1')->applyFromArray($style);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow((count($Competencias)*2) - 1,1)->applyFromArray($style);
            //$objPHPExcel->getActiveSheet()->getStyle('H1:M1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('CCCCCC');
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow((count($Competencias)*2) - 1,1)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('CCCCCC');
            $objPHPExcel->
            setActiveSheetIndex($NextSheet)
                    ->setCellValueByColumnAndRow((count($Competencias)*2) - 1,1,"EJECUTIVO");
            
            $Col = 1;
            foreach($Competencias as $Competencia){
                $objPHPExcel->
                setActiveSheetIndex($NextSheet)
                        ->setCellValueByColumnAndRow($Col,2,$Competencia["Nombre"]);
                $Col++;
            }
            $objPHPExcel->
            setActiveSheetIndex($NextSheet)
                    ->setCellValueByColumnAndRow($Col,2,"TOTAL");
            
            $Col = (count($Competencias)*2) - 1;
            foreach($Competencias as $Competencia){
                $objPHPExcel->
                setActiveSheetIndex($NextSheet)
                        ->setCellValueByColumnAndRow($Col,2,$Competencia["Nombre"]);
                $Col++;
            }
            $objPHPExcel->
            setActiveSheetIndex($NextSheet)
                    ->setCellValueByColumnAndRow($Col,2,"TOTAL");

            $Row = 3;
            foreach($ArrayEjecutivos as $Ejecutivo){
                $Col = 0;
                $objPHPExcel->
                setActiveSheetIndex($NextSheet)
                        ->setCellValueByColumnAndRow($Col,$Row,$Ejecutivo["Nombre"]);
                $Col++;
                $Total = 0;
                foreach($Ejecutivo["NotasCalidad"] as $Nota){
                    $objPHPExcel->
                    setActiveSheetIndex($NextSheet)
                            ->setCellValueByColumnAndRow($Col,$Row,$Nota["Nota"]);
                    $Total += $Nota["Nota"];
                    $Col++;
                }
                $Total = number_format($Total / count($Ejecutivo["NotasCalidad"]),2);
                $objPHPExcel->
                setActiveSheetIndex($NextSheet)
                        ->setCellValueByColumnAndRow($Col,$Row,$Total);
                $Col++;
                $Total = 0;
                foreach($Ejecutivo["NotasEjecutivo"] as $Nota){
                    $objPHPExcel->
                    setActiveSheetIndex($NextSheet)
                            ->setCellValueByColumnAndRow($Col,$Row,$Nota["Nota"]);
                    $Total += $Nota["Nota"];
                    $Col++;
                }
                $Total = number_format($Total / count($Ejecutivo["NotasEjecutivo"]),2);
                $objPHPExcel->
                setActiveSheetIndex($NextSheet)
                        ->setCellValueByColumnAndRow($Col,$Row,$Total);
                $Col++;
                $Row++;
            }
            $Col = 1;
            for($i=1; $i<=2; $i++){
                foreach($Competencias as $Competencia){
                    $ColumnName = PHPExcel_Cell::stringFromColumnIndex($Col);
                    $Desde = $ColumnName."3";
                    $Hasta = $ColumnName."".($Row - 1);
                    $objPHPExcel->
                    setActiveSheetIndex($NextSheet)
                            ->setCellValue($ColumnName.$Row,"=AVERAGE(".$Desde.":".$Hasta.")");
                    $Col++;
                }
                $ColumnName = PHPExcel_Cell::stringFromColumnIndex($Col);
                $Desde = $ColumnName."3";
                $Hasta = $ColumnName."".($Row - 1);
                $objPHPExcel->
                setActiveSheetIndex($NextSheet)
                        ->setCellValue($ColumnName.$Row,"=AVERAGE(".$Desde.":".$Hasta.")");
                $Col++;
            }
            
            $NextSheet = 1;
            $objPHPExcel->createSheet($NextSheet);
            $objPHPExcel->setActiveSheetIndex($NextSheet);
            $objPHPExcel->getActiveSheet()->setTitle('Evolución Semanal');

            $Months = array();
            $Months["01"] = "Enero";
            $Months["02"] = "Febrero";
            $Months["03"] = "Marzo";
            $Months["04"] = "Abril";
            $Months["05"] = "Mayo";
            $Months["06"] = "Junio";
            $Months["07"] = "Julio";
            $Months["08"] = "Agosto";
            $Months["09"] = "Septiembre";
            $Months["10"] = "Octubre";
            $Months["11"] = "Noviembre";
            $Months["12"] = "Diciembre";

            $ResultNotes = $this->getNotesByWeekAndEjecutivo($Periodo);

            $Row = 2;
            $Col = 0;

            $objPHPExcel->
                setActiveSheetIndex($NextSheet)
                        ->setCellValueByColumnAndRow($Col,$Row,"Ejecutivo");
            $Col++;
            $ContMonths = 1;
            $style["borders"] = array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN));
            foreach($ResultNotes["Semanas"] as $idMes => $Mes){
                $objPHPExcel->
                        setActiveSheetIndex($NextSheet)
                                ->setCellValueByColumnAndRow($Col,$Row - 1,$Months[$idMes]);
                $objPHPExcel->setActiveSheetIndex($NextSheet)->mergeCells($this->cellsToMergeByColsRow($Col,(count($Mes) * $ContMonths),$Row - 1));
                foreach($Mes as $idSemana => $Semana){
                    $objPHPExcel->
                        setActiveSheetIndex($NextSheet)
                                ->setCellValueByColumnAndRow($Col,$Row,$Semana["WeekTxt"]);
                    $Col++;
                }
                $ContMonths++;
            }

            $Row = 3;
            foreach($ResultNotes["Ejecutivos"] as $idEjecutivo => $Ejecutivo){
                $Col = 0;
                $objPHPExcel->
                    setActiveSheetIndex($NextSheet)
                            ->setCellValueByColumnAndRow($Col,$Row,$Ejecutivo["Ejecutivo"]);
                $Col++;
                foreach($Ejecutivo as $idMes => $Mes){
                    foreach($Mes as $Semana){
                        $objPHPExcel->
                            setActiveSheetIndex($NextSheet)
                                    ->setCellValueByColumnAndRow($Col,$Row,$Semana["Note"]);
                        $Col++;
                    }
                }
                $Row++;
            }


            $NextSheet = 1;
            $objPHPExcel->createSheet($NextSheet);
            $objPHPExcel->setActiveSheetIndex($NextSheet);
            $objPHPExcel->getActiveSheet()->setTitle('Evolución Mensual');
            
            $ResultNotes = $this->getNotesByMonthAndEjecutivo($Periodo);

            $Row = 1;
            $Col = 0;

            $objPHPExcel->
                setActiveSheetIndex($NextSheet)
                        ->setCellValueByColumnAndRow($Col,$Row,"Ejecutivo");
            $Col++;
            $ContMonths = 2;
            $style["borders"] = array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN));
            foreach($ResultNotes["Meses"] as $idMes => $Mes){
                $objPHPExcel->
                        setActiveSheetIndex($NextSheet)
                                ->setCellValueByColumnAndRow($Col,$Row,$Months[$idMes]);
                $Col++;
            }
            $objPHPExcel->
                    setActiveSheetIndex($NextSheet)
                            ->setCellValueByColumnAndRow($Col,$Row,"Variación Ultimos 2 meses");
            $Col++;
            $objPHPExcel->
                    setActiveSheetIndex($NextSheet)
                            ->setCellValueByColumnAndRow($Col,$Row,"Variación Ultimos 3 meses");
            $Col++;

            $Row = 2;
            foreach($ResultNotes["Ejecutivos"] as $idEjecutivo => $Ejecutivo){
                $Col = 0;
                $objPHPExcel->
                    setActiveSheetIndex($NextSheet)
                            ->setCellValueByColumnAndRow($Col,$Row,$Ejecutivo["Ejecutivo"]);
                $Col++;

                foreach($Ejecutivo as $idMes => $Mes){
                    switch($idMes){
                        case 'Ejecutivo':
                        break;
                        default:
                            $objPHPExcel->
                                setActiveSheetIndex($NextSheet)
                                        ->setCellValueByColumnAndRow($Col,$Row,$Mes["Note"]);
                            $Col++;
                        break;
                    }
                }
                $VariacionDosMeses = 0;
                $VariacionTressMeses = 0;
                if($Col >= 4){
                    $NotaUltimoMes = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow($Row, $Col - 1)->getValue();
                    $CellUltimoMes = PHPExcel_Cell::stringFromColumnIndex($Col - 1);
                    $PenultimoMes = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow($Row, $Col - 2)->getValue();
                    $CellPenultimoMes = PHPExcel_Cell::stringFromColumnIndex($Col - 2);
                    $AntePenultimoMes = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow($Row, $Col - 3)->getValue();
                    $CellAntePenultimoMes = PHPExcel_Cell::stringFromColumnIndex($Col - 3);

                    $CellVariacion = PHPExcel_Cell::stringFromColumnIndex($Col);
                    $objPHPExcel->getActiveSheet()->setCellValue($CellVariacion.$Row,"=IF(".$CellPenultimoMes.$Row.">0;((".$CellUltimoMes.$Row."/".$CellPenultimoMes.$Row.")-1)*100;0)");
                    $Col++;
                    $CellVariacion = PHPExcel_Cell::stringFromColumnIndex($Col);
                    $objPHPExcel->getActiveSheet()->setCellValue($CellVariacion.$Row,"=IF(".$CellAntePenultimoMes.$Row.">0;((".$CellUltimoMes.$Row."/".$CellAntePenultimoMes.$Row.")-1)*100;0)");
                    $Col++;
                }
                
                $Row++;
            }
            
            $objPHPExcel->setActiveSheetIndex(0);

            header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment; filename="'.$fileName.'.xlsx"');
			header('Cache-Control: max-age=0');
			$objWriter=PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel2007');
            $objWriter->save('php://output');
            $xlsData = ob_get_contents();
            ob_end_clean();
			$response =  array(
                'filename' => "Informe General - ".$Months[date("m",strtotime($Periodo))]." ".date("Y",strtotime($Periodo))."_".date("d-m-Y") ,
				'file' => "data:application/vnd.ms-excel;base64,".base64_encode($xlsData)
			);

            return $response;
        }
        function getEvaluacionesFromCierre($idEvaluaciones,$Periodo,$TipoCierre,$Personal){
            $db = new DB();
            $WhereEvaluaciones = "";
            $WherePeriodo = "";
            $WherePersonal = "";
            switch($TipoCierre){
                case '0':
                    $WhereEvaluaciones = " and evaluaciones.id IN (".$idEvaluaciones.") ";
                break;
                case '1':
                    $Desde = date("Ym01",strtotime($Periodo));
                    $Hasta = date("Ymt",strtotime($Desde));
                    $WherePeriodo = " and evaluaciones.Fecha_Evaluacion between '".$Desde."' and '".$Hasta."'";
                    $WherePersonal = " and evaluaciones.Id_Personal = '".$Personal."' ";
                    $WhereMandante = " and mandante_cedente.Id_Mandante = '".$_SESSION['mandante']."' ";
                break;
            }
            $TipoUsuario = $this->getTipoUsuario($_SESSION['id_usuario']);
            $SqlEvaluaciones = "SELECT 
                                    grabacion_2.Nombre_Grabacion, round(AVG(respuesta_opciones_afirmaciones_calidad.valor),2) as Nota, grabacion_2.Fecha as Fecha_Grabacion, grabacion_2.Cartera as Cedente, evaluaciones.Fecha_Evaluacion as Fecha_Evaluacion
                                FROM
                                    evaluaciones
                                        INNER JOIN grabacion_2 ON grabacion_2.id = evaluaciones.Id_Grabacion
                                        INNER JOIN respuesta_opciones_afirmaciones_calidad on respuesta_opciones_afirmaciones_calidad.Id_Evaluacion = evaluaciones.id
                                        INNER JOIN mandante_cedente on mandante_cedente.Id_Cedente = evaluaciones.Id_Cedente
                                WHERE
                                    evaluaciones.".$TipoUsuario."
                                    ".$WhereEvaluaciones."
                                    ".$WherePeriodo."
                                    ".$WherePersonal."
                                    ".$WhereMandante."
                                GROUP BY evaluaciones.id";
            $Evaluaciones = $db->select($SqlEvaluaciones);
            return $Evaluaciones;
        }
        function getTipoUsuario($idUsuario){
            $ToReturn = "";
            $db = new DB();
            $SqlUsuario = "select * from Usuarios where id = '".$idUsuario."'";
            $Usuario = $db->select($SqlUsuario);
            if(count($Usuario) > 0){
                $Usuario = $Usuario[0];
                $Nivel = $Usuario["nivel"];
                $MandanteUsuario = $Usuario["mandante"];
                switch($Nivel){
                    case '1':
                        //Administrador
                    break;
                    case '2':
                        if($MandanteUsuario == ""){
                            $ToReturn = "bySupervisorSystem";
                        }else{
                            $ToReturn = "bySupervisorMandante";
                        }
                    break;
                    case '3':
                    break;
                    case '4':
                        if($MandanteUsuario == ""){
                            $ToReturn = "byEjecutivoSystem";
                        }else{
                            $ToReturn = "byEjecutivoMandante";
                        }
                    break;
                    case '5':
                    break;
                    case '6':
                        if($MandanteUsuario == ""){
                            $ToReturn = "byCalidadSystem";
                        }else{
                            $ToReturn = "byCalidadMandante";
                        }
                    break;
                }
            }
            return $ToReturn;
        }
        function getNotesGroupedByCompetencias($Id_Evaluaciones,$Id_Personal){
            $db = new DB();
            $SqlCompetencias = "select
                                    competencias_calidad.id as idCompetencia,
                                    competencias_calidad.nombre as Competencia,
                                    ROUND(AVG(respuesta_opciones_afirmaciones_calidad.valor),2) as Nota,
                                    ROUND(SUM(respuesta_opciones_afirmaciones_calidad.Nota),2) as NotaPonderada
                                from
                                    evaluaciones
                                        inner join respuesta_opciones_afirmaciones_calidad on respuesta_opciones_afirmaciones_calidad.Id_Evaluacion = evaluaciones.id
                                        inner join afirmaciones_dimensiones_competencias_calidad on afirmaciones_dimensiones_competencias_calidad.id = respuesta_opciones_afirmaciones_calidad.id_afirmacion
                                        inner join dimensiones_competencias_calidad on dimensiones_competencias_calidad.id = afirmaciones_dimensiones_competencias_calidad.id_dimension
                                        inner join competencias_calidad on competencias_calidad.id = dimensiones_competencias_calidad.id_competencia
                                where
                                    evaluaciones.Id_Personal='".$Id_Personal."' and
                                    evaluaciones.id in (".$Id_Evaluaciones.")
                                group by
                                    competencias_calidad.nombre";
            $Competencias = $db->select($SqlCompetencias);
            return $Competencias;
        }
        function getCierreEjecutivos_InformeCierre($Month,$Id_Personal){
            $ToReturn = array();
            $db = new DB();
            $Desde = date('Ym01',strtotime($Month));
            $Hasta = date('Ymt',strtotime($Desde));
            $SqlCierres = "select
                                cierre_evaluaciones.*
                            from
                                cierre_evaluaciones
                                inner join Usuarios on Usuarios.id = cierre_evaluaciones.Id_Usuario
                            where
                                cierre_evaluaciones.Id_Mandante='".$_SESSION['mandante']."' and
                                Usuarios.nivel='4' and
                                cierre_evaluaciones.Id_Personal='".$Id_Personal."' and
                                fecha between '".$Desde."' and '".$Hasta."'";
            $Cierres = $db->select($SqlCierres);
            $Cont = 1;
            foreach($Cierres as $Cierre){
                $ArrayTmp = array();
                $ArrayTmp["AspectosFortalecer"] = $Cierre["Aspectos_Fortalecer"];
                $ArrayTmp["AspectosCorregir"] = $Cierre["Aspectos_Corregir"];
                $ArrayTmp["CompromisoEjecutivo"] = $Cierre["Compromiso_Ejecutivo"];
                $Cont++;
                array_push($ToReturn,$ArrayTmp);
            }
            return $ToReturn;
        }
        function selectDimensionesByCompetencia($Competencia){
            $db = new DB();
            $SqlDimensiones = "select
                                    dimensiones_competencias_calidad.id as idDimension,
                                    dimensiones_competencias_calidad.nombre as Dimension,
                                    dimensiones_competencias_calidad.ponderacion as Ponderacion
                                from
                                    dimensiones_competencias_calidad
                                        inner join competencias_calidad on competencias_calidad.id = dimensiones_competencias_calidad.id_competencia
                                where
                                    competencias_calidad.id='".$Competencia."'
                                order by
                                competencias_calidad.nombre";
            $Dimensiones = $db->select($SqlDimensiones);
            return $Dimensiones;
        }
        function selectAfirmacionesByDimension($Dimension){
            $db = new DB();
            $SqlAfirmaciones = "select
                                    afirmaciones_dimensiones_competencias_calidad.id as idAfirmacion,
                                    afirmaciones_dimensiones_competencias_calidad.nombre as Afirmacion,
                                    afirmaciones_dimensiones_competencias_calidad.ponderacion as Ponderacion
                                from
                                    afirmaciones_dimensiones_competencias_calidad
                                        inner join dimensiones_competencias_calidad on dimensiones_competencias_calidad.id = afirmaciones_dimensiones_competencias_calidad.id_dimension
                                        inner join competencias_calidad on competencias_calidad.id = dimensiones_competencias_calidad.id_competencia
                                where
                                    dimensiones_competencias_calidad.id='".$Dimension."'
                                order by
                                dimensiones_competencias_calidad.id";
            $Afirmaciones = $db->select($SqlAfirmaciones);
            return $Afirmaciones;
        }
        function selectOpcionesAfirmacionesByAfirmacion($Afirmacion){
            $db = new DB();
            $SqlOpciones = "select
                                opciones_afirmaciones_competencias_calidad.id as idOpcion,
                                opciones_afirmaciones_competencias_calidad.nombre as Opcion,
                                opciones_afirmaciones_competencias_calidad.valor as Valor
                            from
                                opciones_afirmaciones_competencias_calidad
                                    inner join afirmaciones_dimensiones_competencias_calidad on afirmaciones_dimensiones_competencias_calidad.id = opciones_afirmaciones_competencias_calidad.id_afirmacion
                                    inner join dimensiones_competencias_calidad on dimensiones_competencias_calidad.id = afirmaciones_dimensiones_competencias_calidad.id_dimension
                                    inner join competencias_calidad on competencias_calidad.id = dimensiones_competencias_calidad.id_competencia
                            where
                                afirmaciones_dimensiones_competencias_calidad.id='".$Afirmacion."'
                            order by
                            opciones_afirmaciones_competencias_calidad.valor";
            $Opciones = $db->select($SqlOpciones);
            return $Opciones;
        }
        function getRespuestasAfirmacionesByCompetenciaAndEvaluacion($Competencia,$idEvaluacion){
            $db = new DB();
            $SqlRespuestas = "select
                                    afirmaciones.id as idAfirmacion,
                                    respuestas.nota as Nota,
                                    respuestas.Valor as Valor
                                from
                                    competencias_calidad competencias
                                        inner join dimensiones_competencias_calidad dimensiones on dimensiones.id_competencia = competencias.id
                                        inner join afirmaciones_dimensiones_competencias_calidad afirmaciones on afirmaciones.id_dimension = dimensiones.id
                                        left join respuesta_opciones_afirmaciones_calidad respuestas on respuestas.id_afirmacion = afirmaciones.id
                                where
                                    respuestas.Id_Evaluacion = '".$idEvaluacion."' and
                                    competencias.id='".$Competencia."'
                                order by
                                    dimensiones.id";
            $Respuestas = $db->select($SqlRespuestas);
            return $Respuestas;
        }
        function getPeriodosEvaluacionesByMonthsAndYears($Mandante,$Cedente){
            $WhereCedente = $Cedente != "" ? " and mandante_cedente.Id_Cedente = '".$Cedente."'" : "";
            $db = new DB();
            $ToReturn = array();
            $Months = array();
            $Months[1] = "Enero";
            $Months[2] = "Febrero";
            $Months[3] = "Marzo";
            $Months[4] = "Abril";
            $Months[5] = "Mayo";
            $Months[6] = "Junio";
            $Months[7] = "Julio";
            $Months[8] = "Agosto";
            $Months[9] = "Septiembre";
            $Months[10] = "Octubre";
            $Months[11] = "Noviembre";
            $Months[12] = "Diciembre";

            $SqlEvaluaciones = "select
                                    month(Fecha_Evaluacion) as Month,
                                    year(Fecha_Evaluacion) as Year
                                from
                                    evaluaciones
                                    inner join mandante_cedente on mandante_cedente.Id_Cedente = evaluaciones.Id_Cedente
                                where
                                    mandante_cedente.Id_Mandante = '".$Mandante."'
                                    ".$WhereCedente."
                                group by
                                    month(Fecha_Evaluacion),
                                    year(Fecha_Evaluacion)
                                order by
                                    Fecha_Evaluacion DESC";
            $Evaluaciones = $db->select($SqlEvaluaciones);
            foreach($Evaluaciones as $Evaluacion){
                $ArrayTmp = array();
                $ArrayTmp["Month"] = strlen($Evaluacion["Month"]) == 1 ? "0".$Evaluacion["Month"] : $Evaluacion["Month"];
                $ArrayTmp["MonthText"] = $Months[$Evaluacion["Month"]];
                $ArrayTmp["Year"] = $Evaluacion["Year"];
                array_push($ToReturn,$ArrayTmp);
            }
            return $ToReturn;
        }
        function getNotesByWeekAndEjecutivo($Periodo){
            $db = new DB();
            
            $DateArray = $this->getDateFromServer();
            $Periodo = new DateTime($Periodo);
            $Periodo->modify('first day of this month');
            $Periodo = $Periodo->format('Ymd');
            $Desde = strtotime ('-3 months',strtotime($Periodo));
            $Desde = date('Ymd',$Desde);
            $Hasta = date('Ymt',strtotime($Periodo));

            $ArrayEjecutivos = array();
            $ArraySemanas = array();

            $SqlEjecutivos = "select
                                Personal.Id_Personal,
                                Personal.Nombre as Ejecutivo,
                                YEAR(evaluaciones.Fecha_Evaluacion) as Year,
                                MONTH(evaluaciones.Fecha_Evaluacion) as Month,
                                WEEK(evaluaciones.Fecha_Evaluacion) as Week,
                                MAX(DATE_FORMAT(evaluaciones.Fecha_Evaluacion,'%Y-%m-%d')) as Date,
                                ROUND(AVG(respuesta_opciones_afirmaciones_calidad.Valor),2) as Note
                            from
                                evaluaciones
                                    inner join Personal on Personal.Id_Personal = evaluaciones.Id_Personal
                                    inner join respuesta_opciones_afirmaciones_calidad on respuesta_opciones_afirmaciones_calidad.Id_Evaluacion = evaluaciones.id
                                    inner join mandante_cedente on mandante_cedente.Id_Cedente = evaluaciones.Id_Cedente
                            where
                                mandante_cedente.Id_Mandante = '".$this->Id_Mandante."' and
                                byCalidadSystem = 1 and
                                evaluaciones.Fecha_Evaluacion between '".$Desde."' and '".$Hasta."'
                            group by
                                Personal.Id_Personal,
                                Personal.Nombre,
                                YEAR(evaluaciones.Fecha_Evaluacion),
                                MONTH(evaluaciones.Fecha_Evaluacion),
                                WEEK(evaluaciones.Fecha_Evaluacion)
                            order by
                                YEAR(evaluaciones.Fecha_Evaluacion),
                                MONTH(evaluaciones.Fecha_Evaluacion),
                                WEEK(evaluaciones.Fecha_Evaluacion)";
            $Ejecutivos = $db->select($SqlEjecutivos);
            if(count($Ejecutivos) > 0){
                $PrimeraFecha = $Ejecutivos[0]["Date"];
                $PrimeraFecha = date("Ym01",strtotime($PrimeraFecha));
                $PrimeraFecha = new DateTime($PrimeraFecha);
                $UltimaFecha = $Ejecutivos[count($Ejecutivos) - 1]["Date"];
                $UltimaFecha = date("Ymt",strtotime($UltimaFecha));
                $UltimaFecha = new DateTime($UltimaFecha);

                $Diferencia = $PrimeraFecha->diff($UltimaFecha);

                $Year = $PrimeraFecha->format("Y");
                $Month = $PrimeraFecha->format("m");
                for($i=0; $i<=$Diferencia->m; $i++){
                    $Semanas = $this->getSemanasMes($Year,$Month);
                    $ArraySemanas[$Month] = array();
                    $ArraySemanas[$Month] = $Semanas;
                    $Month = $PrimeraFecha->modify('+1 month')->format("m");
                }
                foreach($Ejecutivos as $Ejecutivo){
                    $Id_Personal = $Ejecutivo["Id_Personal"];
                    $NombreEjecutivo = $Ejecutivo["Ejecutivo"];
                    $Year = $Ejecutivo["Year"];
                    $Month = strlen($Ejecutivo["Month"]) > 1 ? $Ejecutivo["Month"] : "0".$Ejecutivo["Month"];
                    $Week = strlen($Ejecutivo["Week"]) > 1 ? $Ejecutivo["Week"] : "0".$Ejecutivo["Week"];
                    $Date = $Ejecutivo["Date"];
                    $Note = $Ejecutivo["Note"];
                    if(!isset($ArrayEjecutivos[$Id_Personal])){
                        $ArrayEjecutivos[$Id_Personal] = array();
                        $ArrayEjecutivos[$Id_Personal] = $ArraySemanas;
                        $ArrayEjecutivos[$Id_Personal]["Ejecutivo"] = utf8_encode($NombreEjecutivo);
                    }
                    if(!isset($ArrayEjecutivos[$Id_Personal][$Month][$Week])){
                        /*$CantWeeks = count($ArrayEjecutivos[$Id_Personal][$Month]) + 1;
                        $ArrayEjecutivos[$Id_Personal][$Month][$Week]["WeekTxt"] = "Semana ". $CantWeeks;
                        $ArrayEjecutivos[$Id_Personal][$Month][$Week]["Week"] = $Week;
                        $ArrayEjecutivos[$Id_Personal][$Month][$Week]["Note"] = 0;*/
                    }
                    if(isset($ArrayEjecutivos[$Id_Personal][$Month][$Week])){
                        $nota = $ArrayEjecutivos[$Id_Personal][$Month][$Week]["Note"];
                        $nota = number_format($nota + $Note, 2);
                        $ArrayEjecutivos[$Id_Personal][$Month][$Week]["Note"] = $nota;   
                    }
                }
            }


            /*echo "<table>";
                echo "<tr>";
                    echo "<td>";
                        echo "Ejecutivo";
                    echo "</td>";
                    foreach($ArraySemanas as $Meses){
                        foreach($Meses as $Mes){
                            //print_r($Mes);
                            echo "<td>";
                                echo $Mes["WeekTxt"];
                            echo "</td>";
                        }
                    }
                echo "</tr>";
            echo "</table>";*/
            /*echo "<pre>";
            print_r($ArrayEjecutivos);
            echo "</pre>";*/
            $ToReturn = array();
            $ToReturn["Ejecutivos"] = $ArrayEjecutivos;
            $ToReturn["Semanas"] = $ArraySemanas;
            return $ToReturn;
        }
        function getSemanasMes($Year,$Month){
            $DateStrToTime = strtotime($Year.$Month."01");
            $Month = date('m',$DateStrToTime);
            $CantidadDias = date('t',$DateStrToTime);
            $Semanas = array();
            $Semana = "";
            $ContSemanas = 1;
            for($i=1; $i<=$CantidadDias; $i++){
                $Day = $i > 9 ? $i : "0".$i;
                //$DateStrToTime = strtotime($Year.$Month.$Day);
                $Week = date("W",mktime(0,0,0,$Month,$Day,$Year));
                if($Semana != $Week){
                    $ArrayTmp = array();
                    $ArrayTmp["WeekTxt"] = "Semana ".$ContSemanas;
                    $ArrayTmp["Week"] = $Week;
                    $ArrayTmp["Note"] = 0;
                    $Semana = $Week;
                    $ContSemanas++;
                    $Semanas[$Semana] = array();
                    $Semanas[$Semana] = $ArrayTmp;
                    //array_push($Semanas,$ArrayTmp);
                }
            }
            /*echo "<pre>";
            print_r($Semanas);
            echo "</pre>";*/
            return $Semanas;
        }
        function getNotesByMonthAndEjecutivo($Periodo){
            $db = new DB();
            
            $DateArray = $this->getDateFromServer();
            $Periodo = new DateTime($Periodo);
            $Periodo->modify('first day of this month');
            $Periodo = $Periodo->format('Ymd');
            $Desde = strtotime ('-3 months',strtotime($Periodo));
            $Desde = date('Ymd',$Desde);
            $Hasta = date('Ymt',strtotime($Periodo));

            $ArrayEjecutivos = array();
            $ArrayMeses = array();

            $SqlEjecutivos = "select
                                Personal.Id_Personal,
                                Personal.Nombre as Ejecutivo,
                                YEAR(evaluaciones.Fecha_Evaluacion) as Year,
                                MONTH(evaluaciones.Fecha_Evaluacion) as Month,
                                MAX(DATE_FORMAT(evaluaciones.Fecha_Evaluacion,'%Y-%m-%d')) as Date,
                                ROUND(AVG(respuesta_opciones_afirmaciones_calidad.Valor),2) as Note
                            from
                                evaluaciones
                                    inner join Personal on Personal.Id_Personal = evaluaciones.Id_Personal
                                    inner join respuesta_opciones_afirmaciones_calidad on respuesta_opciones_afirmaciones_calidad.Id_Evaluacion = evaluaciones.id
                                    inner join mandante_cedente on mandante_cedente.Id_Cedente = evaluaciones.Id_Cedente
                            where
                                mandante_cedente.Id_Mandante = '".$this->Id_Mandante."' and
                                byCalidadSystem = 1 and
                                evaluaciones.Fecha_Evaluacion between '".$Desde."' and '".$Hasta."'
                            group by
                                Personal.Id_Personal,
                                Personal.Nombre,
                                YEAR(evaluaciones.Fecha_Evaluacion),
                                MONTH(evaluaciones.Fecha_Evaluacion)
                            order by
                                YEAR(evaluaciones.Fecha_Evaluacion),
                                MONTH(evaluaciones.Fecha_Evaluacion)";
            $Ejecutivos = $db->select($SqlEjecutivos);
            if(count($Ejecutivos) > 0){
                $PrimeraFecha = $Ejecutivos[0]["Date"];
                $PrimeraFecha = date("Ym01",strtotime($PrimeraFecha));
                $PrimeraFecha = new DateTime($PrimeraFecha);
                $UltimaFecha = $Ejecutivos[count($Ejecutivos) - 1]["Date"];
                $UltimaFecha = date("Ymt",strtotime($UltimaFecha));
                $UltimaFecha = new DateTime($UltimaFecha);

                $Diferencia = $PrimeraFecha->diff($UltimaFecha);

                $Year = $PrimeraFecha->format("Y");
                $Month = $PrimeraFecha->format("m");
                for($i=0; $i<=$Diferencia->m; $i++){
                    $ArrayTmp = array();
                    $ArrayTmp["Year"] = $Year;
                    $ArrayTmp["Month"] = $Month;
                    $ArrayTmp["Note"] = 0;
                    $ArrayMeses[$Month] = array();
                    $ArrayMeses[$Month] = $ArrayTmp;
                    $Month = $PrimeraFecha->modify('+1 month')->format("m");
                }
                foreach($Ejecutivos as $Ejecutivo){
                    $Id_Personal = $Ejecutivo["Id_Personal"];
                    $NombreEjecutivo = $Ejecutivo["Ejecutivo"];
                    $Year = $Ejecutivo["Year"];
                    $Month = strlen($Ejecutivo["Month"]) > 1 ? $Ejecutivo["Month"] : "0".$Ejecutivo["Month"];
                    $Date = $Ejecutivo["Date"];
                    $Note = $Ejecutivo["Note"];
                    if(!isset($ArrayEjecutivos[$Id_Personal])){
                        $ArrayEjecutivos[$Id_Personal] = array();
                        $ArrayEjecutivos[$Id_Personal] = $ArrayMeses;
                        $ArrayEjecutivos[$Id_Personal]["Ejecutivo"] = utf8_encode($NombreEjecutivo);
                    }
                    if(isset($ArrayEjecutivos[$Id_Personal][$Month])){
                        $nota = $ArrayEjecutivos[$Id_Personal][$Month]["Note"];
                        $nota = number_format($nota + $Note, 2);
                        $ArrayEjecutivos[$Id_Personal][$Month]["Note"] = $nota;   
                    }
                }
            }
            /*echo "<pre>";
            print_r($ArrayEjecutivos);
            echo "</pre>";*/
            $ToReturn = array();
            $ToReturn["Ejecutivos"] = $ArrayEjecutivos;
            $ToReturn["Meses"] = $ArrayMeses;
            return $ToReturn;
        }
        function getAspectosIndividualesByEvaluacion($idEvaluacion, $TipoAspecto = "Corregir", $Competencia = ""){
            $db = new DB();
            $WhereCompetencia = $Competencia != "" ? " competencias_calidad.id='".$Competencia."' and " : "";
            $OperadorAspecto = "";
            switch($TipoAspecto){
                case 'Corregir':
                    $OperadorAspecto = "<=";
                break;
                case 'Fortalecer':
                    $OperadorAspecto = ">";
                break;
            }
            $SqlAspectos = "select
                                opciones_afirmaciones_competencias_calidad.descripcion_caracteristica as Aspecto
                            from
                                respuesta_opciones_afirmaciones_calidad
                                inner join opciones_afirmaciones_competencias_calidad on opciones_afirmaciones_competencias_calidad.id_afirmacion = respuesta_opciones_afirmaciones_calidad.id_afirmacion and opciones_afirmaciones_competencias_calidad.valor = respuesta_opciones_afirmaciones_calidad.Valor
                                inner join afirmaciones_dimensiones_competencias_calidad on afirmaciones_dimensiones_competencias_calidad.id = respuesta_opciones_afirmaciones_calidad.id_afirmacion
                                inner join dimensiones_competencias_calidad on dimensiones_competencias_calidad.id = afirmaciones_dimensiones_competencias_calidad.id_dimension
                                inner join competencias_calidad on competencias_calidad.id = dimensiones_competencias_calidad.id_competencia
                            where
                                ".$WhereCompetencia."
                                Id_Evaluacion in (".$idEvaluacion.") and 
                                respuesta_opciones_afirmaciones_calidad.Valor ".$OperadorAspecto." afirmaciones_dimensiones_competencias_calidad.corte";
            $Aspectos = $db->select($SqlAspectos);
            return $Aspectos;
        }
        function NotaPromedioByPersonal($idPersonal, $Mandante = "",$Periodo){
            $db = new DB();
            $Hasta = date("Ymt",strtotime($Periodo));
            $WhereMandante = $Mandante != "" ? " mandante_cedente.Id_Mandante='".$Mandante."' and " : "";
            $SqlPromedio = "select
                                AVG(respuesta_opciones_afirmaciones_calidad.Valor) as Nota
                            from
                                evaluaciones
                                    inner join respuesta_opciones_afirmaciones_calidad on respuesta_opciones_afirmaciones_calidad.Id_Evaluacion = evaluaciones.id
                                    inner join mandante_cedente on mandante_cedente.Id_Cedente = evaluaciones.Id_Cedente
                            where
                                ".$WhereMandante."
                                evaluaciones.byCalidadSystem = 1 and 
                                evaluaciones.Id_Personal='".$idPersonal."'and
                                evaluaciones.Fecha_Evaluacion <= '".$Hasta."'";
            $Promedio = $db->select($SqlPromedio);
            $Promedio = $Promedio[0]["Nota"];
            return $Promedio;
        }
        function getPerfilEjecutivoByNota($Nota){
            $db = new DB();
            $SqlPerfil = "select * from corte_nivel_ejecutivo_calidad where $Nota between notaMin and notaMax";
            $Perfil = $db->select($SqlPerfil);
            $Perfil = $Perfil[0];
            return $Perfil;
        }
        function getAspectosPromediosByEvaluacion($idEvaluacion, $TipoAspecto = "Corregir", $Competencia = ""){
            $db = new DB();
            $WhereCompetencia = $Competencia != "" ? " competencias_calidad.id='".$Competencia."' and " : "";
            $OperadorAspecto = "";
            switch($TipoAspecto){
                case 'Corregir':
                    $OperadorAspecto = "<=";
                break;
                case 'Fortalecer':
                    $OperadorAspecto = ">";
                break;
            }
            $SqlAspectos = "select
                                opciones_afirmaciones_competencias_calidad.descripcion_caracteristica as Aspecto,
                                afirmaciones_dimensiones_competencias_calidad.corte as Corte,
                                avg(respuesta_opciones_afirmaciones_calidad.Valor) as Nota
                            from
                                respuesta_opciones_afirmaciones_calidad
                                inner join opciones_afirmaciones_competencias_calidad on opciones_afirmaciones_competencias_calidad.id_afirmacion = respuesta_opciones_afirmaciones_calidad.id_afirmacion and opciones_afirmaciones_competencias_calidad.valor = respuesta_opciones_afirmaciones_calidad.Valor
                                inner join afirmaciones_dimensiones_competencias_calidad on afirmaciones_dimensiones_competencias_calidad.id = respuesta_opciones_afirmaciones_calidad.id_afirmacion
                                inner join dimensiones_competencias_calidad on dimensiones_competencias_calidad.id = afirmaciones_dimensiones_competencias_calidad.id_dimension
                                inner join competencias_calidad on competencias_calidad.id = dimensiones_competencias_calidad.id_competencia
                            where
                                ".$WhereCompetencia."
                                Id_Evaluacion in (".$idEvaluacion.") 
                            group by
                                afirmaciones_dimensiones_competencias_calidad.id
                            having 
                                avg(respuesta_opciones_afirmaciones_calidad.Valor) ".$OperadorAspecto." afirmaciones_dimensiones_competencias_calidad.corte";
            $Aspectos = $db->select($SqlAspectos);
            return $Aspectos;
        }
        function getNotasByEvaluationsAndDateGroupedByCompetencia($idPersonal,$Periodo){
            $db = new DB();
            $Desde = date("Ym01",strtotime($Periodo));
            $Hasta = date("Ymt",strtotime($Periodo));
            $TipoUsuario = $this->getTipoUsuario($_SESSION['id_usuario']);
            $SqlNotas = "select
                            competencias_calidad.tag as Competencia,
                            year(evaluaciones.Fecha_Evaluacion) as Year,
                            month(evaluaciones.Fecha_Evaluacion) as Month,
                            ROUND(avg(respuesta_opciones_afirmaciones_calidad.Valor),2) as Nota
                        from
                            evaluaciones
                                inner join respuesta_opciones_afirmaciones_calidad on respuesta_opciones_afirmaciones_calidad.Id_Evaluacion = evaluaciones.id
                                inner join afirmaciones_dimensiones_competencias_calidad on afirmaciones_dimensiones_competencias_calidad.id = respuesta_opciones_afirmaciones_calidad.id_afirmacion
                                inner join dimensiones_competencias_calidad on dimensiones_competencias_calidad.id = afirmaciones_dimensiones_competencias_calidad.id_dimension
                                inner join competencias_calidad on competencias_calidad.id = dimensiones_competencias_calidad.id_competencia
                        where
                            evaluaciones.".$TipoUsuario." and 
                            evaluaciones.Fecha_Evaluacion between DATE_ADD('".$Desde."', INTERVAL -2 MONTH) and '".$Hasta."' and
                            evaluaciones.Id_Personal = '".$idPersonal."'
                        group by
                            competencias_calidad.id,
                            year(evaluaciones.Fecha_Evaluacion),
                            month(evaluaciones.Fecha_Evaluacion)
                        order by
                            year(evaluaciones.Fecha_Evaluacion) DESC,
                            month(evaluaciones.Fecha_Evaluacion) DESC";                        
            $Notas = $db->select($SqlNotas);
            return $Notas;
        }
        function getNotaFromCierre($idCierre){
            $db = new DB();
            $this->Id_Cierre = $idCierre;
            $Cierre = $this->getCierre($idCierre);
            $Cierre = $Cierre[0];
            $TipoCierre = $Cierre["tipo_cierre"];
            $WherePeriodo = "";
            $WherePersonal = "";
            $WhereMandante = "";
            $WhereEvaluaciones = "";
            switch($TipoCierre){
                case '0':
                    $WhereEvaluaciones = " respuesta_opciones_afirmaciones_calidad.Id_Evaluacion IN (".$Cierre["Id_Evaluaciones"].") ";
                break;
                case '1':
                    $Periodo = $Cierre["fecha"];
                    $Desde = date("Ym01",strtotime($Periodo));
                    $Hasta = date("Ymt",strtotime($Desde));
                    $WherePeriodo = " DATE(evaluaciones.Fecha_Evaluacion) between '".$Desde."' and '".$Hasta."'";
                    $WherePersonal = " and evaluaciones.Id_Personal = '".$Cierre["Id_Personal"]."' ";
                    $WhereMandante = " and Id_Mandante = '".$_SESSION['mandante']."' ";
                break;
            }
            $SqlNota = "select
                            AVG(Valor) Nota
                        from
                            respuesta_opciones_afirmaciones_calidad
                                inner join evaluaciones on evaluaciones.id = respuesta_opciones_afirmaciones_calidad.Id_Evaluacion
                        where
                            ".$WhereEvaluaciones."
                            ".$WherePeriodo."
                            ".$WherePersonal."
                            ".$WhereMandante."
                            ";
            $Nota = $db->select($SqlNota);
            return $Nota[0]["Nota"];
        }
        function getPerfilByUserType($UserType,$Mandante,$Ejecutivo){
            $WhereSoloActivos = $Ejecutivo == "" ? " and Personal.Activo = '1' " : "";
            $WhereMandante = $Mandante != "" ? " and mandante_cedente.Id_Mandante='".$Mandante."'" : "";
            $WhereEjecutivo = $Ejecutivo != "" ? " and Personal.Id_Personal = '".$Ejecutivo."'" : "";
            $ByUser = "";
            switch($UserType){
                case '1':
                    //Calidad Sistema
                    $ByUser = "byCalidadSystem";
                break;
                case '2':
                    //Calidad Mandante
                    $ByUser = "byCalidadMandante";
                break;
                case '3':
                    //Ejecutivo Sistema
                    $ByUser = "byEjecutivoSystem";
                break;
                case '4':
                    //Ejecutivo Mandantte
                    $ByUser = "byEjecutivoMandante";
                break;
                case '5':
                break;
                case '6':
                break;
            }
            $db = new Db();
            $SqlNota = "select
                            ROUND(AVG(respuesta_opciones_afirmaciones_calidad.Valor)) as Nota
                        from
                            evaluaciones
                            inner join respuesta_opciones_afirmaciones_calidad on respuesta_opciones_afirmaciones_calidad.Id_Evaluacion = evaluaciones.id
                            inner join mandante_cedente on mandante_cedente.Id_Mandante = respuesta_opciones_afirmaciones_calidad.Id_Mandante
                            inner join Personal on Personal.Id_Personal = evaluaciones.Id_Personal
                        where
                            ".$ByUser." = 1
                            ".$WhereSoloActivos." ".$WhereMandante." ".$WhereEjecutivo." ";
            $Nota = $db->select($SqlNota);
            $Nota = $Nota[0]["Nota"];
            $Perfil = $this->getPerfilEjecutivoByNota($Nota);
            return $Perfil;
        }
        function getTotalEjecutivosMandante($Mandante,$Ejecutivo){
            $WhereMandante = $Mandante != "" ? " and mandante_cedente.Id_Mandante='".$Mandante."'" : "";
            $WhereEjecutivo = $Ejecutivo != "" ? " and Personal.Id_Personal = '".$Ejecutivo."'" : "";
            $WhereSoloActivos = $Ejecutivo == "" ? " and Personal.Activo = '1' " : "";
            $ToReturn = "";
            $db = new DB();
            $SqlTotalEjecutivos = "select
                                    count(*) as TotalEjecutivos
                                from
                                    (select
                                        Personal.Id_Personal
                                    from
                                        evaluaciones
                                            inner join Personal on Personal.Id_Personal = evaluaciones.Id_Personal
                                            inner join mandante_cedente on mandante_cedente.Id_Cedente = evaluaciones.Id_Cedente
                                    where
                                        1 = 1
                                        ".$WhereSoloActivos."
                                        ".$WhereMandante."
                                        ".$WhereEjecutivo."
                                    group by
                                        Personal.Id_Personal) Ejecutivos";
            $TotalEjecutivos = $db ->select($SqlTotalEjecutivos);
            $ToReturn = $TotalEjecutivos[0]["TotalEjecutivos"]."|";
            return $ToReturn;
        }
        function getRutaGrabaciones($NombreArchivo){
            //20170725-185812_995883252_013_mrivero-all.mp3
            $ArrayNombreArchivo = explode("-",$NombreArchivo);
            $Fecha = $ArrayNombreArchivo[0];
            $ArrayNombreArchivo = explode("_",$ArrayNombreArchivo[1]);
            $Hora = $ArrayNombreArchivo[0];
            $Fono = $ArrayNombreArchivo[1];
            $Lista = $ArrayNombreArchivo[2];
            $ArrayNombreArchivo = explode("-",$ArrayNombreArchivo[3]);
            $Usuario = $ArrayNombreArchivo[0];
            return $Lista."/".$Fecha."/".$Usuario;
        }
        function getCompetencias(){
            $db = new Db();
            $EvaluationsArray = array();
            $Cont = 0;
            $SqlEvaluation = "select
                                    competencias.id,
                                    competencias.nombre,
                                    competencias.tag,
                                    competencias.descripcion,
                                    competencias.ponderacion
                                from
                                    competencias_calidad competencias
                                group by
                                    competencias.id
                                order by
                                    competencias.nombre";
		    $Evaluations = $db -> select($SqlEvaluation);
            foreach($Evaluations as $Evaluation){
                $EvaluationArray = array();
                $EvaluationArray['Nombre'] = utf8_encode($Evaluation["nombre"]);
                $EvaluationArray['Tag'] = utf8_encode($Evaluation["tag"]);
                $EvaluationArray['Descripcion'] = utf8_encode($Evaluation["descripcion"]);
                $EvaluationArray['Ponderacion'] = number_format($Evaluation["ponderacion"], 2, '.', '');
                $EvaluationArray['ID'] = $Evaluation["id"];
                $EvaluationsArray[$Cont] = $EvaluationArray;
                $Cont++;
            }
            return $EvaluationsArray;
        }
        function getDimensiones($idCompetencia){
            $db = new Db();
            $DimensionesArray = array();
            $Cont = 0;
            $SqlDimensiones = "select
                                    dimensiones.id as id,
                                    dimensiones.id_competencia as competencia,
                                    dimensiones.nombre as nombre,
                                    dimensiones.ponderacion as ponderacion
                                from
                                    dimensiones_competencias_calidad dimensiones
                                where
                                    dimensiones.id_competencia='".$idCompetencia."'
                                group by
                                    dimensiones.id
                                order by
                                    dimensiones.nombre";
		    $Dimensiones = $db -> select($SqlDimensiones);
            foreach($Dimensiones as $Dimension){
                $DimensionArray = array();
                $DimensionArray['Nombre'] = utf8_encode($Dimension["nombre"]);
                $DimensionArray['Ponderacion'] = number_format($Dimension["ponderacion"], 2, '.', '');
                $DimensionArray['ID'] = $Dimension["competencia"]."_".$Dimension["id"];
                $DimensionesArray[$Cont] = $DimensionArray;
                $Cont++;
            }
            return $DimensionesArray;
        }
        function getAfirmaciones($idDimension){
            $db = new Db();
            $AfirmacionesArray = array();
            $Cont = 0;
            $SqlAfirmaciones = "select
                                    afirmaciones.id as id,
                                    afirmaciones.id_dimension as dimension,
                                    afirmaciones.nombre as nombre,
                                    afirmaciones.ponderacion as ponderacion,
                                    afirmaciones.descripcion_simple as descripcion_simple,
                                    afirmaciones.corte as corte
                                from
                                    afirmaciones_dimensiones_competencias_calidad afirmaciones
                                where
                                    afirmaciones.id_dimension='".$idDimension."'
                                group by
                                    afirmaciones.id
                                order by
                                    afirmaciones.nombre";
		    $Afirmaciones = $db -> select($SqlAfirmaciones);
            foreach($Afirmaciones as $Afirmacion){
                $AfirmacionArray = array();
                $AfirmacionArray['Nombre'] = utf8_encode($Afirmacion["nombre"]);
                $AfirmacionArray['Ponderacion'] = number_format($Afirmacion["ponderacion"], 2, '.', '');
                $AfirmacionArray['DescripcionSimple'] = utf8_encode($Afirmacion["descripcion_simple"]);
                $AfirmacionArray['Corte'] = utf8_encode($Afirmacion["corte"]);
                $AfirmacionArray['ID'] = $Afirmacion["dimension"]."_".$Afirmacion["id"];
                $AfirmacionesArray[$Cont] = $AfirmacionArray;
                $Cont++;
            }
            return $AfirmacionesArray;
        }
        function getOpcionesAfirmaciones($idAfirmacion){
            $db = new Db();
            $OpcionesArray = array();
            $Cont = 0;
            $SqlOpciones = "select
                                    opciones.id as id,
                                    opciones.id_afirmacion as afirmacion,
                                    opciones.nombre as nombre,
                                    opciones.valor as valor,
                                    opciones.descripcion_caracteristica as descripcion_caracteristica
                                from
                                    opciones_afirmaciones_competencias_calidad opciones
                                where
                                    opciones.id_afirmacion='".$idAfirmacion."'
                                group by
                                    opciones.id
                                order by
                                    opciones.nombre";
		    $Opciones = $db -> select($SqlOpciones);
            foreach($Opciones as $Opcion){
                $OpcionArray = array();
                $OpcionArray['Nombre'] = utf8_encode($Opcion["nombre"]);
                $OpcionArray['Valor'] = number_format($Opcion["valor"], 2, '.', '');
                $OpcionArray['DescripcionCaracteristica'] = utf8_encode($Opcion["descripcion_caracteristica"]);
                $OpcionArray['ID'] = $Opcion["afirmacion"]."_".$Opcion["id"];
                $OpcionesArray[$Cont] = $OpcionArray;
                $Cont++;
            }
            return $OpcionesArray;
        }
        function SaveCompetencia($Nombre,$Descripcion,$Ponderacion,$Tag){
            $db = new DB();
            $ToReturn = array();
            $ToReturn["result"] = false;
            $SqlInsert = "insert into competencias_calidad (nombre,descripcion,ponderacion,tag) values ('".$Nombre."','".$Descripcion."','".$Ponderacion."','".$Tag."')";
            $Insert = $db->query($SqlInsert);
            if($Insert){
                $ToReturn["result"] = true;
            }
            return $ToReturn;
        }
        function DeleteCompetencia($idCompetencia){
            $db = new DB();
            $ToReturn = array();
            $ToReturn["result"] = false;
            $SqlDelete = "delete from competencias_calidad where id='".$idCompetencia."'";
            $Delete = $db->query($SqlDelete);
            if($Delete){
                $ToReturn["result"] = true;
            }
            return $ToReturn;
        }
        function GetCompetencia($idCompetencia){
            $db = new DB();
            $ToReturn = array();
            $SqlCompetencia = "select * from competencias_calidad where id='".$idCompetencia."'";
            $Competencia = $db->select($SqlCompetencia);
            $Competencia = $Competencia[0];
            $ToReturn["Nombre"] = utf8_encode($Competencia["nombre"]);
            $ToReturn["Descripcion"] = utf8_encode($Competencia["descripcion"]);
            $ToReturn["Ponderacion"] = $Competencia["ponderacion"];
            $ToReturn["Tag"] = utf8_encode($Competencia["tag"]);
            return $ToReturn;
        }
        function UpdateCompetencia($idCompetencia,$Nombre,$Descripcion,$Ponderacion,$Tag){
            $db = new DB();
            $ToReturn = array();
            $ToReturn["result"] = false;
            $SqlUpdate = "update competencias_calidad set nombre='".$Nombre."', descripcion='".$Descripcion."', ponderacion='".$Ponderacion."', tag='".$Tag."' where id='".$idCompetencia."'";
            $Update = $db->query($SqlUpdate);
            if($Update){
                $ToReturn["result"] = true;
            }
            return $ToReturn;
        }
        function SaveDimension($Nombre,$Ponderacion,$idCompetencia){
            $db = new DB();
            $ToReturn = array();
            $ToReturn["result"] = false;
            $SqlInsert = "insert into dimensiones_competencias_calidad (nombre,ponderacion,id_competencia) values ('".$Nombre."','".$Ponderacion."','".$idCompetencia."')";
            $Insert = $db->query($SqlInsert);
            if($Insert){
                $ToReturn["result"] = true;
            }
            return $ToReturn;
        }
        function DeleteDimension($idDimension){
            $db = new DB();
            $ToReturn = array();
            $ToReturn["result"] = false;
            $SqlDelete = "delete from dimensiones_competencias_calidad where id='".$idDimension."'";
            $Delete = $db->query($SqlDelete);
            if($Delete){
                $ToReturn["result"] = true;
            }
            return $ToReturn;
        }
        function GetDimension($idDimension){
            $db = new DB();
            $ToReturn = array();
            $SqlDimension = "select * from dimensiones_competencias_calidad where id='".$idDimension."'";
            $Dimension = $db->select($SqlDimension);
            $Dimension = $Dimension[0];
            $ToReturn["Nombre"] = utf8_encode($Dimension["nombre"]);
            $ToReturn["Ponderacion"] = $Dimension["ponderacion"];
            return $ToReturn;
        }
        function UpdateDimension($idDimension,$Nombre,$Ponderacion){
            $db = new DB();
            $ToReturn = array();
            $ToReturn["result"] = false;
            $SqlUpdate = "update dimensiones_competencias_calidad set nombre='".$Nombre."', ponderacion='".$Ponderacion."' where id='".$idDimension."'";
            $Update = $db->query($SqlUpdate);
            if($Update){
                $ToReturn["result"] = true;
            }
            return $ToReturn;
        }
        function SaveAfirmacion($Nombre,$Ponderacion,$DescripcionSimple,$Corte,$idDimension){
            $db = new DB();
            $ToReturn = array();
            $ToReturn["result"] = false;
            $SqlInsert = "insert into afirmaciones_dimensiones_competencias_calidad (nombre,ponderacion,descripcion_simple,corte,id_dimension) values ('".$Nombre."','".$Ponderacion."','".$DescripcionSimple."','".$Corte."','".$idDimension."')";
            $Insert = $db->query($SqlInsert);
            if($Insert){
                $ToReturn["result"] = true;
            }
            return $ToReturn;
        }
        function DeleteAfirmacion($idAfirmacion){
            $db = new DB();
            $ToReturn = array();
            $ToReturn["result"] = false;
            $SqlDelete = "delete from afirmaciones_dimensiones_competencias_calidad where id='".$idAfirmacion."'";
            $Delete = $db->query($SqlDelete);
            if($Delete){
                $ToReturn["result"] = true;
            }
            return $ToReturn;
        }
        function GetAfirmacion($idAfirmacion){
            $db = new DB();
            $ToReturn = array();
            $SqlAfirmacion = "select * from afirmaciones_dimensiones_competencias_calidad where id='".$idAfirmacion."'";
            $Afirmacion = $db->select($SqlAfirmacion);
            $Afirmacion = $Afirmacion[0];
            $ToReturn["Nombre"] = utf8_encode($Afirmacion["nombre"]);
            $ToReturn["Ponderacion"] = $Afirmacion["ponderacion"];
            $ToReturn["DescripcionSimple"] = utf8_encode($Afirmacion["descripcion_simple"]);
            $ToReturn["Corte"] = $Afirmacion["corte"];
            return $ToReturn;
        }
        function UpdateAfirmacion($idAfirmacion,$Nombre,$Ponderacion,$DescripcionSimple,$Corte){
            $db = new DB();
            $ToReturn = array();
            $ToReturn["result"] = false;
            $SqlUpdate = "update afirmaciones_dimensiones_competencias_calidad set nombre='".$Nombre."', ponderacion='".$Ponderacion."', descripcion_simple='".$DescripcionSimple."', corte='".$Corte."' where id='".$idAfirmacion."'";
            $Update = $db->query($SqlUpdate);
            if($Update){
                $ToReturn["result"] = true;
            }
            return $ToReturn;
        }
        function SaveOpcionAfirmacion($Nombre,$Valor,$DescripcionCaracteristica,$idAfirmacion){
            $db = new DB();
            $ToReturn = array();
            $ToReturn["result"] = false;
            $SqlInsert = "insert into opciones_afirmaciones_competencias_calidad (nombre,valor,descripcion_caracteristica,id_afirmacion) values ('".$Nombre."','".$Valor."','".$DescripcionCaracteristica."','".$idAfirmacion."')";
            $Insert = $db->query($SqlInsert);
            if($Insert){
                $ToReturn["result"] = true;
            }
            return $ToReturn;
        }
        function DeleteOpcionAfirmacion($OpcionAfirmacion){
            $db = new DB();
            $ToReturn = array();
            $ToReturn["result"] = false;
            $SqlDelete = "delete from opciones_afirmaciones_competencias_calidad where id='".$OpcionAfirmacion."'";
            $Delete = $db->query($SqlDelete);
            if($Delete){
                $ToReturn["result"] = true;
            }
            return $ToReturn;
        }
        function GetOpcionAfirmacion($idOpcionAfirmacion){
            $db = new DB();
            $ToReturn = array();
            $SqlOpcionAfirmacion = "select * from opciones_afirmaciones_competencias_calidad where id='".$idOpcionAfirmacion."'";
            $OpcionAfirmacion = $db->select($SqlOpcionAfirmacion);
            $OpcionAfirmacion = $OpcionAfirmacion[0];
            $ToReturn["Nombre"] = utf8_encode($OpcionAfirmacion["nombre"]);
            $ToReturn["Valor"] = $OpcionAfirmacion["valor"];
            $ToReturn["DescripcionCaracteristica"] = utf8_encode($OpcionAfirmacion["descripcion_caracteristica"]);
            return $ToReturn;
        }
        function UpdateOpcionAfirmacion($idOpcionAfirmacion,$Nombre,$Valor,$DescripcionCaracteristica){
            $db = new DB();
            $ToReturn = array();
            $ToReturn["result"] = false;
            $SqlUpdate = "update opciones_afirmaciones_competencias_calidad set nombre='".$Nombre."', valor='".$Valor."', descripcion_caracteristica='".$DescripcionCaracteristica."' where id='".$idOpcionAfirmacion."'";
            $Update = $db->query($SqlUpdate);
            if($Update){
                $ToReturn["result"] = true;
            }
            return $ToReturn;
        }
    }
?>