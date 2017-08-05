
<?php

class Bienvenida
{
	public function CalendarioAgenda(){
        $Cedente = $_SESSION['cedente'];
        $IdUsuario = $_SESSION['id_usuario'];

        $db = new DB();
        $Query = '';
        $QueryCautiva = "SELECT query FROM SIS_Querys_Estrategias WHERE  cautiva = 1 AND Id_Cedente = $Cedente and IdUserCautiva = $IdUsuario";
        $Cautivos = $db->select($QueryCautiva);
        foreach($Cautivos as $Cautivo){
            $Query = $Cautivo['query'];
        }
        $AgendasArray = array();
        $Cont = 0;
        $SqlAgenda = "SELECT FechaAgenda,Agenda,Rut FROM Agendamiento WHERE Rut IN ($Query)";
        $Agendas = $db->select($SqlAgenda);
        foreach($Agendas as $Agenda){
            $AgendaArray = array();
            $AgendaArray['start']= $Agenda['FechaAgenda'];
            $AgendaArray['title']= "A - ".$Agenda['Agenda'];
            $AgendaArray['Rut']= "Rut Agendado : ".$Agenda['Rut']." Hora : ".$Agenda['FechaAgenda'];
            switch ($Agenda['Agenda']){
                case 'Vencido' :
                    $AgendaArray['className']= "danger";
                    break;
                case 'Hoy' :
                    $AgendaArray['className']= "purple";
                    break;
                case 'Mañana' :
                    $AgendaArray['className']= "success";
                    break;
                case 'Futuro' : 
                    $AgendaArray['className']= "primary";
                    break;
            }
            $AgendasArray[$Cont] = $AgendaArray;
            $Cont++;
        }
        $SqlComp = "SELECT FechaCompromiso,Compromiso,Rut FROM Agendamiento_Compromiso WHERE Rut IN ($Query)";
        $Compromisos = $db->select($SqlComp);
        foreach($Compromisos as $Compromiso){
            $CompromisoArray = array();
            $CompromisoArray['start']= $Compromiso['FechaCompromiso'];
            $CompromisoArray['title']= "C - ".$Compromiso['Compromiso'];
            $CompromisoArray['Rut']= "Rut Compromiso : ".$Compromiso['Rut']." Fecha : ".$Compromiso['FechaCompromiso'];
            switch ($Compromiso['Compromiso']){
                case 'Roto' :
                    $CompromisoArray['className']= "danger";
                    break;
                case 'Hoy' :
                    $CompromisoArray['className']= "purple";
                    break;
                case 'Mañana' :
                    $CompromisoArray['className']= "success";
                    break;
                case 'Futuro' : 
                    $CompromisoArray['className']= "primary";
                    break;
            }
            $AgendasArray[$Cont] = $CompromisoArray;
            $Cont++;
        }
        return $AgendasArray;
	}
}
?>
