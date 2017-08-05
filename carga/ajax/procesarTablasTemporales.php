<?php

    include_once("../../db/db.php");
    include_once("../../includes/functions/Functions.php");
    if(!isset($_SESSION)){
        session_start();
    }
    $Id_Cedente = $_SESSION['cedente'];
    $Id_Mandante = $_SESSION['mandante'];
    $Fecha = date('Y-m-d');
    $IdCFecha = $Id_Cedente."_".$Fecha;
    $tipoCarga = "nueva";
    if(isset($_POST['tipoCarga'])){
        $tipoCarga = $_POST['tipoCarga'];
    }

    switch($tipoCarga){
        case 'nueva':
        case 'actualizacion':
            $ArrayColumnsPersona = array();
            $QueryColPersona = mysql_query("SHOW COLUMNS FROM Persona_tmp");
            while($row = mysql_fetch_array($QueryColPersona)){
                if($row[0]=='id_persona'){

                }else{
                    array_push($ArrayColumnsPersona,$row[0]);
                }
            }
            $ArrayImplodePersona = implode(',',$ArrayColumnsPersona);
            switch($tipoCarga){
                case 'nueva':
                    mysql_query("INSERT INTO Persona_Historico ($ArrayImplodePersona) SELECT $ArrayImplodePersona FROM Persona WHERE FIND_IN_SET($Id_Cedente,Id_Cedente)");
                    
                    mysql_query("UPDATE Persona SET Id_Cedente = REPLACE(REPLACE(Id_Cedente,',$Id_Cedente',''),'$Id_Cedente,','') WHERE FIND_IN_SET($Id_Cedente,Id_Cedente)");

                    mysql_query("DELETE FROM Persona WHERE Id_Cedente = $Id_Cedente");

                    mysql_query("UPDATE Persona_Periodo SET Id_Cedente = REPLACE(Id_Cedente,',$Id_Cedente','') WHERE FIND_IN_SET($Id_Cedente,Id_Cedente)");

                    mysql_query("DELETE FROM Persona_Periodo WHERE Id_Cedente = $Id_Cedente");

                    $QueryPersona = "INSERT INTO Persona_Periodo($ArrayImplodePersona) SELECT * FROM Persona_tmp ON DUPLICATE KEY UPDATE Persona_Periodo.Id_Cedente = CONCAT(REPLACE(Persona_Periodo.Id_Cedente,',$Id_Cedente',''),',','$Id_Cedente'), Persona_Periodo.Mandante = CONCAT(REPLACE(Persona_Periodo.Mandante,',$Id_Mandante',''),',','$Id_Mandante')";

                    mysql_query($QueryPersona);
                break;
                case 'actualizacion':
                break;
            }
            $QueryPersona = "INSERT INTO Persona($ArrayImplodePersona) SELECT * FROM Persona_tmp ON DUPLICATE KEY UPDATE Persona.Id_Cedente = CONCAT(REPLACE(Persona.Id_Cedente,',$Id_Cedente',''),',','$Id_Cedente'), Persona.Mandante = CONCAT(REPLACE(Persona.Mandante,',$Id_Mandante',''),',','$Id_Mandante')";
            mysql_query($QueryPersona);


            $ArrayColumnsDeuda = array();
            $QueryColDeuda = mysql_query("SHOW COLUMNS FROM Deuda_tmp");
            while($row = mysql_fetch_array($QueryColDeuda)){
                if($row[0]=='Id_deuda'){

                }else{
                    array_push($ArrayColumnsDeuda,$row[0]);
                }
            }
            $ArrayImplodeDeuda = implode(',',$ArrayColumnsDeuda);
            switch($tipoCarga){
                case 'nueva':
                    mysql_query("INSERT INTO Deuda_Historico ($ArrayImplodeDeuda) SELECT $ArrayImplodeDeuda FROM Deuda WHERE Id_Cedente =  $Id_Cedente");
                    mysql_query("DELETE FROM Deuda WHERE Id_Cedente =  $Id_Cedente");
                break;
                case 'actualizacion':
                break;
            }
            $QueryDeuda = "INSERT INTO Deuda($ArrayImplodeDeuda) SELECT * FROM Deuda_tmp";
            mysql_query($QueryDeuda);


            $ArrayColumnsMail = array();
            $QueryColMail = mysql_query("SHOW COLUMNS FROM Mail");
            while($row = mysql_fetch_array($QueryColMail)){
                if($row[0]=='id_mail'){

                }elseif($row[0]=='Id_Cedente'){

                }else{
                    array_push($ArrayColumnsMail,$row[0]);
                }
            }
            $ArrayImplodeMail = implode(',',$ArrayColumnsMail);
            switch($tipoCarga){
                case 'nueva':
                    mysql_query("DELETE FROM Mail_cedente WHERE Id_Cedente = $Id_Cedente");

                    $QueryFono= "INSERT INTO Mail_cedente ($ArrayImplodeMail,Id_Cedente) SELECT $ArrayImplodeMail,'".$Id_Cedente."' FROM Mail_tmp ";
                    mysql_query($QueryFono);
                break;
                case 'actualizacion':
                break;
            }
            $QueryMail= "INSERT INTO Mail($ArrayImplodeMail) SELECT $ArrayImplodeMail FROM Mail_tmp ON DUPLICATE KEY UPDATE Mail.Origen = CONCAT(Mail.Origen , ',' ,'$IdCFecha')";
            mysql_query($QueryMail);


            $ArrayColumnsDir = array();
            $QueryColDir = mysql_query("SHOW COLUMNS FROM Direcciones");
            while($row = mysql_fetch_array($QueryColDir)){
                if($row[0]=='Id_Direccion'){

                }else{
                    array_push($ArrayColumnsDir,$row[0]);
                }
            }
            $ArrayImplodeDir = implode(',',$ArrayColumnsDir);
            switch($tipoCarga){
                case 'nueva':
                    mysql_query("DELETE FROM Direcciones_cedentes WHERE Id_Cedente = $Id_Cedente");

                    $QueryFono= "INSERT INTO Direcciones_cedentes ($ArrayImplodeDir,Id_Cedente) SELECT $ArrayImplodeDir,'".$Id_Cedente."' FROM Direcciones_tmp ";
                    mysql_query($QueryFono);
                break;
                case 'actualizacion':
                break;
            }
            $QueryDir= "INSERT INTO Direcciones($ArrayImplodeDir) SELECT $ArrayImplodeDir FROM Direcciones_tmp ON DUPLICATE KEY UPDATE Direcciones.Origen = CONCAT(Direcciones.Origen , ',' ,'$IdCFecha')";
            mysql_query($QueryDir);
            


            $ArrayColumnsFono = array();
            $QueryColFono = mysql_query("SHOW COLUMNS FROM fono_cob_tmp");
            while($row = mysql_fetch_array($QueryColFono)){
                if($row[0]=='id_fono'){

                }elseif($row[0]=='Id_Cedente'){

                }else{
                    array_push($ArrayColumnsFono,$row[0]);
                }
            }
            RepairFonos($Id_Cedente);
            $ArrayImplodeFono = implode(',',$ArrayColumnsFono);
            switch($tipoCarga){
                case 'nueva':
                    mysql_query("DELETE FROM fono_cob_cedente WHERE Id_Cedente = $Id_Cedente");

                    $QueryFono= "INSERT INTO fono_cob_cedente ($ArrayImplodeFono,Id_Cedente) SELECT $ArrayImplodeFono,'".$Id_Cedente."' FROM fono_cob_tmp ";
                    mysql_query($QueryFono);
                break;
                case 'actualizacion':
                break;
            }
            $QueryFono= "INSERT INTO fono_cob($ArrayImplodeFono) SELECT $ArrayImplodeFono FROM fono_cob_tmp ON DUPLICATE KEY UPDATE fono_cob.cedente = CONCAT(fono_cob.cedente , ',' ,'$IdCFecha')";
            mysql_query($QueryFono);


            $MontoDeuda = 0;
            $SqlMontoDeuda = mysql_query("SELECT SUM(Monto_Mora) FROM Deuda_tmp WHERE Id_Cedente =  $Id_Cedente");
            while($row = mysql_fetch_array($SqlMontoDeuda)){
                $MontoDeuda = $row[0];
            }

            $Registros = 0;
            $SqlRegistros = mysql_query("SELECT COUNT(Rut) FROM Persona_tmp WHERE Id_Cedente = $Id_Cedente");
            $Registros = "";
            while($row = mysql_fetch_array($SqlRegistros)){
                $Registros = $row[0];
            }
            
            
            $sql = "";
            switch($tipoCarga){
                case 'nueva':
                    $sql = "INSERT INTO Historico_Carga (Id_Cedente,fecha,Cant_Ruts,Deuda_Total) values ('".$_SESSION['cedente']."',NOW(),'".$Registros."','$MontoDeuda')";
                break;
                case 'actualizacion':
                    $sql = "UPDATE Historico_Carga SET Deuda_Total = (Deuda_Total + ".$MontoDeuda.") where id in (select id from (select id from Historico_Carga where Id_Cedente='".$_SESSION['cedente']."' order by id DESC LIMIT 1) tb1)";
                break;
            }
            $result = mysql_query($sql);
            $ToReturn = array();
            if($result){
                $ToReturn["Result"] = "1";
            }else{
                $ToReturn["Result"] = "0";
            }
            $ToReturn["Resume"] = array();
                $ToReturn["Resume"]["Registros"] = $Registros;
                $ToReturn["Resume"]["TotalDeuda"] = $MontoDeuda;
            echo json_encode($ToReturn);

            mysql_query("DELETE FROM Persona_tmp WHERE Id_Cedente = $Id_Cedente");
            mysql_query("DELETE FROM Deuda_tmp WHERE Id_Cedente =  $Id_Cedente");
            mysql_query("DELETE FROM Mail_tmp WHERE Id_Cedente =  $Id_Cedente");
            mysql_query("DELETE FROM fono_cob_tmp WHERE Id_Cedente =  $Id_Cedente");
            mysql_query("DELETE FROM Direcciones_tmp WHERE Id_Cedente =  $Id_Cedente");
        break;
        case 'pagos':
            $ArrayColumnsPagos = array();
            $QueryColPagos = mysql_query("SHOW COLUMNS FROM pagos_deudas_tmp");
            while($row = mysql_fetch_array($QueryColPagos)){
                if($row[0]=='id'){

                }else{
                array_push($ArrayColumnsPagos,$row[0]);
                }
            }
            //mysql_query("DELETE FROM pagos_deudas WHERE Mandante =  $Id_Mandante");
            $ArrayImplodePagos = implode(',',$ArrayColumnsPagos);
            $QueryPagos = "INSERT INTO pagos_deudas($ArrayImplodePagos) SELECT $ArrayImplodePagos FROM pagos_deudas_tmp";
            mysql_query($QueryPagos);

            mysql_query("DELETE FROM pagos_deudas_tmp WHERE Id_Cedente =  $Id_Cedente");
        break;
    }

function RepairFonos($Id_Cedente){
    $QueryFonos = mysql_query("SELECT * FROM fono_cob_tmp where Id_Cedente='".$Id_Cedente."'");
    while($row = mysql_fetch_assoc($QueryFonos)){
        $Codigo = $row["codigo_area"];
        $Fono = $row["formato_subtel"];
        $Depurador = Depurador($Codigo,$Fono,"",false) == "1" ? true : false;
        if(!$Depurador){
            mysql_query("DELETE FROM fono_cob_tmp WHERE Id_Cedente='$Id_Cedente' and formato_subtel='".$Fono."'");
        }
    }
}

?>