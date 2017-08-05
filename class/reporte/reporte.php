<?php
    class Reporte{
        public $Id_Usuario;
        public $Id_Mandante;
        public $Id_Cedente;

        function __construct(){
            $this->Id_Usuario = isset($_SESSION['id_usuario']) ? $_SESSION['id_usuario']: "";
            $this->Id_Mandante = isset($_SESSION['mandante']) ? $_SESSION['mandante'] : "";
            $this->Id_Cedente = isset($_SESSION['cedente']) ? $_SESSION['cedente']: "";
        }
        
        function getCedentes(){
            $db = new DB();
            $SqlCedentes = "select Cedente.Id_Cedente as idCedente, Cedente.Nombre_Cedente as Nombre from Cedente inner join mandante_cedente on mandante_cedente.Id_Cedente = Cedente.Id_Cedente inner join mandante on mandante_cedente.Id_Mandante = mandante.id where mandante.id='".$this->Id_Mandante."' order by Cedente.Nombre_Cedente";
            $Cedentes = $db->select($SqlCedentes);
            return $Cedentes;
        }
        function getCarteraField(){
            $db = new DB();
            $SqlCarteras = "select distinct CARTERA as Cartera from Deuda inner join mandante_cedente on mandante_cedente.Id_Cedente = Deuda.Id_Cedente where Id_Mandante='".$this->Id_Mandante."' order by CARTERA";
            $Carteras = $db->select($SqlCarteras);
            return $Carteras;
        }
        function getTramoField(){
            $db = new DB();
            $SqlTramos = "select distinct Tramo_Dias_Mora as Tramo from Deuda inner join mandante_cedente on mandante_cedente.Id_Cedente = Deuda.Id_Cedente where Id_Mandante='".$this->Id_Mandante."' order by Tramo_Dias_Mora";
            $Tramos = $db->select($SqlTramos);
            return $Tramos;
        }
        function getPeriodosMandante(){
            $db = new DB();
            $SqlPeriodos = "select * from Periodo_Mandante where Mandante='".$this->Id_Mandante."' order by id DESC LIMIT 1";
            $Periodos = $db->select($SqlPeriodos);
            return $Periodos;
        }
        function getReportData($idCedente,$idPeriodo,$Cartera,$Tramo){
            $WhereCedente = $idCedente != "" ? " and mandante_cedente.Id_Cedente='".$idCedente."' " : "";
            $WhereCartera = $Cartera != "" ? " and Deuda.CARTERA='".$Cartera."' " : "";
            $WhereTramo = $Tramo != "" ? " and Deuda.Tramo_Dias_Mora='".$Tramo."' " : "";
            $db = new DB();
            $Periodo = $this->getDatesFromPeriodoMandante($idPeriodo);
            $Status = $this->getStatusGestiones($Periodo["startDate"],$Periodo["endDate"]);
            $SqlRuts = "select 
                            rut_cliente,
                            status_name,
                            (select SUM(Monto_Mora) from Deuda inner join mandante_cedente on mandante_cedente.Id_Cedente = Deuda.Id_Cedente where Monto_Mora > 0 and mandante_cedente.Id_Mandante='".$this->Id_Mandante."' and Rut=rut_cliente ".$WhereCedente." ".$WhereCartera." ".$WhereTramo.") as SumDeuda,
                            (select count(*) from Deuda inner join mandante_cedente on mandante_cedente.Id_Cedente = Deuda.Id_Cedente where Monto_Mora > 0 and mandante_cedente.Id_Mandante='".$this->Id_Mandante."' and Rut=rut_cliente ".$WhereCedente." ".$WhereCartera." ".$WhereTramo.") as CasosDeuda,
                            (select SUM(Monto) from pagos_deudas where Rut=rut_cliente and Mandante in (select Id_Mandante from mandante_cedente where Id_Mandante='".$this->Id_Mandante."' ".$WhereCedente.") and Numero_Operacion in (select Numero_Operacion from Deuda	where Monto_Mora > 0 and Id_Cedente in (select Id_Cedente from mandante_cedente where Id_Mandante='".$this->Id_Mandante."' ".$WhereCedente.") ".$WhereCartera." ".$WhereTramo." )) as SumRecupero,
                            (select count(*) from pagos_deudas where Rut=rut_cliente and Mandante in (select Id_Mandante from mandante_cedente where Id_Mandante='".$this->Id_Mandante."' ".$WhereCedente.") and Numero_Operacion in (select Numero_Operacion from Deuda	where Monto_Mora > 0 and Id_Cedente in (select Id_Cedente from mandante_cedente where Id_Mandante='".$this->Id_Mandante."' ".$WhereCedente.") ".$WhereCartera." ".$WhereTramo." )) as CasosRecupero
                        from
                            (select
                                    gestion_ult_trimestre.rut_cliente,gestion_ult_trimestre.status_name, fechahora
                            from gestion_ult_trimestre
                                    inner join Cedentes_Listas on Cedentes_Listas.Id_Lista = gestion_ult_trimestre.cedente
                                    inner join mandante_cedente on mandante_cedente.Id_Cedente = Cedentes_Listas.Id_Cedente
                                    inner join mandante on mandante.id = mandante_cedente.Id_Mandante
                            where
                                    fecha_gestion BETWEEN '".$Periodo["startDate"]."' and '".$Periodo["endDate"]."' and
                                    mandante.id='".$this->Id_Mandante."'
                                    ".$WhereCedente."
                            GROUP BY gestion_ult_trimestre.rut_cliente,gestion_ult_trimestre.status_name
                            ORDER BY fechahora desc) tb1
                        GROUP BY rut_cliente
                        HAVING CasosDeuda > 0";
            if($WhereCartera != ""){
                //echo $SqlRuts;
            }
            $Ruts = $db->select($SqlRuts);
            //print_r($Status);
            foreach($Ruts as $Rut){
                $ArrayTmp = array();
                $Status[strtoupper($Rut["status_name"])]["SumDeuda"] = $Status[strtoupper($Rut["status_name"])]["SumDeuda"] + $Rut["SumDeuda"];
                $Status[strtoupper($Rut["status_name"])]["CasosDeuda"] = $Status[strtoupper($Rut["status_name"])]["CasosDeuda"] + $Rut["CasosDeuda"];
                $Status[strtoupper($Rut["status_name"])]["SumRecupero"] = $Status[strtoupper($Rut["status_name"])]["SumRecupero"] + $Rut["SumRecupero"];
                $Status[strtoupper($Rut["status_name"])]["CasosRecupero"] = $Status[strtoupper($Rut["status_name"])]["CasosRecupero"] + $Rut["CasosRecupero"];
            }
            $ToReturn = array();
            $Cont = 1;
            $CasosDeuda = 0;
            $SumDeuda = 0;
            $SumRecupero = 0;
            $CasosRecupero = 0;
            $ToReturn[0]["Cont"] = $Cont;
            $ToReturn[0]["Estatus"] = "Sin Gestion";
            $ToReturn[0]["CasosDeuda"] = 0;
            $ToReturn[0]["SumDeuda"] = 0;
            $ToReturn[0]["SumRecupero"] = 0;
            $ToReturn[0]["CasosRecupero"] = 0;
            $ToReturn[0]["MontoRecuperoAvg"] = 0;
            $ToReturn[0]["CasosRecuperoAvg"] = 0;
            foreach($Status as $Tmp){
                $Cont++;
                $CasosDeuda += $Tmp["CasosDeuda"];
                $SumDeuda += $Tmp["SumDeuda"];
                $SumRecupero += $Tmp["SumRecupero"];
                $CasosRecupero += $Tmp["CasosRecupero"];
                $arrayTmp = array();
                $arrayTmp["Cont"] = $Cont;
                $arrayTmp["Estatus"] = $Tmp["Status"];
                $arrayTmp["CasosDeuda"] = $Tmp["CasosDeuda"];
                $arrayTmp["SumDeuda"] = $Tmp["SumDeuda"];
                $arrayTmp["SumRecupero"] = $Tmp["SumRecupero"];
                $arrayTmp["CasosRecupero"] = $Tmp["CasosRecupero"];
                $arrayTmp["MontoRecuperoAvg"] = $arrayTmp["SumDeuda"] > 0 ? round(($arrayTmp["SumRecupero"] / $arrayTmp["SumDeuda"]) * 100,2) : 0;
                $arrayTmp["CasosRecuperoAvg"] = $arrayTmp["CasosDeuda"] > 0 ? round(($arrayTmp["CasosRecupero"] / $arrayTmp["CasosDeuda"]) * 100,2) : 0;
                array_push($ToReturn,$arrayTmp);
            }
            $TotalGestion = $this->getTotalGestion($idCedente,$Cartera,$Tramo);
            $ToReturn[0]["CasosDeuda"] = $TotalGestion["CasosDeuda"] - $CasosDeuda;
            $ToReturn[0]["SumDeuda"] = $TotalGestion["SumaDeuda"] - $SumDeuda;
            $ToReturn[0]["SumRecupero"] = $TotalGestion["SumaRecupero"] - $SumRecupero;
            $ToReturn[0]["CasosRecupero"] = $TotalGestion["CasosRecupero"] - $CasosRecupero;
            $ToReturn[0]["MontoRecuperoAvg"] = $ToReturn[0]["SumDeuda"] > 0 ? round(($ToReturn[0]["SumRecupero"] / $ToReturn[0]["SumDeuda"]) * 100,2) : 0;
            $ToReturn[0]["CasosRecuperoAvg"] = $ToReturn[0]["CasosDeuda"] > 0 ? round(($ToReturn[0]["CasosRecupero"] / $ToReturn[0]["CasosDeuda"]) * 100,2) : 0;
            return $ToReturn;
        }
        function getStatusGestiones($startDate,$endDate){
            $db = new DB();
            $ToReturn = array();
            $SqlStatus = "select
                                    gestion_ult_trimestre.status_name as Name
                            from gestion_ult_trimestre
                                    inner join Cedentes_Listas on Cedentes_Listas.Id_Lista = gestion_ult_trimestre.cedente
                                    inner join mandante_cedente on mandante_cedente.Id_Cedente = Cedentes_Listas.Id_Cedente
                                    inner join mandante on mandante.id = mandante_cedente.Id_Mandante
                            where
                                    fecha_gestion BETWEEN '".$startDate."' and '".$endDate."' and
                                    mandante.id='".$this->Id_Mandante."'
                                    GROUP BY gestion_ult_trimestre.status_name
                            ORDER BY gestion_ult_trimestre.status_name";
            $Status = $db->select($SqlStatus);
            foreach($Status as $Name){
                $ToReturn[strtoupper($Name["Name"])]["Status"] = $Name["Name"];
                $ToReturn[strtoupper($Name["Name"])]["SumDeuda"] = 0;
                $ToReturn[strtoupper($Name["Name"])]["CasosDeuda"] = 0;
                $ToReturn[strtoupper($Name["Name"])]["SumRecupero"] = 0;
                $ToReturn[strtoupper($Name["Name"])]["CasosRecupero"] = 0;
            }
            return $ToReturn;
        }
        function getDatesFromPeriodoMandante($idPeriodo){
            $ToReturn = array();
            $db = new DB();
            $SqlPeriodo = "select * from Periodo_Mandante where Mandante='".$this->Id_Mandante."' and ID='".$idPeriodo."'";
            $Periodo = $db->select($SqlPeriodo);
            $ToReturn["startDate"] = $Periodo[0]["Fecha_Inicio"];
            $ToReturn["endDate"] = $Periodo[0]["Fecha_Termino"] == "0000-00-00" ? date("Y-m-d") : $Periodo[0]["Fecha_Termino"];
            return $ToReturn;
        }
        function getTotalGestion($idCedente,$Cartera,$Tramo){
            $WhereCedente = $idCedente != "" ? " and mandante_cedente.Id_Cedente='".$idCedente."' " : "";
            $WhereCartera = $Cartera != "" ? " and Deuda.CARTERA='".$Cartera."' " : "";
            $WhereTramo = $Tramo != "" ? " and Deuda.Tramo_Dias_Mora='".$Tramo."' " : "";
            $ToReturn = array();
            $db = new DB();
            $SqlTotalDeuda = "select SUM(Monto_Mora) as Total, count(*) as Cantidad from Deuda where Monto_Mora > 0 and Id_Cedente in (select Id_Cedente from mandante_cedente where Id_Mandante='".$this->Id_Mandante."' ".$WhereCedente." ".$WhereCartera." ".$WhereTramo.")";
            $TotalDeuda = $db->select($SqlTotalDeuda);
            $TotalSumaDeuda = $TotalDeuda[0]["Total"];
            $TotalCasosDeuda = $TotalDeuda[0]["Cantidad"];

            $SqlTotalRecupero = "select SUM(Monto) as Total, count(*) as Cantidad from pagos_deudas where Mandante in (select Id_Mandante from mandante_cedente where Id_Mandante='".$this->Id_Mandante."' ".$WhereCedente.") and Numero_Operacion in (select Numero_Operacion from Deuda where Monto_Mora > 0 and Id_Cedente in (select Id_Cedente from mandante_cedente where Id_Mandante='".$this->Id_Mandante."' ".$WhereCedente.") ".$WhereCartera." ".$WhereTramo." )";
            $TotalRecupero = $db->select($SqlTotalRecupero);
            $TotalSumaRecupero = $TotalRecupero[0]["Total"];
            $TotalCasosRecupero = $TotalRecupero[0]["Cantidad"];
            $ToReturn["SumaDeuda"] = $TotalSumaDeuda;
            $ToReturn["CasosDeuda"] = $TotalCasosDeuda;
            $ToReturn["SumaRecupero"] = $TotalSumaRecupero;
            $ToReturn["CasosRecupero"] = $TotalCasosRecupero;

            return $ToReturn;
        }
        function getCantidadTipoGestion($datos){
            $db = new DB();
            $segmento = $datos['segmento'];
            // aquiiiiiiiiiiiiiiiiiii
            $sqlCantidadGestiones = "SELECT count(g.Id_TipoGestion) as cantidad , g.Id_TipoGestion, t.Nombre  FROM gestion_ult_trimestre g , Tipo_Contacto t WHERE g.rut_cliente IN (Select Rut FROM Deuda WHERE Id_Cedente = 47 and Segmento = '".$segmento."') AND g.cedente = 47  and t.Id_TipoContacto = g.Id_TipoGestion group by g.Id_TipoGestion";
            $totalCantidadGestiones = $db->select($sqlCantidadGestiones);
            return $totalCantidadGestiones;
        }
        function getCantidadGestionNivel1($datos){
            $db = new DB();
            $idCedente = $datos['idCedente'];
            $fechaInicio = $datos['fechaInicio'];
            $fechaFin = $datos['fechaFin'];
            $idTipoGestion = $datos['idTipoGestion'];
            $sqlCantidadGestiones = "SELECT n.Respuesta_N1, COUNT(n.Respuesta_N1) as cantidad, n.Id FROM gestion_ult_semestre_respaldo g, Nivel1 n WHERE g.cedente = '".$idCedente."' and g.fecha_gestion BETWEEN '".$fechaInicio."' and '".$fechaFin."' and g.Id_TipoGestion = '".$idTipoGestion."' and n.Id = g.resultado GROUP BY n.Respuesta_N1";
            $totalCantidadGestiones = $db->select($sqlCantidadGestiones);
            return $totalCantidadGestiones;
        }
        function getCantidadGestionNivel2($datos){
            $db = new DB();
            $idCedente = $datos['idCedente'];
            $fechaInicio = $datos['fechaInicio'];
            $fechaFin = $datos['fechaFin'];
            $idTipoGestion = $datos['idTipoGestion'];
            $idNivel1 = $datos['idNivel1'];
            $sqlCantidadGestiones = "SELECT n2.Respuesta_N2, COUNT(n2.Respuesta_N2) as cantidad, n2.id FROM gestion_ult_semestre_respaldo g, Nivel2 n2 WHERE g.cedente =  '".$idCedente."' AND g.fecha_gestion BETWEEN  '".$fechaInicio."' AND  '".$fechaFin."' AND g.resultado =  '".$idNivel1."' AND g.Id_TipoGestion =  '".$idTipoGestion."' AND g.resultado_n2 = n2.id GROUP BY n2.Respuesta_N2";
            $totalCantidadGestiones = $db->select($sqlCantidadGestiones);
            return $totalCantidadGestiones;
        }
        function getCantidadGestionNivel3($datos){
            $db = new DB();
            $idCedente = $datos['idCedente'];
            $fechaInicio = $datos['fechaInicio'];
            $fechaFin = $datos['fechaFin'];
            $idTipoGestion = $datos['idTipoGestion'];
            $idNivel2 = $datos['idNivel2'];
            $sqlCantidadGestiones = "SELECT n3.Respuesta_N3, n3.id, COUNT(n3.Respuesta_N3) as cantidad, n3.id FROM gestion_ult_semestre_respaldo g, Nivel3 n3 WHERE g.cedente =  '".$idCedente."' AND g.fecha_gestion BETWEEN  '".$fechaInicio."' AND  '".$fechaFin."' AND g.resultado_n2 =  '".$idNivel2."' AND g.Id_TipoGestion =  '".$idTipoGestion."' AND g.resultado_n3 = n3.id GROUP BY n3.Respuesta_N3"; 
            $totalCantidadGestiones = $db->select($sqlCantidadGestiones);
            return $totalCantidadGestiones;
        }
        function getDatosUltimaCarga(){
            $db = new DB();            
            $sql = "SELECT * FROM Historico_Carga WHERE Id_Cedente = '".$this->Id_Cedente."' ORDER BY fecha DESC LIMIT 1"; 
            $registros = $db->select($sql);
            return $registros;
        }
        function getDatosDeudaCarga(){
            $db = new DB();            
            $sql = "SELECT p.Nombre_Completo, d.* FROM Deuda d, Persona p WHERE d.Id_Cedente = '".$this->Id_Cedente."' and p.Rut = d.Rut"; 
            $deudas = $db->select($sql);
            return $deudas;
        }
        function getDeudaMes(){
            $db = new DB();            
            $sql = "select YEAR(Fecha_Vencimiento) as year, MONTH(Fecha_Vencimiento) as month, SUM(Monto_Mora) as monto from Deuda where Id_Cedente='".$this->Id_Cedente."' group by YEAR(Fecha_Vencimiento), MONTH(Fecha_Vencimiento) order by YEAR(Fecha_Vencimiento) DESC, MONTH(Fecha_Vencimiento) DESC limit 14"; 
            $deudasMes = $db->select($sql);
            return $deudasMes;
        }
        function getTotalPorSegmento(){
            $db = new DB();
            switch ($this->Id_Cedente) {
            case 47:
                $campo = "Segmento";
            break;
            case 107:
                $campo = "TipoMora";
            break;
            case 215:
                $campo = "Tramo_Morosidad";
            break;
            }
            $sql = "SELECT SUM(Monto_Mora) as total, ".$campo." FROM Deuda WHERE Id_Cedente = '".$this->Id_Cedente."' GROUP BY ".$campo."";
            $totalMontoSegmento = $db->select($sql);

            $totalesArray = array();
            foreach($totalMontoSegmento as $registro){
            $Array = array();
            $Array['total'] = $registro["total"]; 
            $Array['segmento'] = $registro[$campo];
            array_push($totalesArray,$Array);
    }
            return $totalesArray;
        }
        function getCasosPorSegmento($datos){
            $db = new DB();
            $segmento = $datos['segmento'];
            switch ($this->Id_Cedente) {
            case 47:
                $campo = "Segmento";
            break;
            case 107:
                $campo = "TipoMora";
            break;
            case 215:
                $campo = "Tramo_Morosidad";
            break;
            }
            $sql = "SELECT p.Nombre_Completo, SUM( d.Monto_Mora ) AS total, COUNT(d.Numero_Factura) AS cantidadFactura, CASE WHEN SUM( d.Monto_Mora ) > 5000 then 'Monto incidente' else 'Monto no incidente' end as marca FROM Deuda d, Persona p WHERE d.Id_Cedente = '".$this->Id_Cedente."' AND d.".$campo." = '".$segmento."' AND p.Rut = d.Rut GROUP BY d.Rut ORDER BY total DESC";
            $totalCasosSegmento = $db->select($sql);
            return $totalCasosSegmento;
        }
        function getTotalCompromiso($datos){
            $db = new DB();

            switch ($this->Id_Cedente) {
            case 47:
                $campo = "Tramo_Dias_Mora";
            break;
            case 107:
                $campo = "TipoMora";
            break;
            case 215:
                $campo = "Tramo_Morosidad";
            break;
            }


            $segmento = $datos['segmento'];
            $sql = "select  SUM(monto_comp) as montoCompromiso from (select * from (select * from gestion_ult_trimestre WHERE    rut_cliente IN (SELECT Rut FROM Deuda WHERE ".$campo." = '".$segmento."' AND Id_Cedente = '".$this->Id_Cedente."' ) AND cedente = '".$this->Id_Cedente."' AND Id_TipoGestion = '5' ORDER BY fechahora DESC) tb1 GROUP BY rut_cliente) tb2";
            $sql2 = "SELECT SUM(Monto_Mora) AS totalDeuda FROM Deuda WHERE Id_Cedente = '".$this->Id_Cedente."' and ".$campo." = '".$segmento."'";

           $sql3 = "select SUM(Monto_Mora) AS montoMora, Nombre, Id_TipoContacto from Deuda inner join (select * from (select Deuda.Rut, gestion_ult_trimestre.fechahora, Tipo_Contacto.Nombre, Tipo_Contacto.Id_TipoContacto from gestion_ult_trimestre inner join Deuda on Deuda.Id_Cedente = gestion_ult_trimestre.cedente and Deuda.Rut = gestion_ult_trimestre.rut_cliente inner join Tipo_Contacto on Tipo_Contacto.Id_TipoContacto = gestion_ult_trimestre.Id_TipoGestion where Deuda.Id_Cedente='".$this->Id_Cedente."' and Deuda.".$campo."='".$segmento."' order by gestion_ult_trimestre.fechahora DESC) tb1 group by Rut) tb2 on tb2.Rut = Deuda.Rut group by Nombre";

           $totalMontoCompromisoSegmento = $db->select($sql3);
           $totalDeudaMontoSegmento = $db->select($sql2);

            $montosArray = array();
            $totalCasosGestionadosPorSegmento = 0;
            foreach($totalMontoCompromisoSegmento as $registro){
            $Array = array();
            $Array['nombre'] = utf8_encode($registro["Nombre"]);
            $Array['monto'] = $registro["montoMora"]; 
            $totalMontoGestionadosPorSegmento = $totalMontoGestionadosPorSegmento+$registro["montoMora"];
            $Array['porcentaje'] = round(((100 * $registro["montoMora"])/$totalDeudaMontoSegmento[0]["totalDeuda"]), 2);
            //$Array['idTipoContacto'] = $registro["Id_TipoGestion"];
            array_push($montosArray,$Array);
            }

            

            $totalMontoSinGestion =  $totalDeudaMontoSegmento[0]["totalDeuda"] - $totalMontoGestionadosPorSegmento;

            $Array['nombre'] = "Sin Gestion";
            $Array['monto'] = $totalMontoSinGestion; 
            $Array['porcentaje'] = round(((100 * $totalMontoSinGestion)/$totalDeudaMontoSegmento[0]["totalDeuda"]), 2);
            array_push($montosArray,$Array);



           /* $totalMontoCompromisoSegmento = $db->select($sql);
            $totalDeudaMontoSegmento = $db->select($sql2);

            $array = array();
            $array['montoCompromiso'] = $totalMontoCompromisoSegmento[0]["montoCompromiso"];
            $array['montoDeuda'] = $totalDeudaMontoSegmento[0]["totalDeuda"]; */
           
            return $montosArray;
        }

        function getTotalCasosCompromiso($datos){

            $db = new DB();

            switch ($this->Id_Cedente) {
            case 47:
                $campo = "Tramo_Dias_Mora";
            break;
            case 107:
                $campo = "TipoMora";
            break;
            case 215:
                $campo = "Tramo_Morosidad";
            break;
            }


            $segmento = $datos['segmento'];
            $sql = "select count(*) as cantidad from (SELECT Rut FROM Deuda WHERE Id_Cedente = '".$this->Id_Cedente."' and ".$campo." = '".$segmento."' Group by Rut) tb1";
            $sql2 = "SELECT rut_cliente FROM gestion_ult_trimestre WHERE rut_cliente IN ( SELECT Rut FROM Deuda WHERE Id_Cedente  = '".$this->Id_Cedente."' AND ".$campo." ='".$segmento."' ) AND cedente ='".$this->Id_Cedente."' AND Id_TipoGestion =5 GROUP BY rut_cliente";

            $sql3 = "select Nombre, Cantidad from (select Id_TipoGestion as Gestion,count(*) as Cantidad from (select * from (select DISTINCT gestion_ult_trimestre.* from gestion_ult_trimestre INNER JOIN Deuda on Deuda.Id_Cedente = gestion_ult_trimestre.cedente and Deuda.Rut = gestion_ult_trimestre.rut_cliente where Deuda.Id_Cedente='".$this->Id_Cedente."' and Deuda.".$campo." = '".$segmento."'  order by fechahora DESC) tbOrdenadoFecha group by rut_cliente) tbAgrupadoRut group by Id_TipoGestion) tbAgrupadoTipoGestion INNER JOIN Tipo_Contacto on Tipo_Contacto.Id_TipoContacto = tbAgrupadoTipoGestion.Gestion";
            $totalCasosPorTipoContacto = $db->select($sql3);
            $gestionArray = array();
            $totalCasosGestionadosPorSegmento = 0;

            $totalCasosSegmento = $db->select($sql); // con esto resto y saco casos sin gestionar    

            foreach($totalCasosPorTipoContacto as $registro){
            $Array = array();
            $Array['nombre'] = utf8_encode($registro["Nombre"]);
            $Array['cantidad'] = $registro["Cantidad"]; 
            $totalCasosGestionadosPorSegmento = $totalCasosGestionadosPorSegmento+$registro["Cantidad"];

            $Array['porcentaje'] = round(((100 * $registro["Cantidad"])/$totalCasosSegmento[0]["cantidad"]), 2);  
            //$Array['idTipoContacto'] = $registro["Id_TipoGestion"];
            array_push($gestionArray,$Array);
            }

            $totalCasosSegmento = $db->select($sql); // con esto resto y saco casos sin gestionar           
            $totalCasosSinGestion = $totalCasosSegmento[0]["cantidad"] - $totalCasosGestionadosPorSegmento;

            $Array['nombre'] = "Sin Gestion";
            $Array['cantidad'] = $totalCasosSinGestion; 
            $Array['porcentaje'] = round(((100 * $totalCasosSinGestion)/$totalCasosSegmento[0]["cantidad"]), 2);  
            array_push($gestionArray,$Array);

           

            /*$totalCasosSegmento = $db->select($sql); // con esto resto y saco casos sin gestionar
            $totalCasosGestion = $db->select($sql2); // total casos por tipo gestion 
            $totalCasosGestion = count($totalCasosGestion);

            $array = array();
            $array['casostotal'] = $totalCasosSegmento[0]["cantidad"] - $totalCasosGestion;
            $array['casosgestion'] = $totalCasosGestion; */
           
            return $gestionArray;
        }

        function getMontoCompromisoRangoFecha($datos){
            $db = new DB();
            $fechaInicio = $datos['inicio'];
            $fechaFin = $datos['fin'];
            // sin factura
            $sql = "select YEAR(UltGest.fec_compromiso), MONTH(UltGest.fec_compromiso), DAY(UltGest.fec_compromiso) as dias, SUM(UltGest.monto_comp) as monto from (select * from (select * from gestion_ult_trimestre inner join Tipo_Contacto on Tipo_Contacto.Id_TipoContacto = gestion_ult_trimestre.Id_TipoGestion where fec_compromiso BETWEEN '".$fechaInicio."' and '".$fechaFin."' and Tipo_Contacto.Id_TipoContacto = '5' and gestion_ult_trimestre.cedente in (select GROUP_CONCAT(Lista_Vicidial) from mandante_cedente where Id_Cedente='".$_SESSION['cedente']."') order by fechahora DESC) AllGest GROUP BY AllGest.rut_cliente order by AllGest.fechahora DESC) UltGest group by YEAR(UltGest.fec_compromiso), MONTH(UltGest.fec_compromiso), DAY(UltGest.fec_compromiso)";
            // muestra el total en pesos de compromisos de todos los dias de un rango de fecha
            // enviar dos campos llamados dias - monto
            // concatenar los dias para que se vea asi Dia 1
            $resultado = $db->select($sql);
            $montoDiasArray = array();  

            foreach($resultado as $filas){
                $Array = array();
                $Array['dias'] = $filas["dias"];
                $Array['monto'] = $filas["monto"];
                array_push($montoDiasArray,$Array);
            }
            return $montoDiasArray;
        }

        function getMontoCompromisoPorMes($datos){
            $db = new DB();
            $mes = $datos['mes'];
            $fechaInicio = "2017-".$mes."-01";
            $fechaFin = "2017-".$mes."-31";
            // sin factura
            $sql = "select YEAR(UltGest.fec_compromiso), MONTH(UltGest.fec_compromiso), DAY(UltGest.fec_compromiso) as dias, SUM(UltGest.monto_comp) as monto from (select * from (select * from gestion_ult_trimestre inner join Tipo_Contacto on Tipo_Contacto.Id_TipoContacto = gestion_ult_trimestre.Id_TipoGestion where fec_compromiso BETWEEN '".$fechaInicio."' and '".$fechaFin."' and Tipo_Contacto.Id_TipoContacto = '5' and gestion_ult_trimestre.cedente in (select GROUP_CONCAT(Lista_Vicidial) from mandante_cedente where Id_Cedente='".$_SESSION['cedente']."') order by fechahora DESC) AllGest GROUP BY AllGest.rut_cliente order by AllGest.fechahora DESC) UltGest group by YEAR(UltGest.fec_compromiso), MONTH(UltGest.fec_compromiso), DAY(UltGest.fec_compromiso)";
            // muestra el total en pesos de compromisos de todos los dias de un mes
            $resultado = $db->select($sql);
            $montoDiasArray = array();  

            foreach($resultado as $filas){
                $Array = array();
                $Array['dias'] = $filas["dias"];
                $Array['monto'] = $filas["monto"];
                array_push($montoDiasArray,$Array);
            }
            return $montoDiasArray;
        }

        function arraySemanas($fechaInicio, $fechaFin, $semana){
            $Array = array();
            $Array['fechaInicio'] = $fechaInicio;
            $Array['fechaFin'] = $fechaFin;
            $Array['semana'] = $semana;
            return $Array;
        }

        function getMontoCompromisoMesSemana($datos){
            $db = new DB();
            $mes = $datos['mes'];
            $semanasArray = array();  
            // semana 1
            $fechaInicio = "2017-".$mes."-01";
            $fechaFin = "2017-".$mes."-08";
            $semana = $this->arraySemanas($fechaInicio, $fechaFin, 'Semana 1');
            array_push($semanasArray,$semana);
            // semana 2
            $fechaInicio = "2017-".$mes."-09";
            $fechaFin = "2017-".$mes."-16";
            $semana = $this->arraySemanas($fechaInicio, $fechaFin, 'Semana 2');
            array_push($semanasArray,$semana);
            // semana 3
            $fechaInicio = "2017-".$mes."-17";
            $fechaFin = "2017-".$mes."-24";
            $semana = $this->arraySemanas($fechaInicio, $fechaFin, 'Semana 3');
            array_push($semanasArray,$semana);
            // semana 4
            $fechaInicio = "2017-".$mes."-25";
            $fechaFin = "2017-".$mes."-31";
            $semana = $this->arraySemanas($fechaInicio, $fechaFin, 'Semana 4');
            array_push($semanasArray,$semana);

            $montoSemanasArray = array(); 

            foreach($semanasArray as $filas){
                $sql = "select YEAR(UltGest.fec_compromiso), MONTH(UltGest.fec_compromiso), DAY(UltGest.fec_compromiso) as dias, SUM(UltGest.monto_comp) as monto from (select * from (select * from gestion_ult_trimestre inner join Tipo_Contacto on Tipo_Contacto.Id_TipoContacto = gestion_ult_trimestre.Id_TipoGestion where fec_compromiso BETWEEN '".$filas['fechaInicio']."' and '".$filas['fechaFin']."' and Tipo_Contacto.Id_TipoContacto = '5' and    gestion_ult_trimestre.cedente in (select GROUP_CONCAT(Lista_Vicidial) from mandante_cedente where Id_Cedente='".$_SESSION['cedente']."') order by fechahora DESC) AllGest GROUP BY AllGest.rut_cliente order by AllGest.fechahora DESC) UltGest group by YEAR(UltGest.fec_compromiso), MONTH(UltGest.fec_compromiso), DAY(UltGest.fec_compromiso)";
                // muestra el total en pesos de compromisos de todos los dias de un mes
                $resultado = $db->select($sql);
                $acumulador = 0;
                foreach($resultado as $resu){
                    // acumulo el monto de la semana
                    $acumulador = $acumulador + $resu["monto"];
                }
                $Array = array();
                $Array['semanas'] = $filas['semana'];
                $Array['monto'] = $acumulador;
                array_push($montoSemanasArray,$Array);
            } 

            return $montoSemanasArray;
        }

        function getMontoFacturasVencidas(){
            $db = new DB();          

            $sql = "SELECT CASE WHEN dias <= -1 and dias >= -30  THEN '30 días' WHEN dias <= -31 and dias >= -60 THEN '30 - 60 días' WHEN dias <= -61 and dias >= -90 THEN '60 - 90 días' WHEN dias <= -91 THEN 'mas de 90 días' ELSE 'noVencidas' END AS tramo, SUM(Monto_Mora) as monto FROM (SELECT DATEDIFF(Fecha_Vencimiento, NOW()) AS dias, Monto_Mora FROM Deuda WHERE Id_Cedente = '".$_SESSION['cedente']."' and Fecha_Vencimiento != '') TABLADIASTRAMO GROUP BY TRAMO";

            $resultado = $db->select($sql); 

            $montoVencidas = array();    
            foreach($resultado as $resu){           
                $Array = array();
                $tramo = utf8_encode($resu['tramo']);
                if ($tramo != 'noVencidas'){
                    $Array['tramo'] = $resu['tramo'];
                    $Array['monto'] =  $resu['monto'];
                    array_push($montoVencidas,$Array);
                }
            }           

            $arrayNombreTramo = array('30 días', '30 - 60 días', '60 - 90 días', 'mas de 90 días');

            for($i=0;$i<count($arrayNombreTramo);$i++) {
                //echo $arrayNombreTramo[$i];
                $bandera = 0;
                foreach($montoVencidas as $mon){           
                    
                    if ($mon['tramo'] == $arrayNombreTramo[$i]){
                        $bandera = 1; // 1 si esta
                    }

                    
                }
                if ($bandera == 0){
                    // si entra aca no esta
                    $Array = array();
                    $Array['tramo'] = $arrayNombreTramo[$i];
                    $Array['monto'] =  0;
                    array_push($montoVencidas,$Array);

                }
            }   
            
            return $montoVencidas;
        }

        function getMontoFacturasNoVencidas(){
            $db = new DB();          

            $sql = "SELECT CASE WHEN dias >= 1 and dias <= 30  THEN '30 días' WHEN dias >= 31 and dias <= 60 THEN '30 - 60 días' WHEN dias >= 61 and dias <= 90 THEN '60 - 90 días' WHEN dias >= 91 THEN 'mas de 90 días' ELSE 'noVencidas' END AS tramo, SUM(Monto_Mora) as monto FROM (SELECT DATEDIFF(Fecha_Vencimiento, NOW()) AS dias, Monto_Mora FROM Deuda WHERE Id_Cedente = '".$_SESSION['cedente']."' and Fecha_Vencimiento != '') TABLADIASTRAMO GROUP BY TRAMO";

            $resultado = $db->select($sql); 

            $montoVencidas = array();    
            foreach($resultado as $resu){           
                $Array = array();
                $tramo = utf8_encode($resu['tramo']);
                if ($tramo != 'noVencidas'){
                    $Array['tramo'] = $resu['tramo'];
                    $Array['monto'] =  $resu['monto'];
                    array_push($montoVencidas,$Array);
                }
            }           

            $arrayNombreTramo = array('30 días', '30 - 60 días', '60 - 90 días', 'mas de 90 días');

            for($i=0;$i<count($arrayNombreTramo);$i++) {
                //echo $arrayNombreTramo[$i];
                $bandera = 0;
                foreach($montoVencidas as $mon){           
                    
                    if ($mon['tramo'] == $arrayNombreTramo[$i]){
                        $bandera = 1; // 1 si esta
                    }

                    
                }
                if ($bandera == 0){
                    // si entra aca no esta
                    $Array = array();
                    $Array['tramo'] = $arrayNombreTramo[$i];
                    $Array['monto'] =  0;
                    array_push($montoVencidas,$Array);

                }
            }


            
            return $montoVencidas;
        }
        function getPagosMes($Mes){
            $db = new DB();
            $Year = date("Y",strtotime($Mes));
            $Month = date("m",strtotime($Mes));
            $ToReturn = array();
            $SqlPagos = "select
                            YEAR(Fecha_Pago) as Year,MONTH(Fecha_Pago) as Month,DAY(Fecha_Pago) as Day, SUM(Monto) as Monto
                        from
                            pagos_deudas
                        where
                            Id_Cedente='".$_SESSION['cedente']."' and
                            YEAR(Fecha_Pago)='".$Year."' AND
                            MONTH(Fecha_Pago)='".$Month."'
                        group by YEAR(Fecha_Pago),MONTH(Fecha_Pago),DAY(Fecha_Pago)
                        order by YEAR(Fecha_Pago),MONTH(Fecha_Pago),DAY(Fecha_Pago)";
            $Pagos = $db->select($SqlPagos);
            foreach($Pagos as $Pago){
                $ArrayTmp = array();
                $ArrayTmp["Dia"] = $Pago["Day"];
                $ArrayTmp["Monto"] = $Pago["Monto"];
                array_push($ToReturn,$ArrayTmp);
            }
            return $ToReturn;
        }
        function getMesesRecupero(){
            $db = new DB();
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
            $ToReturn = array();
            $SqlMeses = "select
                            YEAR(Fecha_Pago) as Year,MONTH(Fecha_Pago) as Month
                        from
                            pagos_deudas
                        where
                            Id_Cedente='".$_SESSION['cedente']."'
                        group by YEAR(Fecha_Pago),MONTH(Fecha_Pago)
                        order by YEAR(Fecha_Pago),MONTH(Fecha_Pago)";
            $Meses = $db->select($SqlMeses);
            foreach($Meses as $Mes){
                $ArrayTmp = array();
                $ArrayTmp["Year"] = $Mes["Year"];
                $ArrayTmp["Month"] = $Mes["Month"];
                $ArrayTmp["MonthText"] = $Months[$Mes["Month"]];
                array_push($ToReturn,$ArrayTmp);
            }
            return $ToReturn;
        }
    }
?>