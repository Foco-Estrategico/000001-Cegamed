<?php
    $Id_Grabacion = $_GET['id'];
    include_once("../includes/functions/Functions.php");
    Main_IncludeClasses("calidad");
    Main_IncludeClasses("personal");
    Main_IncludeClasses("global");
    Main_IncludeClasses("db");

    $CalidadClass = new Calidad();
    $PersonalClass = new Personal();
    $CedenteClass = new Cedente();
    $CalidadClass->Id_Grabacion = $Id_Grabacion;
    $Evaluation = $CalidadClass->getEvaluationByUser();
    $Evaluation = $Evaluation[0];
    $PersonalClass->id = $Evaluation["Id_Personal"];
    $Personal = $PersonalClass->getPersonal();
    $Personal = $Personal[0];
    $Competencias = $CalidadClass->getNotesGroupedByCompetencias($Evaluation["id"],$Personal["Id_Personal"]);
    foreach($Competencias as $key => $Competencia){
        $idCompetencia = $Competencia["idCompetencia"];
        $Aspectos = $CalidadClass->getAspectosIndividualesByEvaluacion($Evaluation["id"],"Corregir",$idCompetencia);
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
    <page footer="page" backtop="70px" backleft="10px" backbottom="50px">
        <page_header>
            <div class="header">
                <div class="Head" style="width: 100%;">
                    <table>
                        <tr>
                            <td style="width: 250px;"><img style="height: 65px;" src="../img/cobranding.png" /></td>
                            <td style="width: 760px;background-color: orange;padding: 15px 20px; border-radius: 5px;">
                                <h3 style="margin: 0; text-align: center;">EVALUACION DE GESTIÓN: <?php echo $NombreCierre; ?>: <?php echo strtoupper($Personal["Nombre"]); ?></h3>
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
                <td style="background-color: #996600; padding: 20px 10px; color: #FFFFFF; font-weight: bold;">Fecha de Evaluación</td>
                <td style="padding: 20px 10px; color: #000000; font-weight: bold;"><?php echo date("d/m/Y",strtotime($Evaluation["Fecha_Evaluacion"])); ?></td>
            </tr>
            <tr>
                <td style="background-color: #996600; padding: 20px 10px; color: #FFFFFF; font-weight: bold;">Gestión</td>
                <td style="padding: 20px 10px; color: #000000; font-weight: bold;"><?php echo $Evaluation["Nombre_Grabacion"]; ?></td>
            </tr>
        </table>
        <div class="BreakLine" style="width: 100%; height: 50px;"></div>
        <table cellspacing="0">
            <tr>
                <th align=center style="width: 330px;background-color: #996600; color: #FFFFFF; padding: 10px 0px;">Competencia</th>
                <th align=center style="width: 100px;background-color: #996600; color: #FFFFFF; padding: 10px 0px;">Nota</th>
                <th align=center style="width: 660px;background-color: #996600; color: #FFFFFF; padding: 10px 0px;">Aspectos a Corregir</th>
            </tr>
            <tbody>
                
                <?php
                    $SumNota = 0;
                    foreach($Competencias as $Competencia){
                        $NombreCompetencia = utf8_encode($Competencia["Competencia"]);
                        $Aspectos = utf8_encode($Competencia["Aspectos"]);
                        $Nota = $Competencia["NotaPonderada"];
                        $SumNota += $Nota;
                        ?>
                            <tr>
                                <td style="padding: 10px 0;"><?php echo $NombreCompetencia; ?></td>
                                <td align=center style="padding: 10px 0;"><?php echo $Nota; ?></td>
                                <td style="padding: 10px 0;"><?php echo $Aspectos; ?></td>
                            </tr>
                        <?php
                    }
                ?>
                <tr style="padding: 10px 0px; background-color: #996600; color: #FFFFFF">
                    <td style="padding: 10px 5px; font-weight: bold;" align=right>TOTAL:</td>
                    <td style="padding: 10px 5px; font-weight: bold;" align=center><?php echo number_format($SumNota, 2, '.', ''); ?></td>
                    <td></td>
                </tr>
            </tbody>
        </table>
        <div class="BreakLine" style="width: 100%; height: 50px;"></div>
        <table style="margin-left: 310px;">
            <tr>
                <td width=200 align=center style="padding: 10px 5px 20px 5px">_________________________________</td>
                <td width=200 align=center style="padding: 10px 5px 20px 5px">_________________________________</td>
            </tr>
            <tr>
                <td width=200 align=center style="padding: 10px 5px 20px 5px">Ejecutivo</td>
                <td width=200 align=center style="padding: 10px 5px 20px 5px">Calidad</td>
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