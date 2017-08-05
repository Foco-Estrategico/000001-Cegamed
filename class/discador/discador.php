<?php
    
    class Discador{
        
        public $QueueName;
        public $ChannelNumber;
        public $Cedente;
        public $QueueAsterisk;
        public $QueueOriginal;
        public $IdQueue;

       function __construct($IdArg){
            $db = new Db();
            $SqlRecord  = "SELECT q.Queue,d.Cola,d.numero_canales,d.Id_Cedente FROM Asterisk_All_Queues q , Asterisk_Discador_Cola d WHERE q.id_discador = d.id AND d.id=$IdArg";
            $Records = $db -> select($SqlRecord);
            foreach($Records as $Record){
                $this->QueueName = $Record['Queue'];
                $this->QueueOriginal = $Record['Cola'];
                $this->ChannelNumber = $Record['numero_canales'];
                $this->Cedente = $Record['Id_Cedente'];
                $this->QueueAsterisk  =  "DR_".$this->QueueName."_".$this->QueueOriginal;
            }  
            $this->IdQueue = $IdArg;
        }

        public function Stop(){

            $db = new Db();
            $PlayRecord = "SELECT * FROM Asterisk_Discador_Cola WHERE id = $this->IdQueue AND Status = 1 AND Estado = 1";
            $CountPlay = count($db -> select($PlayRecord));
            $StopRecord = "SELECT * FROM Asterisk_Discador_Cola WHERE id = $this->IdQueue AND Status = 1 AND Estado = 0";
            $CountStop = count($db -> select($StopRecord));
            $PauseRecord = "SELECT * FROM Asterisk_Discador_Cola WHERE id = $this->IdQueue AND Status = 1 AND Estado = 2";
            $CountPause = count($db -> select($PauseRecord));
            $DisableRecord = "SELECT * FROM Asterisk_Discador_Cola WHERE id = $this->IdQueue AND Status = 0";
            $CountDisable = count($db -> select($DisableRecord));
            $Stop = 0;
            
            switch (true) {
                case ($CountPlay==1 && $CountPause==0 &&  $CountStop==0 && $CountDisable==0):
                $Stop = 1;
                return $Stop;
                break;

                case ($CountPlay==0 && $CountPause==0 &&  $CountStop==1 && $CountDisable==0):
                $Stop = 0;
                $FechaHora = date("Y-m-d G:i:s");
                $QueryUpdate = "UPDATE $this->QueueAsterisk SET llamado=0  WHERE llamado=1";
                $UpdateRecord = $db -> query($QueryUpdate);
                $QueryDiscador = "UPDATE Asterisk_Discador_Cola SET Estado=$Stop ,FeFin = '$FechaHora' WHERE id = $this->IdQueue";
                $UpdateRecordDiscador = $db -> query($QueryDiscador);
                return $Stop;
                break;

                case ($CountPlay==0 && $CountPause==1 &&  $CountStop==0 && $CountDisable==0):
                $Stop = 2;
                $FechaHora = date("Y-m-d G:i:s");
                $QueryDiscador = "UPDATE Asterisk_Discador_Cola SET Estado=$Stop ,FeFin = '$FechaHora' WHERE id = $this->IdQueue";
                $UpdateRecordDiscador = $db -> query($QueryDiscador);
                return $Stop;
                break;

                case ($CountDisable==1):
                $Stop = 3;
                $FechaHora = date("Y-m-d G:i:s");
                $QueryUpdate = "UPDATE $this->QueueAsterisk SET llamado=0  WHERE llamado=1";
                $UpdateRecord = $db -> query($QueryUpdate);
                $QueryDiscador = "UPDATE Asterisk_Discador_Cola SET Estado=$Stop ,FeFin = '$FechaHora' WHERE id = $this->IdQueue";
                $UpdateRecordDiscador = $db -> query($QueryDiscador);
                return $Stop;
                break;
            }
        }

        public function Start(){
            $db = new Db();
            $Stop = $this->Stop();
            $ArrayMultipler = $this->getMultipler();
            $MultiplerReturn = $ArrayMultipler['Multipler'];
            $PauseReturn = $ArrayMultipler['Pause'];
     
            while($Stop == 1){
                while($Stop == 1 && $MultiplerReturn==0){
                    echo "Waiting...";
                    sleep(1);
                    $ArrayMultipler = $this->getMultipler();
                    $MultiplerReturn = $ArrayMultipler['Multipler'];
                    $PauseReturn = $ArrayMultipler['Pause'];
                    $Stop = $this->Stop();
                }
                $BeginRecord = "SELECT id,Fono,Rut FROM $this->QueueAsterisk WHERE llamado = 0 LIMIT  $MultiplerReturn";
                $CountBegin = count($db -> select($BeginRecord));
                $Records = $db -> select($BeginRecord);
                if($CountBegin > 0){
                    $Stop = $this->Stop();
                    foreach($Records as $Record){
                        $Fono = $Record['Fono'];
                        $Id = $Record['id'];
                        $Rut = $Record['Rut'];
                        
                        $InCallQuery = "SELECT * FROM Asterisk_InCall WHERE Queue = $this->QueueName";
                        $CountIncall = count($db -> select($InCallQuery));

                        if($CountIncall>=$MultiplerReturn){
                            echo "No insertar";
                            sleep(1);
                            $Stop = $this->Stop();
                            $ArrayMultipler = $this->getMultipler();
                            $MultiplerReturn = $ArrayMultipler['Multipler'];
                            $PauseReturn = $ArrayMultipler['Pause'];
                        }
                        else{

                            $SqlInsertRecord = "INSERT INTO Asterisk_InCall(Fono,Rut,Queue) VALUES ('$Fono','$Rut','$this->QueueName')";
                            $InsertRecord = $db -> query($SqlInsertRecord);

                            $SqlUpdateRecord = "UPDATE $this->QueueAsterisk SET llamado = 1 WHERE Fono = $Fono";
                            $UpdateRecord = $db -> query($SqlUpdateRecord);

                            $FonoSip = "SIP/".$Fono."@claro";
                            $asm = new AGI_AsteriskManager();
                            $asm->connect("127.0.0.1","lponce","lponce");
                            $VarAgi = "Id=".$Id."&".$Fono."&".$this->QueueName."&".$Rut."&".$this->Cedente;
                            //$resultado = $asm->originate("$FonoSip","$NombreQueue","from-prueba","1","","","18000","227144101","","","","");
                            $Call = $asm->originate("$FonoSip","$this->QueueName","from-prueba","1","","","18000","227144101","$VarAgi","$VarAgi","true","1001");

                            sleep(2);
                            print_r($Call);
                            $Stop = $this->Stop();
                            $asm->disconnect();
                            echo "Llamando";
                        }
                    }

                } 
                else{
                    $Stop=0;
                }   
                sleep(1);
            }    
        }

        public function getMultipler(){

            $asm = new AGI_AsteriskManager();
            $asm->connect("127.0.0.1","lponce","lponce");

            $db = new Db();
            $AgentAvailables = array();
            $AgentQuery= "SELECT Agente FROM Asterisk_Agentes WHERE Queue = $this->QueueName";
            $Records = $db -> select($AgentQuery);
            foreach($Records as $Record){
                $Agent = $Record['Agente'];
                array_push($AgentAvailables,"$Agent");
            }

            $Unavailable = array();
            $Available = array();
            $ToReturn = array();
            $CountAgent = count($AgentAvailables);
            $Multipler = 0;
            $i = 0;
            while($i<$CountAgent){
                $Result= $asm->Command("queue show $this->QueueName");
                $Agent = $AgentAvailables[$i];
                $Test = implode("\n",$Result);
                $Array = explode("\n",$Test);
                $Count= count($Array);
                $j = 0;
                while($j<$Count){
                    $ArrayTest = explode(" ",$Array[$j]);
                    if  (in_array("$Agent", $ArrayTest) && in_array("(Unavailable)", $ArrayTest)) {
                    }
                    else if (in_array("$Agent", $ArrayTest) && in_array("(paused)", $ArrayTest)){
                        array_push($Unavailable, "$Agent");
                    }
                    else if (in_array("$Agent", $ArrayTest)){
                        array_push($Available, "$Agent");
                    }
                    else{

                    }
                    $j++;
                }
                $i++;
            }
            echo "Function Multipler";
            echo "Paused : "; echo $Pause  =  count($Unavailable);
            echo "Availables: "; echo $Availables = count($Available);
            echo "Multipler :"; echo $Multipler = $Availables*$this->ChannelNumber;
            $ToReturn = array('Multipler' => $Multipler, 'Pause' => $Pause );
            return $ToReturn;
            $asm->disconnect();
        }      
    }    
  
    
?>