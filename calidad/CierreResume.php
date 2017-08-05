<?php
    $Id_Cierre = $_GET['id'];
    include_once("../includes/functions/Functions.php");
    Main_IncludeClasses("calidad");
    Main_IncludeClasses("personal");
    Main_IncludeClasses("global");
    Main_IncludeClasses("db");

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

    $CalidadClass = new Calidad();
    $PersonalClass = new Personal();
    $CedenteClass = new Cedente();
    $CalidadClass->Id_Cierre = $Id_Cierre;
    $Cierre = $CalidadClass->getCierre();
    $Cierre = count($Cierre) > 0 ? $Cierre[0] : "";
    $PersonalClass->id = $Cierre["Id_Personal"];
    $Personal = $PersonalClass->getPersonal();
    $Personal = $Personal[0];
    $CierreEjecutivo = $CalidadClass->getCierreEjecutivos_InformeCierre($Cierre["fecha"],$Cierre["Id_Personal"]);
    $CierreEjecutivo = $CierreEjecutivo[0];
    $Evaluaciones = $CalidadClass->getEvaluacionesFromCierre($Cierre["Id_Evaluaciones"],$Cierre["fecha"],$Cierre["tipo_cierre"],$Personal["Id_Personal"]);
    $Competencias = $CalidadClass->getNotesGroupedByCompetencias($Cierre["Id_Evaluaciones"],$Cierre["Id_Personal"]);
    $SumNota = 0;
    foreach($Evaluaciones as $Evaluacion){
        $SumNota += floatval($Evaluacion["Nota"]);
    }
    $NotaPeriodo = number_format($SumNota / count($Evaluaciones),2);
    $NotaPromedio = number_format($CalidadClass->NotaPromedioByPersonal($Personal["Id_Personal"],$_SESSION['mandante'],$Cierre["fecha"]),2);
    $Nota = 0;
    switch($Cierre["tipo_cierre"]){
        case '0': //Semanal
            $Nota = $NotaPeriodo;
        break;
        case '1': //Mensual
            $Nota = $NotaPromedio;
        break;
    }
    $Perfil = $CalidadClass->getPerfilEjecutivoByNota($Nota);
    $AspectosCorregir = $CalidadClass->getAspectosPromediosByEvaluacion($Cierre["Id_Evaluaciones"],"Corregir");
    $AspectosFortalecer = $CalidadClass->getAspectosPromediosByEvaluacion($Cierre["Id_Evaluaciones"],"Fortalecer");
    $CantRowsAspectos = 0;
    if(count($AspectosCorregir) >= count($AspectosFortalecer)){
        $CantRowsAspectos = count($AspectosCorregir);
    }else{
        $CantRowsAspectos = count($AspectosFortalecer);
    }
    $Competencias = $CalidadClass->getEvaluationTemplate();
    $ArrayCompetencias = array();
    $ArrayMesesCompetencias = array();
    foreach($Competencias as $Competencia){
        $Tag = $Competencia["Tag"];
        $ArrayTmp = array();
        $ArrayTmp["Nota"] = 0;
        $ArrayCompetencias[$Tag] = array();
        $ArrayCompetencias[$Tag]["Nota"] = 0;
        $ArrayCompetencias[$Tag]["Competencia"] = $Competencia["Nombre"];
    }
    $NotasCompetencias = $CalidadClass->getNotasByEvaluationsAndDateGroupedByCompetencia($Personal["Id_Personal"],$Cierre["fecha"]);
    foreach($NotasCompetencias as $NotaCompetencia){
        $Year = $NotaCompetencia["Year"];
        $Month = $Months[$NotaCompetencia["Month"]];
        $Competencia = $NotaCompetencia["Competencia"];
        $Nota = $NotaCompetencia["Nota"];
        if(!isset($ArrayMesesCompetencias[$Month."/".$Year])){
            $ArrayMesesCompetencias[$Month."/".$Year] = array();
            $ArrayMesesCompetencias[$Month."/".$Year] = $ArrayCompetencias;
        }
        $ArrayMesesCompetencias[$Month."/".$Year][$Competencia]["Nota"] = $Nota;
    }
    $NombreCierre = $Cierre["tipo_cierre"] == 0 ? "SEMANAL" : "MENSUAL";
    $idEvaluaciones = $Cierre["Id_Evaluaciones"];
    $Competencias = $CalidadClass->getNotesGroupedByCompetencias($idEvaluaciones,$Personal["Id_Personal"]);
    foreach($Competencias as $key => $Competencia){
        $idCompetencia = $Competencia["idCompetencia"];
        $Aspectos = $CalidadClass->getAspectosPromediosByEvaluacion($idEvaluaciones,"Corregir",$idCompetencia);
        $Cont = 1;
        if(count($Aspectos) > 0){
            foreach($Aspectos as $Aspecto){
                $Competencias[$key]["Aspectos"] .= $Cont.". ".$Aspecto["Aspecto"]."<br>";
                $Cont++;
            }
        }else{
            $Competencias[$key]["Aspectos"] = "";
        }
    }
    ob_start();
?>
    <style>
        .header{
            padding-left: 10px;
        }
    </style>
    <page footer="page" backtop="70px" backleft="10px" backbottom="50px">
        <page_header>
            <div class="header">
                <div class="Head" style="width: 100%;">
                    <table>
                        <tr>
                            <td style="width: 250px;"><img style="height: 65px;" src="../img/cobranding.png" /></td>
                            <td style="width: 760px;background-color: orange;padding: 15px 20px; border-radius: 5px;">
                                <h3 style="margin: 0; text-align: center;">INFORME DE CALIDAD <?php echo $NombreCierre; ?>: <?php echo strtoupper($Personal["Nombre"]); ?></h3>
                                <h4 style="margin: 0; text-align: center;">Mandante: <?php echo $CedenteClass->getMandanteName($_SESSION['mandante']); ?></h4>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </page_header>
        <div class="BreakLine" style="width: 100%; height: 50px;"></div>
        <table>
            <tr>
                <td>
                    <table>
                        <tr>
                            <td style="width: 100px; text-align: center;background-color: #996600; padding: 10px 10px; color: #FFFFFF; font-weight: bold;">Período</td>
                            <td style="width: 100px; text-align: center;background-color: #996600; padding: 10px 10px; color: #FFFFFF; font-weight: bold;"><?php echo date("M Y",strtotime($Cierre["fecha"])); ?></td>
                        </tr>
                        <tr>
                            <td style="padding: 10px 10px; color: #000000; font-weight: bold;">Fecha de Emisión</td>
                            <td style="text-align: center;padding: 10px 10px; color: #000000; font-weight: bold;"><?php echo date("d/m/Y"); ?></td>
                        </tr>
                        <tr>
                            <td style="padding: 10px 10px; color: #000000; font-weight: bold;">Nota Promedio</td>
                            <td style="text-align: center;padding: 10px 10px; color: #000000; font-weight: bold;"><?php echo $NotaPromedio; ?></td>
                        </tr>
                        <tr>
                            <td style="padding: 10px 10px; color: #000000; font-weight: bold;">Nota del Período</td>
                            <td style="text-align: center;padding: 10px 10px; color: #000000; font-weight: bold;"><?php echo $NotaPeriodo; ?></td>
                        </tr>
                        <tr>
                            <td style="padding: 10px 10px; color: #000000; font-weight: bold;">Tendencia</td>
                            <?php
                                if($NotaPeriodo >= $NotaPromedio){
                                    ?>
                                        <td style="text-align: center;color: #000000; font-weight: bold;"><div class="Up" style="margin-left: -15px;width: 0;height: 0;border-left: 15px solid transparent;border-right: 15px solid transparent;border-bottom: 15px solid green;"></div></td>
                                    <?php
                                }else{
                                    ?>
                                        <td style="text-align: center;color: #000000; font-weight: bold;"><div class="Up" style="margin-left: -15px;width: 0;height: 0;border-left: 15px solid transparent;border-right: 15px solid transparent;border-top: 15px solid red;"></div></td>
                                    <?php
                                }
                            ?>
                        </tr>
                    </table>
                </td>
                <td>
                    <div class="Perfil" style="font-size: 14px;margin:0 20px;border-radius: 15px 50px 30px;height: 200px; width: 300px; background-color:#FFCC66;">
                        <div style="text-align: center; font-weight: bold;font-size: 16px;margin: 10px 0;" class="Title">Perfil del Ejecutivo</div>
                        <div style="text-align: center;font-weight: bold;font-size: 20px;margin: 10px 0;" class="Tipo"><?php echo utf8_encode($Perfil["nombre"]); ?></div>
                        <div style="text-align: center; padding: 0 10px;" class="Descripcion"><?php echo utf8_encode($Perfil["descripcion"]); ?></div>
                    </div>
                </td>
                <td>
                    <table>
                        <tr>
                            <th></th>
                            <?php
                                $Cont = 1;
                                foreach($ArrayCompetencias as $Tag => $Competencia){
                                    $NombreCompetencia = $Competencia["Competencia"];
                                    $NombreCompetencia = str_replace(" ","<br>",$NombreCompetencia);
                                    switch($Cont){
                                        case '1':
                                            $Color = "#FF6600";
                                        break;
                                        case '2':
                                            $Color = "#CC9900";
                                        break;
                                        case '3':
                                            $Color = "#FFCC99";
                                        break;
                                        default:
                                            $Color = "#FF6600";
                                            $Cont = 1;
                                        break;
                                    }
                                    ?>
                                        <th style="background-color: <?php echo $Color; ?>; text-align: center; padding: 10px 10px; font-size: 12px;"><?php echo $NombreCompetencia; ?></th>
                                    <?php
                                    $Cont++;
                                }
                            ?>
                            <th style="background-color: #996600; text-align: center; padding: 10px 20px;">TOTAL</th>
                        </tr>
                        <?php
                            foreach($ArrayMesesCompetencias as $Mes => $NotaCompetencia){
                                $Mes = explode("/",$Mes);
                                $Mes = $Mes[0];
                                ?>
                                    <tr>
                                        <td style="background-color: #D9D9D9; padding: 10px 20px;"><?php echo $Mes; ?></td>
                                        <?php
                                            $Cont = 1;
                                            $SumNota = 0;
                                            foreach($NotaCompetencia as $Nota){
                                                $SumNota += $Nota["Nota"];
                                                switch($Cont){
                                                    case '1':
                                                        $Color = "#FF6600";
                                                    break;
                                                    case '2':
                                                        $Color = "#CC9900";
                                                    break;
                                                    case '3':
                                                        $Color = "#FFCC99";
                                                    break;
                                                    default:
                                                        $Color = "#FF6600";
                                                        $Cont = 1;
                                                    break;
                                                }
                                                ?>
                                                    <td width=50 style="background-color: <?php echo $Color; ?>; padding 10px 20px; text-align: center;"><?php echo $Nota["Nota"]; ?></td>
                                                <?php
                                                $Cont++;
                                            }
                                            $SumNota = number_format($SumNota / count($NotaCompetencia),2);
                                        ?>
                                        <td width=50 style="background-color: #996600; padding 10px 20px; text-align: center;" ><?php echo $SumNota; ?></td>
                                    </tr>
                                <?php
                            }
                        ?>
                    </table>
                </td>
            </tr>
        </table>
        <div class="BreakLine" style="width: 100%; height: 50px;"></div>
        <?php
            switch($Cierre["tipo_cierre"]){
                case '0': //Semanal
                    ?>
                    <table cellspacing="0">
                        <tr>
                            <th align=center style="width: 330px;background-color: #996600; color: #FFFFFF; padding: 10px 0px;">Competencia</th>
                            <th align=center style="width: 770px;background-color: #996600; color: #FFFFFF; padding: 10px 0px;">Aspectos a Corregir</th>
                        </tr>
                        <tbody>
                            
                            <?php
                                $SumNota = 0;
                                foreach($Competencias as $Competencia){
                                    $NombreCompetencia = utf8_encode($Competencia["Competencia"]);
                                    $Aspectos = utf8_encode($Competencia["Aspectos"]);
                                    $Nota = $Competencia["Nota"];
                                    $SumNota += $Nota;
                                    ?>
                                        <tr>
                                            <td style="padding: 10px 0;"><?php echo $NombreCompetencia; ?></td>
                                            <td style="padding: 10px 0;"><?php echo $Aspectos; ?></td>
                                        </tr>
                                    <?php
                                }
                                $SumNota = $SumNota / count($Competencias);
                            ?>
                        </tbody>
                    </table>
                    <?php
                break;
                case '1': //Mensual
                    ?>  
                        <table>
                            <tr>
                                <td style="background-color: #996600; color: #FFFFFF; text-align: center; font-weight: bold; padding: 10px 0;">Aspectos a Corregir</td>
                                <td style="background-color: #996600; color: #FFFFFF; text-align: center; font-weight: bold; padding: 10px 0;">Aspectos a Fortalecer</td>
                            </tr>
                            <?php
                                for($i=0;$i<=$CantRowsAspectos;$i++){
                                    $Corregir = isset($AspectosCorregir[$i]["Aspecto"]) ? ($i + 1).". ".$AspectosCorregir[$i]["Aspecto"] : "";
                                    $Fortalecer = isset($AspectosFortalecer[$i]["Aspecto"]) ? ($i + 1).". ".$AspectosFortalecer[$i]["Aspecto"] : "";
                                    ?>
                                        <tr>
                                            <td width=545><?php echo $Corregir; ?></td>
                                            <td width=545><?php echo $Fortalecer; ?></td>
                                        </tr>
                                    <?php
                                }
                            ?>
                        </table>
                    <?php
                break;
            }
        ?>
        <div class="BreakLine" style="width: 100%; height: 50px;"></div>    
        <table cellspacing="0">
            <tr>
                <th align=center width=420 style=" background-color: #996600; color: #FFFFFF; padding: 10px 0px;">Grabación</th>
                <th align=center width=170 style=" background-color: #996600; color: #FFFFFF; padding: 10px 0px;">Fecha Grabación</th>
                <th align=center width=170 style=" background-color: #996600; color: #FFFFFF; padding: 10px 0px;">Cedente</th>
                <th align=center width=170 style=" background-color: #996600; color: #FFFFFF; padding: 10px 0px;">Fecha Evaluación</th>
                <th align=center width=170 style=" background-color: #996600; color: #FFFFFF; padding: 10px 0px;">Nota</th>
            </tr>
            <tbody>
                
                <?php
                    $SumNota = 0;
                    foreach($Evaluaciones as $Evaluacion){
                    ?>
                        <tr style="padding: 10px 0px; background-color: #f9f9f9;">
                            <td style="padding: 10px 5px;"><?php echo $Evaluacion["Nombre_Grabacion"]; ?></td>
                            <td style="padding: 10px 5px;" align=center><?php echo $Evaluacion["Fecha_Grabacion"]; ?></td>
                            <td style="padding: 10px 5px;" align=center><?php echo $Evaluacion["Cedente"]; ?></td>
                            <td style="padding: 10px 5px;" align=center><?php echo $Evaluacion["Fecha_Evaluacion"]; ?></td>
                            <td style="padding: 10px 5px;" align=center><?php echo $Evaluacion["Nota"]; ?></td>
                        </tr>
                    <?php
                        $SumNota += floatval($Evaluacion["Nota"]);
                    }
                    $NotaAverage = $SumNota / count($Evaluaciones);
                ?>
                <tr style="padding: 10px 0px; background-color: #996600; color: #FFFFFF">
                    <td style="padding: 10px 5px;" align=right></td>
                    <td style="padding: 10px 5px; font-weight: bold;" align=center></td>
                    <td style="padding: 10px 5px; font-weight: bold;" align=center></td>
                    <td style="padding: 10px 5px; font-weight: bold;" align=center>TOTAL:</td>
                    <td style="padding: 10px 5px;" align=center><?php echo number_format($NotaAverage, 2, '.', '') ?></td>
                </tr>
            </tbody>
        </table>
        <table>
            <tr>
                <td width=500>
                    <div class="Compromiso" style="border-radius: 25px 5px 25px 5px;border: 6px solid #ff6600;padding: 10px 0;">
                        <div style="text-align: center; font-weight: bold;font-size: 16px;" class="Title">
                            Compromiso de mejora continua
                        </div>
                        <div style="text-align: center;margin: 10px 0;" class="Title">
                            Yo <span style="font-weight: bold;"><?php echo strtoupper($Personal["Nombre"]); ?></span> doy constancia de haber recibido la retroalimentación correspondiente al mes de <span style="font-weight: bold;"><?php echo $Months[intval(date("m",strtotime($Cierre["fecha"])))]; ?></span> y me comprometo a realizar mi capacitación individualizada y ponerla en práctica en mi gestión.
                        </div>
                    </div>
                </td>
                <td width=500>
                    <table style="margin-left: 50px;margin-top: 120px;">
                        <tr>
                            <td width=200 align=center style="padding: 10px 5px 20px 5px">_________________________________</td>
                            <td width=200 align=center style="padding: 10px 5px 20px 5px">_________________________________</td>
                        </tr>
                        <tr>
                            <td width=200 align=center style="padding: 10px 5px 20px 5px">Ejecutivo</td>
                            <td width=200 align=center style="padding: 10px 5px 20px 5px">Calidad</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </page>
<?php
    $content = ob_get_clean();

	// convert in PDF
    include("../includes/html2pdf/class/html2pdf.class.php");
    include("../includes/html2pdf/class/exception.class.php");
    include("../includes/html2pdf/class/locale.class.php");
    include("../includes/html2pdf/class/myPdf.class.php");
    include("../includes/html2pdf/class/parsingHtml.class.php");
    include("../includes/html2pdf/class/parsingCss.class.php");
    try
    {
        $html2pdf = new HTML2PDF('L','A4', 'es', true, 'UTF-8', array(0,0,0,0));
      	//$html2pdf->setModeDebug();
        $html2pdf->setDefaultFont('Arial');
        $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
        $html2pdf->Output('pdf.pdf');
    }
    catch(HTML2PDF_exception $e) {
        echo $e;
        exit;
    }
?>