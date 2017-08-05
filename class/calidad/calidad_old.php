<?php
    class Calidad_OLD{
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

        public $Id_Group;
        public $Fecha_Agrupamiento;

        public $Id_Mandante;
        public $Id_Cedente;

        public $EvaluatedColum;
        public $EvaluatedValue;

        public $Id_Cierre;

        public $Tipificacion;

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
        }

        function getRecordListAjax(){
            $db = new Db();
            $RecordsArray = array();
            $Cont = 0;
            $WhereTipificacion = $this->Tipificacion != "" ? " and gestion_ult_trimestre.status_name='".$this->Tipificacion."' " : "";
            //$SqlRecord = "select * from grabacion_2 where usuario = '".$this->User."' and Cartera = '".$this->Cartera."' and Fecha BETWEEN '".$this->startDate."' and '".$this->endDate."' order by Fecha";
            $SqlRecord = "select grabacion_2.*,gestion_ult_trimestre.status_name from grabacion_2 inner join Cedente on Cedente.Nombre_Cedente = grabacion_2.Cartera inner join mandante_cedente on mandante_cedente.Id_Cedente = Cedente.Id_Cedente inner join gestion_ult_trimestre on gestion_ult_trimestre.nombre_grabacion = SUBSTR(grabacion_2.Nombre_Grabacion, 1, POSITION('-all' IN grabacion_2.Nombre_Grabacion) - 1) where grabacion_2.usuario = '".$this->User."' and mandante_cedente.Id_Mandante = '".$this->Id_Mandante."' and grabacion_2.Fecha BETWEEN '".$this->startDate."' and '".$this->endDate."' ".$WhereTipificacion." order by Fecha";
		    $Records = $db -> select($SqlRecord);
            foreach($Records as $Record){
                $this->Id_Grabacion = $Record['id'];
                $RecordArrayTmp = array();
                $RecordArrayTmp["Filename"] = $Record["Nombre_Grabacion"];
                $RecordArrayTmp["Date"] = $Record["Fecha"];
                $RecordArrayTmp["Cartera"] = $Record["Cartera"];
                $RecordArrayTmp["User"] = $Record["Usuario"];
                $RecordArrayTmp["Phone"] = $Record["Telefono"];
                $RecordArrayTmp["Listen"] = $this->dir.$Record["Nombre_Grabacion"];
                $RecordArrayTmp["Status"] = $this->hasEvaluation() ? "Evaluada" : "";//$Record["Estado"] == "1" ? "Evaluada" : "";
                $RecordArrayTmp["Evaluar"] = $Record["id"];
                $RecordArrayTmp["Imprimir"] = $Record["id"];
                $RecordArrayTmp["Tipificacion"] = $Record["status_name"];
                $RecordsArray[$Cont] = $RecordArrayTmp;
                $Cont++;
            }
            return $RecordsArray;
        }
        function getTipificacionGrabaciones(){
            $db = new DB();
            $SqlTipificacion = "select gestion_ult_trimestre.status_name from grabacion_2 inner join Cedente on Cedente.Nombre_Cedente = grabacion_2.Cartera inner join mandante_cedente on mandante_cedente.Id_Cedente = Cedente.Id_Cedente inner join gestion_ult_trimestre on gestion_ult_trimestre.nombre_grabacion = SUBSTR(grabacion_2.Nombre_Grabacion, 1, POSITION('-all' IN grabacion_2.Nombre_Grabacion) - 1) where grabacion_2.usuario = '".$this->User."' and mandante_cedente.Id_Mandante = '".$this->Id_Mandante."' and grabacion_2.Fecha BETWEEN '".$this->startDate."' and '".$this->endDate."' GROUP BY gestion_ult_trimestre.status_name order by gestion_ult_trimestre.status_name";
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
                        rename($this->dirTmp.$Filename, $this->dir.$Filename);
                    //
                }else{
                    echo "No paso: ".$Cartera." - ".$Filename."<br>";
                }
                $Cont++;
                
            }
        }
        function getCedenteArray(){
            $ToReturn = array();
            $db = new DB();
            $SqlCedentes = "select vicidial_campaigns.campaign_id as campaign, Cedente.Nombre_Cedente as nombre from Cedente inner join Cedente_Campaign on Cedente_Campaign.id_cedente = Cedente.Id_Cedente inner join vicidial_campaigns on vicidial_campaigns.campaign_id = Cedente_Campaign.id_campaign";
            $Cedentes = $db->select($SqlCedentes);
            foreach($Cedentes as $Cedente){
                $ToReturn[$Cedente["campaign"]] = $Cedente["nombre"];
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
            //$SqlInsertEvaluation = "insert into evaluaciones (Id_Personal, Id_Usuario, Id_Grabacion, Evaluacion_Final, Aspectos_Fortalecer,Aspectos_Corregir,Compromiso_Ejecutivo, Fecha_Evaluacion) values('".$this->Id_Personal."','".$this->Id_Usuario."','".$this->Id_Grabacion."','".$this->Evaluacion_Final."','".$this->Aspectos_Fortalecer."','".$this->Aspectos_Corregir."','".$this->Compromiso_Ejecutivo."',NOW())";
            //$SqlInsertEvaluation = "insert into evaluaciones (Id_Personal, Id_Usuario, Id_Grabacion, Evaluacion_Final, Fecha_Evaluacion, Id_Cedente".$this->EvaluatedColum.") values('".$this->Id_Personal."','".$this->Id_Usuario."','".$this->Id_Grabacion."','".$this->Evaluacion_Final."',NOW(),'".$Cedente."'".$this->EvaluatedValue.")";
            $SqlInsertEvaluation = "insert into evaluaciones (Id_Personal, Id_Usuario, Id_Grabacion, Evaluacion_Final, Fecha_Evaluacion, Id_Cedente".$this->EvaluatedColum.") values('".$this->Id_Personal."','".$this->Id_Usuario."','".$this->Id_Grabacion."','".$this->Evaluacion_Final."',".$this->getFechaEvaluacion($this->Id_Grabacion).",'".$Cedente."'".$this->EvaluatedValue.")";
            $InsertEvaluation = $db -> query($SqlInsertEvaluation);
            /*if($InsertEvaluation !== false){
                $ToReturn = $db->getLastID();
            }else{
                $ToReturn = false;
            }
            return $db->getLastID();*/
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
            $SqlEvaluation = "select * from evaluaciones where Id_Grabacion = '".$this->Id_Grabacion."' and Id_Usuario = '".$this->Id_Usuario."'";
		    $Evaluations = $db -> select($SqlEvaluation);
            return $Evaluations;
        }
        function getEvaluationDetails(){
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
                $EvaluationArray['Observacion'] = "";
                $EvaluationArray['Actions'] = "";
                $EvaluationArray['ObservacionText'] = $Evaluation["Observacion"];
                $EvaluationsArray[$Cont] = $EvaluationArray;
                $Cont++;
            }
            return $EvaluationsArray;
        }
        function getEvaluationTemplate(){
            $db = new Db();
            $EvaluationsArray = array();
            $Cont = 0;
            $SqlEvaluation = "select distinct mantenedor_evaluaciones.* from mantenedor_evaluaciones order by resumen";
		    $Evaluations = $db -> select($SqlEvaluation);
            foreach($Evaluations as $Evaluation){
                $EvaluationArray = array();
                $EvaluationArray['Nombre'] = utf8_encode($Evaluation["resumen"]);
                $EvaluationArray['Descripcion'] = utf8_encode($Evaluation["Descripcion"]);
                $EvaluationArray['Esperado'] = utf8_encode($Evaluation["Esperado"]);
                $EvaluationArray['Ponderacion'] = number_format($Evaluation["Ponderacion"], 2, '.', '');
                $EvaluationArray['Nota'] = number_format(0, 2, '.', '');
                $EvaluationArray['CalificacionPonderada'] = number_format(0, 2, '.', '');
                $EvaluationArray['Observacion'] = "";
                $EvaluationArray['Actions'] = "";
                $EvaluationArray['ObservacionText'] = "";
                $EvaluationsArray[$Cont] = $EvaluationArray;
                $Cont++;
            }
            return $EvaluationsArray;
        }
        function getEvaluationTemplateByPerfil($idPerfil){
            $db = new Db();
            $EvaluationsArray = array();
            $Cont = 0;
            $SqlEvaluation = "select distinct mantenedor_evaluaciones.* from mantenedor_evaluaciones inner join perfil_personal on perfil_personal.id = mantenedor_evaluaciones.id_perfil inner join Personal on Personal.id_perfil = perfil_personal.id where perfil_personal.id = '".$idPerfil."' order by resumen";
		    $Evaluations = $db -> select($SqlEvaluation);
            foreach($Evaluations as $Evaluation){
                $EvaluationArray = array();
                $EvaluationArray['Nombre'] = utf8_encode($Evaluation["resumen"]);
                $EvaluationArray['Descripcion'] = utf8_encode($Evaluation["Descripcion"]);
                $EvaluationArray['Esperado'] = utf8_encode($Evaluation["Esperado"]);
                $EvaluationArray['Ponderacion'] = number_format($Evaluation["Ponderacion"], 2, '.', '');
                $EvaluationArray['Nota'] = number_format(0, 2, '.', '');
                $EvaluationArray['CalificacionPonderada'] = number_format(0, 2, '.', '');
                $EvaluationArray['Observacion'] = "";
                $EvaluationArray['Actions'] = "";
                $EvaluationArray['ObservacionText'] = "";
                $EvaluationsArray[$Cont] = $EvaluationArray;
                $Cont++;
            }
            return $EvaluationsArray;
        }
        function deleteEvaluationDetails(){
            $db = new Db();
            $ToReturn = false;
            $SqlDeleteEvaluacionDetail = "delete from detalle_evaluaciones where Id_Evaluacion = ".$this->Id_Evaluacion;
            $DeleteEvaluacionDetail = $db -> query($SqlDeleteEvaluacionDetail);
            if($DeleteEvaluacionDetail !== false){
                $ToReturn = true;
            }else{
                $ToReturn = false;
            }
            return $ToReturn;
        }
        function addEvaluationDetails($Evaluations){
            $db = new Db();
            foreach($Evaluations as $Evaluation){
                $this->Resumen = $Evaluation[0];
                $this->Description = $Evaluation[1];
                $this->Esperado = $Evaluation[2];
                $this->Ponderacion = $Evaluation[3];
                $this->Nota = $Evaluation[4];
                $this->Observacion = $Evaluation[5];
                $SqlInsertEvaluation = "insert into detalle_evaluaciones (Id_Evaluacion, resumen, Descripcion, Esperado, Ponderacion, Nota, Observacion) values('".$this->Id_Evaluacion."', '".$this->Resumen."', '".$this->Description."', '".$this->Esperado."','".$this->Ponderacion."','".$this->Nota."','".$this->Observacion."')";
                $InsertEvaluation = $db -> query($SqlInsertEvaluation);
            }

        }
        function updateEvaluation(){
            $db = new Db();
            $ToReturn = false;
            //$SqlUpdateEvaluation = "update evaluaciones set Evaluacion_Final = '".$this->Evaluacion_Final."',Aspectos_Fortalecer = '".$this->Aspectos_Fortalecer."',Aspectos_Corregir = '".$this->Aspectos_Corregir."',Compromiso_Ejecutivo='".$this->Compromiso_Ejecutivo."' where Id_Grabacion='".$this->Id_Grabacion."' and Id_Usuario = '".$this->Id_Usuario."'";
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
            //$SqlCartera = "select distinct Cartera from grabacion_2 where grabacion_2.Fecha BETWEEN '".$this->startDate."' and '".$this->endDate."'";
		    $Carteras = $db -> select($SqlCartera);
            return $Carteras;
        }
        function getRecordGroupByIDs($ArrayIDs){

            $inArrayIDs = "(".$ArrayIDs.")";
            //Query (Probar)
                //select grabacion_2.Nombre_Grabacion as Grabacion, grabacion_2.Fecha as fecha, SUM(detalle_evaluaciones.Ponderacion) as Ponderacion, AVG(detalle_evaluaciones.Nota) as Nota, SUM((detalle_evaluaciones.Nota * detalle_evaluaciones.Ponderacion) / 100) as CalfPonderada from grabacion_2 inner join evaluaciones on evaluaciones.Id_Grabacion = grabacion_2.id inner join detalle_evaluaciones on detalle_evaluaciones.Id_Evaluacion = evaluaciones.id where evaluaciones.Id_Usuario='46' group by grabacion_2.Nombre_Grabacion, grabacion_2.Usuario, grabacion_2.Fecha, evaluaciones.Id_Usuario

            $db = new Db();
            $RecordsArray = array();
            $Cont = 0;
            $PromPonderacion = 0;
            $PromNota = 0;
            $PromCalf = 0;
            $SqlRecord = "select evaluaciones.id as ID, grabacion_2.Nombre_Grabacion as Grabacion, grabacion_2.Fecha as fecha, SUM(detalle_evaluaciones.Ponderacion) as Ponderacion, AVG(detalle_evaluaciones.Nota) as Nota, SUM((detalle_evaluaciones.Nota * detalle_evaluaciones.Ponderacion) / 100) as CalfPonderada from grabacion_2 inner join evaluaciones on evaluaciones.Id_Grabacion = grabacion_2.id inner join detalle_evaluaciones on detalle_evaluaciones.Id_Evaluacion = evaluaciones.id where evaluaciones.Id_Usuario='".$this->Id_Usuario."' and grabacion_2.id in ".$inArrayIDs." group by evaluaciones.id, grabacion_2.Nombre_Grabacion, grabacion_2.Usuario, grabacion_2.Fecha, evaluaciones.Id_Usuario";
		    $Records = $db -> select($SqlRecord);
            foreach($Records as $Record){
                $RecordArrayTmp = array();
                $RecordArrayTmp["Grabacion"] = $Record["Grabacion"];
                $RecordArrayTmp["FechaEvaluacion"] = $Record["fecha"];
                $RecordArrayTmp["Ponderacion"] = number_format($Record["Ponderacion"], 2, '.', '');
                $RecordArrayTmp["Nota"] = number_format($Record["Nota"], 2, '.', '');
                $RecordArrayTmp["CalificacionPonderada"] = number_format($Record["CalfPonderada"], 2, '.', '');
                $RecordsArray["Body"][$Cont] = $RecordArrayTmp;
                $Cont++;
                $PromPonderacion += $RecordArrayTmp["Ponderacion"];
                $PromNota += $RecordArrayTmp["Nota"];
                $PromCalf += $RecordArrayTmp["CalificacionPonderada"];
            }
            $PromPonderacion = $PromPonderacion / $Cont;
            $PromNota = $PromNota / $Cont;
            $PromCalf = $PromCalf / $Cont;
            $RecordsArray["Foot"]["Ponderacion"] = number_format($PromPonderacion,2,'.','');
            $RecordsArray["Foot"]["Nota"] = number_format($PromNota,2,'.','');
            $RecordsArray["Foot"]["CalificacionPonderada"] = number_format($PromCalf,2,'.','');
            return $RecordsArray;
        }
        function AddGroup(){
            $db = new Db();
            $ToReturn = false;
            $SqlInsertGroup = "insert into grupos_evaluaciones (Id_Personal, fecha_agrupamiento, Aspectos_Fortalecer, Aspectos_Corregir, Compromiso_Ejecutivo, Id_Usuario) values('".$this->Id_Personal."',NOW(),'".$this->Aspectos_Fortalecer."','".$this->Aspectos_Corregir."','".$this->Compromiso_Ejecutivo."','".$this->Id_Usuario."')";
            $InsertGroup = $db -> query($SqlInsertGroup);
            if($InsertGroup !== false){
                $ToReturn = $db->getLastID();
            }else{
                $ToReturn = false;
            }
            return $db->getLastID();
        }
        function deleteGroupDetails(){
            $db = new Db();
            $ToReturn = false;
            $SqlDeleteGroupDetail = "delete from detalle_grupos_evaluaciones where Id_Grupo = ".$this->Id_Group;
            $DeleteGroupDetail = $db -> query($SqlDeleteGroupDetail);
            if($DeleteGroupDetail !== false){
                $ToReturn = true;
            }else{
                $ToReturn = false;
            }
            return $ToReturn;
        }
        function addGroupDetails($Records){
            $db = new Db();
            $Evaluations = $this->getEvaluationsFromRecords($Records);
            foreach($Evaluations as $Evaluation){
                $SqlInsertGroup = "insert into detalle_grupos_evaluaciones (Id_Grupo, Id_Evaluacion) values('".$this->Id_Group."','".$Evaluation['id']."')";
                $InsertGroup = $db -> query($SqlInsertGroup);
            }
        }
        function getEvaluationsFromRecords($Records){
            $db = new Db();
            $SqlRecord = "select * from evaluaciones where Id_Grabacion in (".$Records.") and Id_Usuario = '".$this->Id_Usuario."' order by id";
		    $Records = $db -> select($SqlRecord);
            return $Records;
        }
        function getRecordListEvaluadosAjax($Periodo = ""){
            $db = new Db();
            $RecordsArray = array();
            $Cont = 0;
            $WhereTipificacion = $this->Tipificacion != "" ? " and gestion_ult_trimestre.status_name='".$this->Tipificacion."' " : "";
            /*$fechaDesde = new DateTime();
            $fechaDesde->modify('first day of this month');
            $Desde = $fechaDesde->format('Ymd'); // imprime por ejemplo: 01/12/2012
            $fechaHasta = new DateTime();
            $fechaHasta->modify('last day of this month');
            $Hasta = $fechaHasta->format('Ymd'); // imprime por ejemplo: 31/12/2012*/
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
            $SqlRecord = "select
                            grabacion_2.id as id,
                            grabacion_2.Nombre_Grabacion as Nombre_Grabacion,
                            grabacion_2.Fecha as Fecha,
                            grabacion_2.Cartera as Cartera,
                            grabacion_2.Usuario as Usuario,
                            grabacion_2.Telefono as Telefono,
                            gestion_ult_trimestre.status_name as Tipificacion
                        from evaluaciones
                            inner join grabacion_2 on grabacion_2.id = evaluaciones.Id_Grabacion
                            inner join Cedente on Cedente.Id_Cedente = evaluaciones.Id_Cedente
                            inner join mandante_cedente on mandante_cedente.Id_Cedente = Cedente.Id_Cedente
                            inner join mandante on mandante.id = mandante_cedente.Id_Mandante
                            inner join gestion_ult_trimestre on gestion_ult_trimestre.nombre_grabacion = SUBSTR(grabacion_2.Nombre_Grabacion, 1, POSITION('-all' IN grabacion_2.Nombre_Grabacion) - 1)
                        where
                            mandante.id = '".$this->Id_Mandante."' and
                            ".$WhereCedente."
                            grabacion_2.Usuario = '".$this->User."' and
                            grabacion_2.Fecha BETWEEN '".$Desde."' and '".$Hasta."'
                            ".$WhereTipificacion."
                        group by
                            grabacion_2.id,
                            grabacion_2.Nombre_Grabacion,
                            grabacion_2.Cartera,
                            grabacion_2.Usuario,
                            grabacion_2.Telefono";
            /*$SqlRecord = "select
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
                            mandante.id = '".$this->Id_Mandante."' and
                            ".$WhereCedente."
                            grabacion_2.Usuario = '".$this->User."' and
                            grabacion_2.Fecha BETWEEN '".$Desde."' and '".$Hasta."'
                        group by
                            grabacion_2.id,
                            grabacion_2.Nombre_Grabacion,
                            grabacion_2.Cartera,
                            grabacion_2.Usuario,
                            grabacion_2.Telefono";*/
		    $Records = $db -> select($SqlRecord);
            foreach($Records as $Record){
                $this->Id_Grabacion = $Record['id'];
                $RecordArrayTmp = array();
                $RecordArrayTmp["Filename"] = $Record["Nombre_Grabacion"];
                $RecordArrayTmp["Date"] = $Record["Fecha"];
                $RecordArrayTmp["Cartera"] = $Record["Cartera"];
                $RecordArrayTmp["User"] = $Record["Usuario"];
                $RecordArrayTmp["Phone"] = $Record["Telefono"];
                $RecordArrayTmp["Listen"] = $this->dir.$Record["Nombre_Grabacion"];
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
                            gestion_ult_trimestre.status_name as Tipificacion
                        from evaluaciones
                            inner join grabacion_2 on grabacion_2.id = evaluaciones.Id_Grabacion
                            inner join Cedente on Cedente.Id_Cedente = evaluaciones.Id_Cedente
                            inner join mandante_cedente on mandante_cedente.Id_Cedente = Cedente.Id_Cedente
                            inner join mandante on mandante.id = mandante_cedente.Id_Mandante
                            inner join gestion_ult_trimestre on gestion_ult_trimestre.nombre_grabacion = SUBSTR(grabacion_2.Nombre_Grabacion, 1, POSITION('-all' IN grabacion_2.Nombre_Grabacion) - 1)
                        where
                            mandante.id = '".$this->Id_Mandante."' and
                            grabacion_2.Usuario = '".$this->User."' and
                            grabacion_2.Fecha BETWEEN '".$Desde."' and '".$Hasta."'
                        group by
                            gestion_ult_trimestre.status_name";
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
            /*$fechaDesde = new DateTime();
            $fechaDesde->modify('first day of this month');
            $Desde = $fechaDesde->format('Ymd'); // imprime por ejemplo: 01/12/2012
            $fechaHasta = new DateTime();
            $fechaHasta->modify('last day of this month');
            $Hasta = $fechaHasta->format('Ymd'); // imprime por ejemplo: 31/12/2012*/
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
            /*$fechaDesde = new DateTime();
            $fechaDesde->modify('first day of this month');
            $Desde = $fechaDesde->format('Ymd'); // imprime por ejemplo: 01/12/2012
            $fechaHasta = new DateTime();
            $fechaHasta->modify('last day of this month');
            $Hasta = $fechaHasta->format('Ymd'); // imprime por ejemplo: 31/12/2012*/
            $Desde = date('Ym01',strtotime($Periodo));
            $Hasta = date('Ymt',strtotime($Desde));
            $db = new Db();
            /*$SqlEvaluation = "select
                                    evaluaciones.*
                                from evaluaciones
                                    inner join grabacion_2 on grabacion_2.id = evaluaciones.Id_Grabacion
                                    inner join Cedente on Cedente.Id_Cedente = evaluaciones.Id_Cedente
                                    inner join mandante_cedente on mandante_cedente.Id_Cedente = Cedente.Id_Cedente
                                    inner join mandante on mandante.id = mandante_cedente.Id_Mandante
                                where
                                    evaluaciones.Id_Usuario = '".$this->Id_Usuario."' and
                                    evaluaciones.Id_Cedente = '".$this->Id_Cedente."' and 
                                    mandante.id = '".$this->Id_Mandante."' and
                                    grabacion_2.Usuario = '".$this->User."' and
                                    grabacion_2.Fecha BETWEEN '".$Desde."' and '".$Hasta."'
                                group by
                                    grabacion_2.id,
                                    grabacion_2.Nombre_Grabacion,
                                    grabacion_2.Cartera,
                                    grabacion_2.Usuario,
                                    grabacion_2.Telefono";*/
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
                $Ponderacion += $ArrayResumenDetalle["Ponderacion"];
                $CalfPonderada += $ArrayResumenDetalle["CalfPonderada"];
            }
            $Id_Evaluaciones = substr($Id_Evaluaciones,0,strlen($Id_Evaluaciones) - 1);
            $Nota = $Nota / count($Evaluations);
            $Ponderacion = $Ponderacion / count($Evaluations);
            $CalfPonderada = $CalfPonderada / count($Evaluations);
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
            $SqlInsertCierre = "insert into cierre_evaluaciones (Id_Evaluaciones,Nota,Ponderacion,Calf_Ponderada,Id_Usuario,Id_Mandante,Id_Cedente,Id_Personal,Aspectos_Fortalecer,Aspectos_Corregir,Compromiso_Ejecutivo,fecha) values('".$Id_Evaluaciones."','".$Nota."','".$Ponderacion."','".$CalfPonderada."','".$this->Id_Usuario."','".$this->Id_Mandante."','".$this->Id_Cedente."','".$Id_Personal."','".$this->Aspectos_Fortalecer."','".$this->Aspectos_Corregir."','".$this->Compromiso_Ejecutivo."',".$Fecha.")";
            $InsertCierre = $db->query($SqlInsertCierre);
            /*if($InsertCierre === true){
                $ToReturn = true;
            }
            return $ToReturn;*/
        }
        function GetDetalleEvaluaciones_Resumen(){
            $ToReturn = array();
            $db = new Db();
            $Query = "select SUM(Ponderacion) as Ponderacion, AVG(Nota) as Nota, SUM((Nota * Ponderacion) / 100) as CalfPonderada from detalle_evaluaciones where Id_Evaluacion = '".$this->Id_Evaluacion."'";
            $EvaluationResume = $db -> select($Query);
            foreach($EvaluationResume as $Evaluation){
                $ToReturn["Nota"] = $Evaluation["Nota"];
                $ToReturn["Ponderacion"] = $Evaluation["Ponderacion"];
                $ToReturn["CalfPonderada"] = $Evaluation["CalfPonderada"];
            }
            return $ToReturn;
        }
        function HizoCierre($Periodo = ""){
            $ToReturn = false;
            $db = new Db();
            $PersonalClass = new Personal();
            $PersonalClass->Username = $this->User;
            $Id_Personal = $PersonalClass->getPersonalIDFromUsername();
            $Query = "select * from cierre_evaluaciones where Id_Usuario = '".$this->Id_Usuario."' and Id_Mandante = '".$this->Id_Mandante."' and Id_Personal = '".$Id_Personal."' and year(fecha) = year(".$Periodo.") and month(fecha) = month(".$Periodo.")";
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
                $CierreArrayTmp["AspectosF"] = $Cierre["Aspectos_Fortalecer"];
                $CierreArrayTmp["AspectosC"] = $Cierre["Aspectos_Corregir"];
                $CierreArrayTmp["CompromisoE"] = $Cierre["Compromiso_Ejecutivo"];
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
                                    SUM(detalle_evaluaciones.Ponderacion) as Ponderacion,
                                    AVG(detalle_evaluaciones.Nota) as Nota,
                                    SUM((detalle_evaluaciones.Nota * detalle_evaluaciones.Ponderacion) / 100) as CalfPonderada
                                from evaluaciones
                                    inner join detalle_evaluaciones on detalle_evaluaciones.Id_Evaluacion = evaluaciones.id
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
                $EvaluationArray['Grabacion'] = $this->dir.$Evaluation["Grabacion"];
                $EvaluationArray['Ponderacion'] = number_format($Evaluation["Ponderacion"], 2, '.', '');
                $EvaluationArray['Nota'] = number_format($Evaluation["Nota"], 2, '.', '');
                $EvaluationArray['CalificacionPonderada'] = number_format($Evaluation["CalfPonderada"], 2,'.','');
                $EvaluationsArray[$Cont] = $EvaluationArray;
                $Cont++;
            }
            return $EvaluationsArray;
        }
        function getGeneralGraphDataByUserType($UserType,$Mandante,$Ejecutivo){
            $WhereMandante = $Mandante != "" ? " and mandante_cedente.Id_Mandante='".$Mandante."'" : "";
            $WhereEjecutivo = $Ejecutivo != "" ? " and Id_Personal = '".$Ejecutivo."'" : "";
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
            $SixMonthsAgo = strtotime ( '-6 months' , strtotime ( $Now ) ) ;
            $SixMonthsAgo = date ( 'Ymd' , $SixMonthsAgo );
            $Array = array();
            $Cont = 0;
            $Month = 1;
            /*$SqlEvaluation = "select
                                    year(evaluaciones.Fecha_Evaluacion) as Year, MONTH(evaluaciones.Fecha_Evaluacion) as Month, ROUND(AVG(Nota),2) as Nota
                                from evaluaciones
                                    INNER JOIN detalle_evaluaciones on detalle_evaluaciones.Id_Evaluacion = evaluaciones.id
                                where
                                    Fecha_Evaluacion BETWEEN '".$SixMonthsAgo."' and '".$Now."' and
                                    Id_Cedente = '".$this->Id_Cedente."' and
                                    Id_Personal = '460' and
                                    (select Id_Evaluaciones from cierre_evaluaciones where FIND_IN_SET(evaluaciones.id,Id_Evaluaciones)) and
                                    ".$ByUser." = 1
                                GROUP by year(evaluaciones.Fecha_Evaluacion), MONTH(evaluaciones.Fecha_Evaluacion)
                                ORDER BY year(evaluaciones.Fecha_Evaluacion) ASC, MONTH(evaluaciones.Fecha_Evaluacion) ASC";*/
            /*$SqlEvaluation = "select
                                    year(evaluaciones.Fecha_Evaluacion) as Year, MONTH(evaluaciones.Fecha_Evaluacion) as Month, ROUND(AVG(Nota),2) as Nota
                                from evaluaciones
                                    INNER JOIN detalle_evaluaciones on detalle_evaluaciones.Id_Evaluacion = evaluaciones.id
                                    INNER JOIN mandante_cedente on mandante_cedente.Id_Cedente = evaluaciones.Id_Cedente
                                where
                                    Fecha_Evaluacion BETWEEN '".$SixMonthsAgo."' and '".$Now."' and
                                    (select Id_Evaluaciones from cierre_evaluaciones where FIND_IN_SET(evaluaciones.id,Id_Evaluaciones)) and
                                    ".$ByUser." = 1 ".$WhereEjecutivo." ".$WhereMandante."
                                GROUP by year(evaluaciones.Fecha_Evaluacion), MONTH(evaluaciones.Fecha_Evaluacion)
                                ORDER BY year(evaluaciones.Fecha_Evaluacion) ASC, MONTH(evaluaciones.Fecha_Evaluacion) ASC";*/
            $SqlEvaluation = "select
                                    year(evaluaciones.Fecha_Evaluacion) as Year, MONTH(evaluaciones.Fecha_Evaluacion) as Month, ROUND(AVG(Nota),2) as Nota
                                from evaluaciones
                                    INNER JOIN detalle_evaluaciones on detalle_evaluaciones.Id_Evaluacion = evaluaciones.id
                                    INNER JOIN mandante_cedente on mandante_cedente.Id_Cedente = evaluaciones.Id_Cedente
                                where
                                    Fecha_Evaluacion BETWEEN '".$SixMonthsAgo."' and '".$Now."' and
                                    ".$ByUser." = 1 ".$WhereEjecutivo." ".$WhereMandante."
                                GROUP by year(evaluaciones.Fecha_Evaluacion), MONTH(evaluaciones.Fecha_Evaluacion)
                                ORDER BY year(evaluaciones.Fecha_Evaluacion) ASC, MONTH(evaluaciones.Fecha_Evaluacion) ASC";
/*            if($ByUser == "byCalidadMandante"){
                echo $SqlEvaluation;
            }*/
		    $Evaluations = $db -> select($SqlEvaluation);
            if(count($Evaluations) > 0){
                $Cant = count($Evaluations);
                $CantEmpty = 6 - $Cant;
                $haveEvaluationThisMonth = $this->haveEvaluationThisMonth($UserType,$Mandante,$Ejecutivo);
                if(!$haveEvaluationThisMonth){
                    $CantEmpty--;
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
                    $DataArray = array();
                    $DataArray[0] = $Month;
                    $DataArray[1] = $Evaluation["Nota"];
                    $Array[$Cont] = $DataArray;
                    $Month++;
                    $Cont++;
                }
                if(!$haveEvaluationThisMonth){
                    $DataArray = array();
                    $DataArray[0] = $Month;
                    $DataArray[1] = 0;
                    $Array[$Cont] = $DataArray;
                    $Month++;
                    $Cont++;
                }
            }else{
                $SqlEvaluation = "select
                                    year(evaluaciones.Fecha_Evaluacion) as Year, MONTH(evaluaciones.Fecha_Evaluacion) as Month, ROUND(AVG(Nota),2) as Nota
                                from evaluaciones
                                    INNER JOIN detalle_evaluaciones on detalle_evaluaciones.Id_Evaluacion = evaluaciones.id
                                    INNER JOIN mandante_cedente on mandante_cedente.Id_Cedente = evaluaciones.Id_Cedente
                                where
                                    YEAR(Fecha_Evaluacion) = YEAR(NOW()) and
                                    MONTH(Fecha_Evaluacion) = MONTH(NOW()) and
                                    ".$ByUser." = 1 ".$WhereEjecutivo." ".$WhereMandante."
                                GROUP by year(evaluaciones.Fecha_Evaluacion), MONTH(evaluaciones.Fecha_Evaluacion)
                                ORDER BY year(evaluaciones.Fecha_Evaluacion) ASC, MONTH(evaluaciones.Fecha_Evaluacion) ASC";
                $Evaluations = $db -> select($SqlEvaluation);
                for($i=1;$i<=6;$i++){
                    $DataArray = array();
                    $DataArray[0] = $Month;
                    $DataArray[1] = 0;
                    $Array[$Cont] = $DataArray;
                    $Cont++;
                    $Month++;
                }
                foreach($Evaluations as $Evaluation){
                    $DataArray = array();
                    $DataArray[0] = $Month;
                    $DataArray[1] = $Evaluation["Nota"];
                    $Array[$Cont] = $DataArray;
                    $Cont++;
                }
            }
            return $Array;
        }
        function getGeneralByEvaluationGraphDataByUserType($UserType,$Mandante,$Ejecutivo){
            
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
            /*$SqlEvaluation = "select
                                    year(evaluaciones.Fecha_Evaluacion) as Year, MONTH(evaluaciones.Fecha_Evaluacion) as Month, ROUND(AVG(Nota),2) as Nota, detalle_evaluaciones.resumen as Resumen
                                from evaluaciones
                                    INNER JOIN detalle_evaluaciones on detalle_evaluaciones.Id_Evaluacion = evaluaciones.id
                                where
                                    Id_Cedente = '".$this->Id_Cedente."' and
                                    Id_Personal = '460' and
                                    YEAR(Fecha_Evaluacion) = YEAR((select MAX(Fecha_Evaluacion) from evaluaciones where Id_Personal = '460')) and
                                    MONTH(Fecha_Evaluacion) = MONTH((select MAX(Fecha_Evaluacion) from evaluaciones where Id_Personal = '460')) and
                                    (select Id_Evaluaciones from cierre_evaluaciones where FIND_IN_SET(evaluaciones.id,Id_Evaluaciones)) and
                                    ".$ByUser." = 1
                                GROUP by year(evaluaciones.Fecha_Evaluacion), MONTH(evaluaciones.Fecha_Evaluacion), detalle_evaluaciones.resumen
                                ORDER BY year(evaluaciones.Fecha_Evaluacion) ASC, MONTH(evaluaciones.Fecha_Evaluacion) ASC, detalle_evaluaciones.resumen ASC";*/
            $SqlEvaluation = "select
                                    year(evaluaciones.Fecha_Evaluacion) as Year, MONTH(evaluaciones.Fecha_Evaluacion) as Month, ROUND(AVG(Nota),2) as Nota, detalle_evaluaciones.resumen as Resumen
                                from evaluaciones
                                    INNER JOIN detalle_evaluaciones on detalle_evaluaciones.Id_Evaluacion = evaluaciones.id
                                    INNER JOIN mandante_cedente on mandante_cedente.Id_Cedente = evaluaciones.Id_Cedente
                                where
                                    YEAR(Fecha_Evaluacion) = YEAR((select MAX(Fecha_Evaluacion) from evaluaciones)) and
                                    MONTH(Fecha_Evaluacion) = MONTH((select MAX(Fecha_Evaluacion) from evaluaciones)) and
                                    ".$ByUser." = 1 ".$WhereEjecutivo." ".$WhereMandante."
                                GROUP by year(evaluaciones.Fecha_Evaluacion), MONTH(evaluaciones.Fecha_Evaluacion), detalle_evaluaciones.resumen
                                ORDER BY year(evaluaciones.Fecha_Evaluacion) ASC, MONTH(evaluaciones.Fecha_Evaluacion) ASC, detalle_evaluaciones.resumen ASC";
		    $Evaluations = $db -> select($SqlEvaluation);
            if(count($Evaluations) > 0){
                /*$Cant = count($Evaluations);
                $CantEmpty = 6 - $Cant;
                if($CantEmpty > 0){
                    for($i=1;$i<=$CantEmpty;$i++){

                    }
                }*/
                foreach($Evaluations as $Evaluation){
                    $DataArray = array();
                    $DataArray["Evaluacion"] = $Evaluation["Resumen"];
                    $DataArray["Nota"] = $Evaluation["Nota"];
                    $DataArray["UserTypeName"] = $UserTypeName;
                    $Array[$Cont] = $DataArray;
                    $Month++;
                    $Cont++;
                }
            }else{
                $SqlEvaluation = "select
                                    year(evaluaciones.Fecha_Evaluacion) as Year, MONTH(evaluaciones.Fecha_Evaluacion) as Month, ROUND(AVG(Nota),2) as Nota, detalle_evaluaciones.resumen as Resumen
                                from evaluaciones
                                    INNER JOIN detalle_evaluaciones on detalle_evaluaciones.Id_Evaluacion = evaluaciones.id
                                    INNER JOIN mandante_cedente on mandante_cedente.Id_Cedente = evaluaciones.Id_Cedente
                                where
                                    YEAR(Fecha_Evaluacion) = YEAR((select MAX(Fecha_Evaluacion) from evaluaciones)) and
                                    MONTH(Fecha_Evaluacion) = MONTH((select MAX(Fecha_Evaluacion) from evaluaciones)) and
                                    ".$ByUser." = 1 ".$WhereEjecutivo." ".$WhereMandante."
                                GROUP by year(evaluaciones.Fecha_Evaluacion), MONTH(evaluaciones.Fecha_Evaluacion), detalle_evaluaciones.resumen
                                ORDER BY year(evaluaciones.Fecha_Evaluacion) ASC, MONTH(evaluaciones.Fecha_Evaluacion) ASC, detalle_evaluaciones.resumen ASC";
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
        function getByEvaluationGraphDataByUserType($UserType,$Mandante,$Ejecutivo){

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
            /*$SqlEvaluation = "select
                                    year(evaluaciones.Fecha_Evaluacion) as Year, MONTH(evaluaciones.Fecha_Evaluacion) as Month, ROUND(AVG(Nota),2) as Nota, detalle_evaluaciones.resumen as Resumen
                                from evaluaciones
                                    INNER JOIN detalle_evaluaciones on detalle_evaluaciones.Id_Evaluacion = evaluaciones.id
                                where
                                    Fecha_Evaluacion BETWEEN '".$SixMonthsAgo."' and '".$Now."' and
                                    Id_Cedente = '".$this->Id_Cedente."' and
                                    Id_Personal = '460' and
                                    (select Id_Evaluaciones from cierre_evaluaciones where FIND_IN_SET(evaluaciones.id,Id_Evaluaciones)) and
                                    ".$ByUser." = 1
                                GROUP by year(evaluaciones.Fecha_Evaluacion), MONTH(evaluaciones.Fecha_Evaluacion), detalle_evaluaciones.resumen
                                ORDER BY detalle_evaluaciones.resumen ASC, year(evaluaciones.Fecha_Evaluacion) ASC, MONTH(evaluaciones.Fecha_Evaluacion) ASC";*/
            $SqlEvaluation = "select
                                    year(evaluaciones.Fecha_Evaluacion) as Year, MONTH(evaluaciones.Fecha_Evaluacion) as Month, ROUND(AVG(Nota),2) as Nota, detalle_evaluaciones.resumen as Resumen
                                from evaluaciones
                                    INNER JOIN detalle_evaluaciones on detalle_evaluaciones.Id_Evaluacion = evaluaciones.id
                                    INNER JOIN mandante_cedente on mandante_cedente.Id_Cedente = evaluaciones.Id_Cedente
                                where
                                    Fecha_Evaluacion BETWEEN '".$SixMonthsAgo."' and '".$Now."' and
                                    ".$ByUser." = 1 ".$WhereEjecutivo." ".$WhereMandante."
                                GROUP by year(evaluaciones.Fecha_Evaluacion), MONTH(evaluaciones.Fecha_Evaluacion), detalle_evaluaciones.resumen
                                ORDER BY detalle_evaluaciones.resumen ASC, year(evaluaciones.Fecha_Evaluacion) DESC, MONTH(evaluaciones.Fecha_Evaluacion) DESC";
		    $Evaluations = $db -> select($SqlEvaluation);
            $EvaluationResumen = "";
            $originales = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿŔŕ';
            $modificadas = 'aaaaaaaceeeeiiiidnoooooouuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr';
            if(count($Evaluations) > 0){
                /*$Cant = count($Evaluations);
                $CantEmpty = 6 - $Cant;
                $haveEvaluationThisMonth = $this->haveEvaluationThisMonth($UserType,$Mandante,$Ejecutivo);
                if(!$haveEvaluationThisMonth){
                    $CantEmpty--;
                }
                if($CantEmpty > 0){
                    $Evaluations = $this->getEvaluationTemplateByPerfil('1');
                    //for($i=1;$i<=$CantEmpty;$i++){
                        foreach($Evaluations as $Evaluation){
                            for($i=1;$i<=$CantEmpty;$i++){
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
                    //}
                }*/
                if($ByUser == "byCalidadSystem"){
                //echo "<br><br><br><br>";
                }
                //$EvaluationResumen = "";
                $Months = $this->MonthsIndex();
                
                foreach($Evaluations as $Evaluation){
                    if($EvaluationResumen != $Evaluation["Resumen"]){
                        $Month = 6;
                        $Cont = 0;
                        $EvaluationResumen = $Evaluation["Resumen"];
                    }
                    $YearDB = $Evaluation["Year"];
                    $MonthDB = strlen($Evaluation["Month"]) == 2 ? $Evaluation["Month"] : "0".$Evaluation["Month"];
                    $Month = $Months[$YearDB."_".$MonthDB];
                    $cadena = $Evaluation["Resumen"];
                    $cadena = utf8_decode($cadena);
                    $cadena = strtr($cadena, utf8_decode($originales), $modificadas);
                    $cadena = utf8_encode($cadena);
                    $DataArray = array();
                    /*if(($Evaluation["Year"] == "".date('Y',strtotime($Now))) && ($Evaluation["Month"] == "".date ('m',strtotime($Now)))){
                        $DataArray[0] = 6;
                    }else{
                        $DataArray[0] = $Month;
                    }*/
                    $DataArray[0] = $Month;
                    $DataArray[1] = $Evaluation["Nota"];
                    $DataArray[2] = $YearDB;
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
                    for($i=1;$i<=6;$i++){
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
                                    INNER JOIN detalle_evaluaciones on detalle_evaluaciones.Id_Evaluacion = evaluaciones.id
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
        function getRankingData($Mandante){
            $db = new DB();
            $SqlRanking = "SELECT Personal.nombre as Nombre, round(avg(detalle_evaluaciones.Nota),2) as Nota from evaluaciones inner join Personal on Personal.Id_Personal = evaluaciones.Id_Personal inner join detalle_evaluaciones on detalle_evaluaciones.Id_Evaluacion = evaluaciones.id inner join mandante_cedente on mandante_cedente.Id_Cedente = evaluaciones.Id_Cedente where mandante_cedente.Id_Mandante = '".$Mandante."' group by Personal.Id_Personal order by Personal.Nombre";
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

            $SqlCierres = "select month(fecha) as Month, year(fecha) as Year from cierre_evaluaciones where Id_Usuario='".$this->Id_Usuario."' group by month(fecha),year(fecha) order by fecha DESC";
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

            $SqlEvaluaciones = "select month(Fecha_Evaluacion) as Month, year(Fecha_Evaluacion) as Year from evaluaciones where Id_Personal='".$this->Id_Personal."' group by month(Fecha_Evaluacion),year(Fecha_Evaluacion) order by Fecha_Evaluacion DESC";
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
        function getCompetencias(){
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
        }
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
                                            detalle_evaluaciones.resumen as Competencia,
                                            AVG(detalle_evaluaciones.Nota) as Nota
                                        from
                                            evaluaciones
                                                inner join detalle_evaluaciones on detalle_evaluaciones.Id_Evaluacion = evaluaciones.id
                                        where
                                            evaluaciones.ByCalidadSystem='1' and
                                            evaluaciones.Id_Personal='".$Ejecutivo["Id_Personal"]."'
                                        group by
                                            detalle_evaluaciones.resumen
                                        order by
                                            detalle_evaluaciones.resumen";
                $SqlNotasCompetenciasEjecutivo = "select
                                            detalle_evaluaciones.resumen as Competencia,
                                            AVG(detalle_evaluaciones.Nota) as Nota
                                        from
                                            evaluaciones
                                                inner join detalle_evaluaciones on detalle_evaluaciones.Id_Evaluacion = evaluaciones.id
                                        where
                                            evaluaciones.ByEjecutivoSystem='1' and
                                            evaluaciones.Id_Personal='".$Ejecutivo["Id_Personal"]."'
                                        group by
                                            detalle_evaluaciones.resumen
                                        order by
                                            detalle_evaluaciones.resumen";
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


            $objPHPExcel->getActiveSheet()->getStyle('B1:M1')->getFont()->setBold(true);
            $objPHPExcel->getActiveSheet()->getStyle('B1:M1')->getFont()->setSize(11);

            $objPHPExcel->setActiveSheetIndex(0)->mergeCells('B1:G1');
            $objPHPExcel->getActiveSheet()->getStyle('B1:G1')->applyFromArray($style);
            $objPHPExcel->getActiveSheet()->getStyle('B1:G1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('00FF00');
            $objPHPExcel->
            setActiveSheetIndex($NextSheet)
                    ->setCellValueByColumnAndRow(1,1,"CALIDAD");
            
            $objPHPExcel->setActiveSheetIndex(0)->mergeCells('H1:M1');
            $objPHPExcel->getActiveSheet()->getStyle('H1:M1')->applyFromArray($style);
            $objPHPExcel->getActiveSheet()->getStyle('H1:M1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('CCCCCC');
            $objPHPExcel->
            setActiveSheetIndex($NextSheet)
                    ->setCellValueByColumnAndRow(7,1,"EJECUTIVO");
            
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
            
            $Col = 7;
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
            
            $objPHPExcel->setActiveSheetIndex(0);

            header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment; filename="'.$fileName.'.xlsx"');
			header('Cache-Control: max-age=0');
			$objWriter=PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel2007');
            $objWriter->save('php://output');
            $xlsData = ob_get_contents();
            ob_end_clean();
			$response =  array(
				'file' => "data:application/vnd.ms-excel;base64,".base64_encode($xlsData)
			);

            return $response;
        }
        function getEvaluacionesFromCierre($idEvaluaciones){
            $db = new DB();
            $SqlEvaluaciones = "SELECT 
                                    grabacion_2.Nombre_Grabacion, round(AVG(detalle_evaluaciones.Nota),2) as Nota, grabacion_2.Fecha as Fecha_Grabacion, grabacion_2.Cartera as Cedente, evaluaciones.Fecha_Evaluacion as Fecha_Evaluacion
                                FROM
                                    evaluaciones
                                        INNER JOIN grabacion_2 ON grabacion_2.id = evaluaciones.Id_Grabacion
                                        INNER JOIN detalle_evaluaciones on detalle_evaluaciones.Id_Evaluacion = evaluaciones.id
                                WHERE
                                    evaluaciones.id IN (".$idEvaluaciones.")
                                GROUP BY evaluaciones.id";
            $Evaluaciones = $db->select($SqlEvaluaciones);
            return $Evaluaciones;
        }
        function getNotesGroupedByCompetencias($Id_Evaluaciones,$Id_Personal){
            $db = new DB();
            $SqlCompetencias = "select detalle_evaluaciones.resumen as Competencia, ROUND(AVG(detalle_evaluaciones.Nota),2) as Nota from evaluaciones inner join detalle_evaluaciones on detalle_evaluaciones.Id_Evaluacion = evaluaciones.id where evaluaciones.Id_Personal='".$Id_Personal."' and evaluaciones.id in (".$Id_Evaluaciones.") group by detalle_evaluaciones.resumen";
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
    }
?>