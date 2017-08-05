<?php
include("../../db/db.php");


class Reporteria
{
    public $Cedentes = '';

	public function TipoBusqueda($Tipo)
	{
        if($Tipo==1){
            $QueryMandante = mysql_query("SELECT nombre, id FROM mandante");
            echo '<label for="sel1">Seleccione Mandante</label>';
            echo '<select class="selectpicker" id="Mandante"  data-live-search="true" data-width="100%">';
            echo '<option value="0">Seleccione</option>';
            while($row = mysql_fetch_array($QueryMandante)){
                $Nombre = $row[0];
                $IdMandante = $row[1];
                echo "<option value='$IdMandante'>".utf8_encode($Nombre)."</option>";
            }
             echo "</select>";

        }
        elseif($Tipo==2){
           
        }
    }
    public function Cartera($Mandante)
	{
        echo '<label for="sel1">Seleccione Cartera</label>';
        echo '<select class="selectpicker" id="Cartera"  data-live-search="true" data-width="100%">';
        echo '<option value="0">Seleccione</option>';
        echo '<option value="-1">Todas</option>';

        $QueryCedente = mysql_query("SELECT Id_Cedente FROM mandante_cedente WHERE Id_Mandante = $Mandante and activo = 1");
        while($row = mysql_fetch_array($QueryCedente)){
            $IdCedente = $row[0];
            $QueryCartera = mysql_query("SELECT Nombre_Cedente FROM Cedente WHERE Id_Cedente  = $IdCedente LIMIT 1");
            while($row = mysql_fetch_array($QueryCartera)){
                $Nombre = $row[0];
                echo "<option value='$IdCedente'>".utf8_encode($Nombre)."</option>";
                
            }
        }
      
        echo "</select>";
    }

    public function Periodo($Cartera,$Mandante)
	{
        if($Cartera==-1){
   
            $QueryPeriodo = mysql_query("SELECT descripcion,Mandante FROM Periodo_Mandante WHERE Mandante = $Mandante ORDER BY id DESC");
            echo '<label for="sel1">Seleccione Periodo</label>';
            echo '<select class="selectpicker" id="Periodo"  data-live-search="true" data-width="100%">';
            echo '<option value="0">Seleccione</option>';
            while($row = mysql_fetch_array($QueryPeriodo)){
                $Descripcion= $row[0];
                $IdMandante= $row[1];
                echo "<option value='$IdMandante'>".utf8_encode($Descripcion)."</option>";
           
            }
            echo "</select>";
        }
        else{
            $QueryPeriodo = mysql_query("SELECT descripcion,Cedente FROM Periodo_Cedente WHERE Cedente = $Cartera ORDER BY id DESC");
            echo '<label for="sel1">Seleccione Periodo</label>';
            echo '<select class="selectpicker" id="Periodo"  data-live-search="true" data-width="100%">';
            echo '<option value="0">Seleccione</option>';
            while($row = mysql_fetch_array($QueryPeriodo)){
                $Descripcion= $row[0];
                $IdMandante= $row[1];
                echo "<option value='$IdMandante'>".utf8_encode($Descripcion)."</option>";
           
            }
            echo "</select>";

        }
       
    }

    public function VerEjecutivo($Cedente){
        
        if($Cedente==1){
            $Cedentes='10,11,12,13,15,14,31,32';
        }
        elseif($Cedente==2){
            $Cedentes='4,5,6,7,8,9';
        }

        $QueryEjec = mysql_query("SELECT nombre_ejecutivo FROM gestion_ult_trimestre WHERE cedente IN ($Cedentes) GROUP BY nombre_ejecutivo");
        echo '<div class="col-sm-2">';
        echo '<div class="form-group">';
        echo '<label for="sel1">Seleccione Cartera</label>';
        echo '<select class="selectpicker" id="Ejecutivo"  data-live-search="true" data-width="100%">';
        while($row = mysql_fetch_array($QueryEjec)){
            echo "<option value='$row[0]'>".$row[0]."</option>";
        }
        echo '<option value="0">Seleccione</option>';
        echo "</select>";
        echo "</div>";
        echo "</div>";


    }
    public function MostrarGestiones($Tipo,$Periodo,$Mandante,$Cartera){
        
        $ListaTotal = '';
        if($Cartera==-1){
            $QueryListas  = mysql_query("SELECT Lista_Vicidial FROM mandante_cedente WHERE Id_Mandante=$Mandante and activo = 1");
            while($row=mysql_fetch_array($QueryListas)){
                $Lista = $row[0];
                $ListaTotal = $Lista.",".$ListaTotal;
            }
            $ListaTotal = substr($ListaTotal, 0, -1);

            $QueryPeriodo = mysql_query("SELECT Fecha_Inicio,Fecha_Termino FROM Periodo_Mandante WHERE Mandante = $Mandante");
            while($row = mysql_fetch_array($QueryPeriodo)){
                $FechaInicio = $row[0];
                $FechaTermino2 = $row[1];
                $FechaTermino = '';
                if($FechaTermino2=='0000-00-00'){
                    $FechaTermino = date('Y-m-d');
                }
                else{
                    $FechaTermino = $FechaTermino2;
                }
            }

        }else{
            $QueryListas  = mysql_query("SELECT Lista_Vicidial FROM mandante_cedente WHERE Id_Cedente=$Cartera and activo = 1");
            while($row=mysql_fetch_array($QueryListas)){
                $Lista = $row[0];
                $ListaTotal = $Lista.",".$ListaTotal;
            }
            $ListaTotal = substr($ListaTotal, 0, -1);

            $QueryPeriodo = mysql_query("SELECT Fecha_Inicio,Fecha_Termino FROM Periodo_Cedente WHERE Cedente = $Cartera");
            while($row = mysql_fetch_array($QueryPeriodo)){
                $FechaInicio = $row[0];
                $FechaTermino2 = $row[1];
                $FechaTermino = '';
                if($FechaTermino2=='0000-00-00'){
                    $FechaTermino = date('Y-m-d');
                }
                else{
                    $FechaTermino = $FechaTermino2;
                }
            }
        }
        
        $Cedentes = $ListaTotal;
       


        
        echo '<table id="TablaScroll" class="table table-striped table-bordered" cellspacing="0" width="100%">';
        echo '<thead>';

        echo '<tr>';
        echo "<th class='bg-primary'>Total Gestiones Call Día</th>";
        $Encabezado = 1;
        self::Funcion($FechaInicio,$FechaTermino,$Encabezado,$Cedentes,$Periodo,$Cartera,$Mandante);
        echo '</tr></thead><tbody>';

        echo "<tr id=''>";
        echo "<td >Contactados</td>";
        $Encabezado = 2;
        self::Funcion($FechaInicio,$FechaTermino,$Encabezado,$Cedentes,$Periodo,$Cartera,$Mandante);
        echo '</tr>';

        echo "<tr id=''>";
        echo "<td >No Contactados</td>";
        $Encabezado = 3;
        self::Funcion($FechaInicio,$FechaTermino,$Encabezado,$Cedentes,$Periodo,$Cartera,$Mandante);
        echo '</tr>';

        echo "<tr id=''>";
        echo "<td >Total General</td>";
        $Encabezado = 4;
        self::Funcion($FechaInicio,$FechaTermino,$Encabezado,$Cedentes,$Periodo,$Cartera,$Mandante);
        echo '</tr>';

        echo "<tr id=''>";
        echo "<td >N° Ejecutivos</td>";
        $Encabezado = 5;
        self::Funcion($FechaInicio,$FechaTermino,$Encabezado,$Cedentes,$Periodo,$Cartera,$Mandante);
        echo '</tr>';

        echo '</tbody></table>';
        echo "<br>";



        echo '<table id="TablaScroll2" class="table table-striped table-bordered" cellspacing="0" width="100%">';
        echo '<thead>';

        echo '<tr>';
        echo "<th class='bg-primary'>Total Gestiones Call Acumulado</th>";
        $Encabezado = 6;
        self::Funcion($FechaInicio,$FechaTermino,$Encabezado,$Cedentes,$Periodo,$Cartera,$Mandante);
        echo '</tr></thead><tbody>';

        echo "<tr id=''>";
        echo "<td >Contactados</td>";
        $Encabezado = 7;
        self::Funcion($FechaInicio,$FechaTermino,$Encabezado,$Cedentes,$Periodo,$Cartera,$Mandante);
        echo '</tr>';

        echo "<tr id=''>";
        echo "<td >No Contactados</td>";
        $Encabezado = 8;
        self::Funcion($FechaInicio,$FechaTermino,$Encabezado,$Cedentes,$Periodo,$Cartera,$Mandante);
        echo '</tr>';

        echo "<tr id=''>";
        echo "<td >Total General</td>";
        $Encabezado = 9;
        self::Funcion($FechaInicio,$FechaTermino,$Encabezado,$Cedentes,$Periodo,$Cartera,$Mandante);
        echo '</tr>';

        echo '</tbody></table>';
        echo "<br>";


        echo '<table id="TablaScroll3" class="table table-striped table-bordered" cellspacing="0" width="100%">';
        echo '<thead>';

        echo '<tr>';
        echo "<th class='bg-warning'>Total Gestiones Rut Unicos Cartera/th>";
        $Encabezado = 10;
        self::Funcion($FechaInicio,$FechaTermino,$Encabezado,$Cedentes,$Periodo,$Cartera,$Mandante);
        echo '</tr></thead><tbody>';

        echo "<tr id=''>";
        echo "<td >Contactados</td>";
        $Encabezado = 11;
        self::Funcion($FechaInicio,$FechaTermino,$Encabezado,$Cedentes,$Periodo,$Cartera,$Mandante);
        echo '</tr>';

        echo "<tr id=''>";
        echo "<td >No Contactados</td>";
        $Encabezado = 12;
        self::Funcion($FechaInicio,$FechaTermino,$Encabezado,$Cedentes,$Periodo,$Cartera,$Mandante);
        echo '</tr>';

        echo "<tr id=''>";
        echo "<td >Total General</td>";
        $Encabezado = 13;
        self::Funcion($FechaInicio,$FechaTermino,$Encabezado,$Cedentes,$Periodo,$Cartera,$Mandante);
        echo '</tr>';

        echo "<tr id=''>";
        echo "<td >Contactabilidad</td>";
        $Encabezado = 14;
        self::Funcion($FechaInicio,$FechaTermino,$Encabezado,$Cedentes,$Periodo,$Cartera,$Mandante);
        echo '</tr>';

        echo '</tbody></table>';
        echo "<br>";


        echo '<table id="TablaScroll4" class="table table-striped table-bordered" cellspacing="0" width="100%">';
        echo '<thead>';

        echo '<tr>';
        echo "<th class='bg-warning'>Total Gestiones Acumuladas Rut Unicos</th>";
        $Encabezado = 15;
        self::Funcion($FechaInicio,$FechaTermino,$Encabezado,$Cedentes,$Periodo,$Cartera,$Mandante);
        echo '</tr></thead><tbody>';

        echo "<tr id=''>";
        echo "<td >Contactados</td>";
        $Encabezado = 16;
        self::Funcion($FechaInicio,$FechaTermino,$Encabezado,$Cedentes,$Periodo,$Cartera,$Mandante);
        echo '</tr>';

        echo "<tr id=''>";
        echo "<td >No Contactados</td>";
        $Encabezado = 17;
        self::Funcion($FechaInicio,$FechaTermino,$Encabezado,$Cedentes,$Periodo,$Cartera,$Mandante);
        echo '</tr>';

        echo "<tr id=''>";
        echo "<td >Total General</td>";
        $Encabezado = 18;
        self::Funcion($FechaInicio,$FechaTermino,$Encabezado,$Cedentes,$Periodo,$Cartera,$Mandante);
        echo '</tr>';

        echo '</tbody></table>';
        echo "<br>";

        //Contactabilida Real
        echo '<table id="TablaScroll5" class="table table-striped table-bordered" cellspacing="0" width="100%">';
        echo '<thead>';
        echo '<tr>';
        echo "<th class='bg-danger'>Contactabilidad Real Periodo</th>";
        $Encabezado = 19;
        self::Funcion($FechaInicio,$FechaTermino,$Encabezado,$Cedentes,$Periodo,$Cartera,$Mandante);
        echo '</tr></thead><tbody>';

        echo "<tr id=''>";
        echo "<td >Asignacion</td>";
        $Encabezado = 20;
        self::Funcion($FechaInicio,$FechaTermino,$Encabezado,$Cedentes,$Periodo,$Cartera,$Mandante);
        echo '</tr>';

        echo "<tr id=''>";
        echo "<td >Contactabilidad Real</td>";
        $Encabezado = 21;
        self::Funcion($FechaInicio,$FechaTermino,$Encabezado,$Cedentes,$Periodo,$Cartera,$Mandante);
        echo '</tr>';

        echo "<tr id=''>";
        if($Cartera==-1){
            $QueryMeta = mysql_query("SELECT Meta FROM mandante_cedente WHERE Id_Mandante=$Periodo and activo = 1");
            $x=0;
            $MetaTotal = 0;
            while($row=mysql_fetch_array($QueryMeta)){
                $Meta = $row[0];
                $MetaTotal = $Meta+$MetaTotal;
                $x++;
            }
            $Promedio = round($MetaTotal/$x);
        }else{
            $QueryMeta = mysql_query("SELECT Meta FROM mandante_cedente WHERE Id_Cedente=$Periodo and activo = 1");
            $x=0;
            $MetaTotal = 0;
            while($row=mysql_fetch_array($QueryMeta)){
                $Meta = $row[0];
                $MetaTotal = $Meta+$MetaTotal;
                $x++;
            }
            $Promedio = round($MetaTotal/$x);
        }
        
        echo "<td>Cumplimiento sobre Meta :<b> $Promedio % </b></td>";
        $Encabezado = 22;
        self::Funcion($FechaInicio,$FechaTermino,$Encabezado,$Cedentes,$Periodo,$Cartera,$Mandante);
        echo '</tr>';

        echo '</tbody></table>';
        echo "<br>";
        /*echo '<table id="TablaScroll2" class="table table-striped table-bordered" cellspacing="0" width="100%">';
        echo '<thead>';

        echo '<tr>';
        echo "<th class='text-sm bg-info'>Acumulado</th>";
        $Encabezado = 7;
        self::Funcion($FechaInicio,$FechaTermino,$Encabezado,$Cedentes,$Periodo);
        echo '</tr></thead><tbody>';

        echo "<tr id=''>";
        echo "<td class='text-sm'>Contactados</td>";
        $Encabezado = 8;
        self::Funcion($FechaInicio,$FechaTermino,$Encabezado,$Cedentes,$Periodo);
        echo '</tr>';

        echo "<tr id=''>";
        echo "<td class='text-sm'>No Contactados</td>";
        $Encabezado = 9;
        self::Funcion($FechaInicio,$FechaTermino,$Encabezado,$Cedentes,$Periodo);
        echo '</tr>';

        echo "<tr id=''>";
        echo "<td class='text-sm'>Total General</td>";
        $Encabezado = 10;
        self::Funcion($FechaInicio,$FechaTermino,$Encabezado,$Cedentes,$Periodo);
        echo '</tr>';
        
        echo '</tbody></table>';*/


    }
    public function Funcion($FechaInicio,$FechaTermino,$Encabezado,$Cedentes,$Periodo,$Cartera,$Mandante){
        $this->FechaInicio=$FechaInicio;
        $this->FechaTermino=$FechaTermino;
        $this->Encabezado=$Encabezado;
        $this->Cedentes=$Cedentes;
       if($this->Encabezado==22){
            $data = array();
        }
        else if($this->Encabezado==11){
            $data2 = array();
        }
        else if($this->Encabezado==14){
            $data3 = array();
        }
        else if($this->Encabezado==16){
            $data4 = array();
        }
        else if($this->Encabezado==18){
            $data5 = array();
        }
        
        $Query = '';
        $Cantidad = 0;
        $Fecha='';
        $Fecha_Ultima = '';
        
        
        $datetime1 = date_create($this->FechaInicio);
        $datetime2 = date_create($this->FechaTermino);
        $interval = date_diff($datetime1, $datetime2);
        $days = $interval->format('%a');
        $i=0;    
        
        $DiaArray = explode('-',$this->FechaInicio);
        $Dia = $DiaArray[2];
        $Mes = $DiaArray[1];
        $Ano = $DiaArray[0];
        
        while($i<=$days){
            switch($Mes){
                case "1" : $MesNombre = "Ene"; break;
                case "2" : $MesNombre = "Feb"; break;
                case "3" : $MesNombre = "Mar"; break;
                case "4" : $MesNombre = "Abr"; break;
                case "5" : $MesNombre = "May"; break;
                case "6" : $MesNombre = "Jun"; break;
                case "7" : $MesNombre = "Jul"; break;
                case "8" : $MesNombre = "Ago"; break;
                case "9" : $MesNombre = "Sep"; break;
                case "10" : $MesNombre = "Oct"; break;
                case "11" : $MesNombre = "Nov"; break;
                case "12" : $MesNombre = "Dic"; break;
                   
            }

            $Fecha = $Ano."-".$Mes."-".$Dia;
            $fecha = new DateTime($Fecha);
            $fecha->modify('last day of this month');
            $Ultimo = $fecha->format('d');
            if($Dia==$Ultimo && $Mes==12){
                $Fecha = $Ano."-".$Mes."-".$Dia;
                $Mes=1;
                $Ano = $Ano+1;
                $Dia=0;

            }

            else if($Dia==$Ultimo){
                $Fecha = $Ano."-".$Mes."-".$Dia;
                $Fecha_Ultima = $Ultimo;
                $Mes=$Mes+1;
                $Dia=0;

            }
            else{
                $Fecha = $Ano."-".$Mes."-".$Dia;
            }
            switch($this->Encabezado){
                
                case 1 : 
                    if($Dia==""){
                        $Dia == $Fecha_Ultima;
                    }
                    else if(strlen($Dia)<2){
                        $Dia = "0".$Dia;
                    }else{}
                    if($Dia == 0){
                        echo "<th class='bg-primary'><center>".$MesNombre."-".$Ultimo."</center></th>";

                    }else{
                        echo "<th class='bg-primary'><center>".$MesNombre."-".$Dia."</center></th>";

                    }
                break;

                case 2 :
                    $Query = mysql_query("SELECT rut_cliente FROM gestion_ult_trimestre WHERE cedente IN($this->Cedentes) and fecha_gestion = '$Fecha' AND Id_TipoGestion IN (1,2,5)");
                    $Cantidad = mysql_num_rows($Query);
                    echo "<td ><center>".$Cantidad."</center></td>";
                break;

                case 3 :
                    $Query = mysql_query("SELECT rut_cliente FROM gestion_ult_trimestre WHERE cedente IN($this->Cedentes) and fecha_gestion = '$Fecha' AND Id_TipoGestion  NOT IN (1,2,5)");
                    $Cantidad = mysql_num_rows($Query);
                    echo "<td ><center>".$Cantidad."</center></td>";
                break;

                case 4 :
                    $Query = mysql_query("SELECT rut_cliente FROM gestion_ult_trimestre WHERE cedente IN($this->Cedentes) and fecha_gestion = '$Fecha' ");
                    $Cantidad = mysql_num_rows($Query);
                    echo "<td ><center>".$Cantidad."</center></td>";
                break;

                case 5 :
                    $Query = mysql_query("SELECT rut_cliente FROM gestion_ult_trimestre WHERE  fecha_gestion = '$Fecha' AND cedente IN($this->Cedentes)  AND NOT nombre_ejecutivo='VDAD' GROUP BY nombre_ejecutivo  ");
                    $Cantidad = mysql_num_rows($Query);
                    echo "<td ><center>".$Cantidad."</center></td>";
                break;
                
                case 6 : 
                    if($Dia==""){
                        $Dia == $Fecha_Ultima;
                    }
                    else if(strlen($Dia)<2){
                        $Dia = "0".$Dia;
                    }else{}
                    if($Dia == 0){
                        echo "<th class='bg-primary'><center>".$MesNombre."-".$Ultimo."</center></th>";

                    }else{
                        echo "<th class='bg-primary'><center>".$MesNombre."-".$Dia."</center></th>";

                    }
                break;

                case 7 :
                    $Query = mysql_query("SELECT rut_cliente FROM gestion_ult_trimestre WHERE cedente IN($this->Cedentes) and fecha_gestion = '$Fecha' AND Id_TipoGestion IN (1,2,5)");
                    $Cantidad = mysql_num_rows($Query);
                    $CantidadTotal = $Cantidad+$CantidadTotal;
                    echo "<td ><center>".$CantidadTotal."</center></td>";
                break;

                case 8 :
                    $Query = mysql_query("SELECT rut_cliente FROM gestion_ult_trimestre WHERE cedente IN($this->Cedentes) and fecha_gestion = '$Fecha' AND Id_TipoGestion NOT IN (1,2,5)");
                    $Cantidad = mysql_num_rows($Query);
                    $CantidadTotal = $Cantidad+$CantidadTotal;
                    echo "<td ><center>".$CantidadTotal."</center></td>";
                break;

                case 9 :
                    $Query = mysql_query("SELECT rut_cliente FROM gestion_ult_trimestre WHERE cedente IN($this->Cedentes) and fecha_gestion = '$Fecha' ");
                    $Cantidad = mysql_num_rows($Query);
                    $CantidadTotal = $Cantidad+$CantidadTotal;
                    echo "<td ><center>".$CantidadTotal."</center></td>";
                break;

                case 10 : 
                    if($Dia==""){
                        $Dia == $Fecha_Ultima;
                    }
                    else if(strlen($Dia)<2){
                        $Dia = "0".$Dia;
                    }else{}
                    if($Dia == 0){
                        echo "<th class='bg-warning'><center>".$MesNombre."-".$Ultimo."</center></th>";

                    }else{
                        echo "<th class='bg-warning'><center>".$MesNombre."-".$Dia."</center></th>";

                    }
                break;

                case 11 :
                    
                    $Query = mysql_query("SELECT Rut FROM Persona_Periodo WHERE Rut IN (SELECT rut_cliente FROM gestion_ult_trimestre WHERE cedente IN($this->Cedentes) and fecha_gestion = '$Fecha' AND Id_TipoGestion IN (1,2,5)) AND Mandante = $Mandante ");
                    $Cantidad = mysql_num_rows($Query);
                    array_push($data2, array('y' => $Dia,'a' => $Cantidad));
                    echo "<td ><center>".$Cantidad."</center></td>";
                break;

                case 12 :
                    $Query = mysql_query("SELECT Rut FROM Persona_Periodo WHERE Rut IN (SELECT rut_cliente FROM gestion_ult_trimestre WHERE cedente IN($this->Cedentes) and fecha_gestion = '$Fecha' AND Id_TipoGestion NOT IN (1,2,5)) AND NOT Rut IN (SELECT rut_cliente FROM gestion_ult_trimestre WHERE cedente IN($this->Cedentes) and fecha_gestion = '$Fecha' AND Id_TipoGestion IN (1,2,5)) AND Mandante = $Mandante");
                    $Cantidad = mysql_num_rows($Query);
                    echo "<td ><center>".$Cantidad."</center></td>";
                break;

                case 13 :
                    $Query = mysql_query("SELECT Rut FROM Persona_Periodo WHERE Rut IN (SELECT rut_cliente FROM gestion_ult_trimestre WHERE cedente IN($this->Cedentes) and fecha_gestion = '$Fecha') AND Mandante = $Mandante ");
                    $Cantidad1 = mysql_num_rows($Query);
                    echo "<td ><center>".$Cantidad1."</center></td>";
                break;
                case 14 :
                    $Query1 = mysql_query("SELECT Rut FROM Persona_Periodo WHERE Rut IN (SELECT rut_cliente FROM gestion_ult_trimestre WHERE cedente IN($this->Cedentes) and fecha_gestion = '$Fecha' AND Id_TipoGestion IN (1,2,5)) AND Mandante = $Mandante");
                    $Cantidad1 = mysql_num_rows($Query1);

                    $Query2 = mysql_query("SELECT Rut FROM Persona_Periodo WHERE Rut IN (SELECT rut_cliente FROM gestion_ult_trimestre WHERE cedente IN($this->Cedentes) and fecha_gestion = '$Fecha') AND Mandante = $Mandante ");
                    $Cantidad2 = mysql_num_rows($Query2);

                    $Cantidad = round(($Cantidad1/$Cantidad2)*100);
                    array_push($data3, array('y' => $Dia,'a' => $Cantidad));
                    echo "<td ><center>".$Cantidad." % </center></td>";
                break;

                case 15 : 
                    if($Dia==""){
                        $Dia == $Fecha_Ultima;
                    }
                    else if(strlen($Dia)<2){
                        $Dia = "0".$Dia;
                    }else{}
                    if($Dia == 0){
                        echo "<th class='bg-warning'><center>".$MesNombre."-".$Ultimo."</center></th>";

                    }else{
                        echo "<th class='bg-warning'><center>".$MesNombre."-".$Dia."</center></th>";

                    }
                break;

                case 16 :
                    $Query = mysql_query("SELECT Rut FROM Persona_Periodo WHERE Rut IN (SELECT rut_cliente FROM gestion_ult_trimestre WHERE cedente IN($this->Cedentes) and fecha_gestion = '$Fecha' AND Id_TipoGestion IN (1,2,5)) AND Mandante = $Mandante ");
                    $Cantidad = mysql_num_rows($Query);
                    $CantidadTotal = $Cantidad+$CantidadTotal;
                    array_push($data4, array('y' => $Dia,'a' => $CantidadTotal));
                    echo "<td ><center>".$CantidadTotal."</center></td>";
                break;

                case 17 :
                    $Query = mysql_query("SELECT Rut FROM Persona_Periodo WHERE Rut IN (SELECT rut_cliente FROM gestion_ult_trimestre WHERE cedente IN($this->Cedentes) and fecha_gestion = '$Fecha' AND Id_TipoGestion NOT IN (1,2,5)) AND NOT Rut IN (SELECT rut_cliente FROM gestion_ult_trimestre WHERE cedente IN($this->Cedentes) and fecha_gestion = '$Fecha' AND Id_TipoGestion IN (1,2,5)) AND Mandante = $Mandante");
                    $Cantidad = mysql_num_rows($Query);
                    $CantidadTotal = $Cantidad+$CantidadTotal;
                    echo "<td ><center>".$CantidadTotal."</center></td>";
                break;

                case 18 :
                    $Query = mysql_query("SELECT Rut FROM Persona_Periodo WHERE Rut IN (SELECT rut_cliente FROM gestion_ult_trimestre WHERE cedente IN($this->Cedentes) and fecha_gestion = '$Fecha') AND Mandante = $Mandante ");
                    $Cantidad = mysql_num_rows($Query);
                    $CantidadTotal = $Cantidad+$CantidadTotal;
                    array_push($data5, array('y' => $Dia,'a' => $CantidadTotal));
                    echo "<td ><center>".$CantidadTotal."</center></td>";
                break;

                case 19 : 
                    if($Dia==""){
                        $Dia == $Fecha_Ultima;
                    }
                    else if(strlen($Dia)<2){
                        $Dia = "0".$Dia;
                    }else{}
                    if($Dia == 0){
                        echo "<th class='bg-danger'><center>".$MesNombre."-".$Ultimo."</center></th>";

                    }else{
                        echo "<th class='bg-danger'><center>".$MesNombre."-".$Dia."</center></th>";

                    }
                break;

                case 20 : 
                    $Query = mysql_query("SELECT Rut FROM Persona_Periodo WHERE  Id_Cedente = $Cartera ");
                    $Cantidad = mysql_num_rows($Query);
                    if($Dia==""){
                        $Dia == $Fecha_Ultima;
                    }
                    else if(strlen($Dia)<2){
                        $Dia = "0".$Dia;
                    }else{}
                    if($Dia == 0){
                        echo "<th class='bg-danger'><center>".$Cantidad."</center></th>";

                    }else{
                        echo "<th class='bg-danger'><center>".$Cantidad."</center></th>";

                    }
                break;

                case 21:
                    $Query = mysql_query("SELECT Rut FROM Persona_Periodo WHERE Rut IN (SELECT rut_cliente FROM gestion_ult_trimestre WHERE cedente IN($this->Cedentes) and fecha_gestion = '$Fecha' AND Id_TipoGestion IN (1,2,5)) AND Mandante = $Mandante");
                    $Contactabilidad = mysql_num_rows($Query);
                    $ContactabilidadTotal = $ContactabilidadTotal+$Contactabilidad;

                    $QueryAsignacion = mysql_query("SELECT Rut FROM Persona_Periodo WHERE  Mandante = $Mandante ");
                    $CantidadAsignacion = mysql_num_rows($QueryAsignacion);

                    $ContactabilidadFinal = round(($ContactabilidadTotal/$CantidadAsignacion)*100);
                    echo "<td ><center>".$ContactabilidadFinal." %</center></td>";
                break;

                case 22 :
                    if($Cartera==-1){
                        $Promedio = 0;
                        $QueryMeta = mysql_query("SELECT Meta FROM mandante_cedente WHERE Id_Mandante=$Periodo and activo = 1");
                        $w=0;
                        $MetaTotal = 0;
                        while($row=mysql_fetch_array($QueryMeta)){
                            $Meta = $row[0];
                            $MetaTotal = $Meta+$MetaTotal;
                            $w++;
                        }
                        $Promedio = round($MetaTotal/$w);

                        $Query = mysql_query("SELECT Rut FROM Persona_Periodo WHERE Rut IN (SELECT rut_cliente FROM gestion_ult_trimestre WHERE cedente IN($this->Cedentes) and fecha_gestion = '$Fecha' AND Id_TipoGestion IN (1,2,5)) AND Mandante = $Mandante ");
                        $Contactabilidad = mysql_num_rows($Query);
                        $ContactabilidadTotal = $ContactabilidadTotal+$Contactabilidad;

                        $QueryAsignacion = mysql_query("SELECT Rut FROM Persona_Periodo WHERE  Mandante = $Mandante ");
                        $CantidadAsignacion = mysql_num_rows($QueryAsignacion);

                        $ContactabilidadFinal = round(($ContactabilidadTotal/$CantidadAsignacion)*100);

                        $ContactabilidadMeta = round(($ContactabilidadFinal/$Promedio)*100);
                        array_push($data, array('y' => $Dia,'a' => $ContactabilidadMeta));
                        if($ContactabilidadMeta<=5){
                        $BG = "bg-danger";
                        }
                        elseif($ContactabilidadMeta>5 && $ContactabilidadMeta<=10){
                            $BG = "bg-warning";
                        }
                        elseif($ContactabilidadMeta>10 && $ContactabilidadMeta<=20){
                            $BG = "bg-mint";
                        }
                        elseif($ContactabilidadMeta>20 && $ContactabilidadMeta<=100){
                            $BG = "bg-success";
                        }
                        echo "<td  class='$BG'><center>$ContactabilidadMeta %</center></td>";
                    }
                    else{
                        $Promedio = 0;
                        $QueryMeta = mysql_query("SELECT Meta FROM mandante_cedente WHERE Id_Mandante=$Mandante and activo = 1");
                        $w=0;
                        $MetaTotal = 0;
                        while($row=mysql_fetch_array($QueryMeta)){
                            $Meta = $row[0];
                            $MetaTotal = $Meta+$MetaTotal;
                            $w++;
                        }
                        $Promedio = round($MetaTotal/$w);

                        $Query = mysql_query("SELECT Rut FROM Persona_Periodo WHERE Rut IN (SELECT rut_cliente FROM gestion_ult_trimestre WHERE cedente IN($this->Cedentes) and fecha_gestion = '$Fecha' AND Id_TipoGestion IN (1,2,5)) AND Mandante = $Mandante");
                        $Contactabilidad = mysql_num_rows($Query);
                        $ContactabilidadTotal = $ContactabilidadTotal+$Contactabilidad;

                        $QueryAsignacion = mysql_query("SELECT Rut FROM Persona_Periodo WHERE  Mandante = $Mandante ");
                        $CantidadAsignacion = mysql_num_rows($QueryAsignacion);

                        $ContactabilidadFinal = round(($ContactabilidadTotal/$CantidadAsignacion)*100);

                        $ContactabilidadMeta = round(($ContactabilidadFinal/$Promedio)*100);
                        array_push($data, array('y' => $Dia,'a' => $ContactabilidadMeta));
                        if($ContactabilidadMeta<=5){
                        $BG = "bg-danger";
                        }
                        elseif($ContactabilidadMeta>5 && $ContactabilidadMeta<=10){
                            $BG = "bg-warning";
                        }
                        elseif($ContactabilidadMeta>10 && $ContactabilidadMeta<=20){
                            $BG = "bg-mint";
                        }
                        elseif($ContactabilidadMeta>20 && $ContactabilidadMeta<=100){
                            $BG = "bg-success";
                        }
                        echo "<td  class='$BG'><center>$ContactabilidadMeta %</center></td>";
                    }
                    
                    
                break;




                /*case 6 :
                    $BG = '';
                    $Query = mysql_query("SELECT rut_cliente FROM gestion_ult_trimestre WHERE cedente IN($this->Cedentes) and fecha_gestion = '$Fecha' AND Id_TipoGestion IN (1,2,5) ");
                    $CantidadA = mysql_num_rows($Query);

                    $QueryB = mysql_query("SELECT rut_cliente FROM gestion_ult_trimestre WHERE cedente IN($this->Cedentes) and fecha_gestion = '$Fecha'  ");
                    $CantidadB = mysql_num_rows($QueryB);
                    $Porcentaje = ($CantidadA/$CantidadB)*100;
                    if($Porcentaje<=5){
                        $BG = "bg-danger";
                    }
                    elseif($Porcentaje>5 && $Porcentaje<=10){
                        $BG = "bg-warning";
                    }
                    elseif($Porcentaje>10 && $Porcentaje<=20){
                        $BG = "bg-mint";
                    }
                    elseif($Porcentaje>20 && $Porcentaje<=100){
                        $BG = "bg-success";
                    }
                    echo "<td class='text-sm $BG'><center>".round($Porcentaje)."% </center></td>";
                    array_push($data, array('y' => $Dia,'a' => round($Porcentaje)));
                    
                break;*/

               /* case 12 :
                   
                        $Query = mysql_query("SELECT Rut FROM Persona WHERE  NOT Rut IN (SELECT rut_cliente FROM gestion_ult_trimestre WHERE fecha_gestion BETWEEN '$FechaInicio' AND  '$Fecha' and  cedente IN ($this->Cedentes)) AND FIND_IN_SET('$Periodo',Mandante) ");
                        $Cantidad = mysql_num_rows($Query);
                   
                    
                    echo "<td class='text-sm'><center>".$Cantidad."</center></td>";
                break;*/

                /*case 7 :
                    if(strlen($Dia)<2){
                            $Dia = "0".$Dia;
                        }
                        echo "<th class='text-sm bg-info'><center>".$MesNombre."-".$Dia."</center></th>";
                break;
                case 8:
                    $Query = mysql_query("SELECT rut_cliente FROM gestion_ult_trimestre WHERE cedente IN($this->Cedentes) and fecha_gestion = '$Fecha' AND Id_TipoGestion IN (1,2,5)");
                    $Cantidad = mysql_num_rows($Query);
                    $CantidadTotal = $Cantidad + $CantidadTotal;
                    echo "<td class='text-sm'><center>".$CantidadTotal."</center></td>";
                break;
                case 9:
                    $Query = mysql_query("SELECT rut_cliente FROM gestion_ult_trimestre WHERE cedente IN($this->Cedentes) and fecha_gestion = '$Fecha' AND Id_TipoGestion  NOT IN (1,2,5)");
                    $Cantidad = mysql_num_rows($Query);
                    $CantidadTotal = $Cantidad + $CantidadTotal;
                    echo "<td class='text-sm'><center>".$CantidadTotal."</center></td>";
                break;
                case 10:
                    $Query = mysql_query("SELECT rut_cliente FROM gestion_ult_trimestre WHERE cedente IN($this->Cedentes) and fecha_gestion = '$Fecha' ");
                    $Cantidad = mysql_num_rows($Query);
                    $CantidadTotal = $Cantidad + $CantidadTotal;
                    echo "<td class='text-sm'><center>".$CantidadTotal."</center></td>";
                break;
                /*case 11:
                    $Query = mysql_query("SELECT rut_cliente FROM gestion_ult_trimestre WHERE cedente IN($this->Cedentes) and fecha_gestion = '$Fecha' AND Id_TipoGestion IN (1,2,5) ");
                    $CantidadA = mysql_num_rows($Query);
                    $CantidadTotalA = $CantidadA + $CantidadTotalA;

                    $QueryB = mysql_query("SELECT rut_cliente FROM gestion_ult_trimestre WHERE cedente IN($this->Cedentes) and fecha_gestion = '$Fecha'  ");
                    $CantidadB = mysql_num_rows($QueryB);
                    $CantidadTotalB = $CantidadB + $CantidadTotalB;
                    $Porcentaje = ($CantidadTotalA/$CantidadTotalB)*100;
                    if($Porcentaje<=5){
                        $BG = "bg-danger";
                    }
                    elseif($Porcentaje>5 && $Porcentaje<=10){
                        $BG = "bg-warning";
                    }
                    elseif($Porcentaje>10 && $Porcentaje<=20){
                        $BG = "bg-mint";
                    }
                    elseif($Porcentaje>20 && $Porcentaje<=100){
                        $BG = "bg-success";
                    }
                    echo "<td class='text-sm $BG'><center>".round($Porcentaje)."% </center></td>";
                    array_push($data2, array('y' => $Dia,'a' => round($Porcentaje)));
                break;   */
            }
            

            $Dia++;
            $i++;
        }
        if($this->Encabezado==22){
            $json1 = json_encode($data);
            echo "<input type='hidden' id='json1' value='$json1'>";
        }
        else if($this->Encabezado==11){
            $json2 = json_encode($data2);
            echo "<input type='hidden' id='json2' value='$json2'>";
        }
        else if($this->Encabezado==14){
            $json3 = json_encode($data3);
            echo "<input type='hidden' id='json3' value='$json3'>";
        }
        else if($this->Encabezado==16){
            $json4 = json_encode($data4);
            echo "<input type='hidden' id='json4' value='$json4'>";
        }
        else if($this->Encabezado==18){
            $json5 = json_encode($data5);
            echo "<input type='hidden' id='json5' value='$json5'>";
        }
        /*
        elseif($this->Encabezado==11){
            $json2 = json_encode($data2);
            echo "<input type='hidden' id='json2' value='$json2'>";
        }*/
    }
}
?>
