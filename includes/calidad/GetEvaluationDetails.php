<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("calidad");
    QueryPHP_IncludeClasses("db");
    $CalidadClass = new Calidad();
    $idEvaluacion = $_POST['Id_Evaluacion'];
    $Competencias = $CalidadClass->getEvaluationTemplate();
    $ArrayCompetencias = array();
    foreach($Competencias as $key => $Competencia){
        $idCompetencia = $Competencia["ID"];
        $ArrayRespuestas = array();
        $Opciones = $CalidadClass->getRespuestasAfirmacionesByCompetenciaAndEvaluacion($idCompetencia,$idEvaluacion);
        $SumNota = 0;
        foreach($Opciones as $Opcion){
            $Valor = $Opcion["Valor"];
            $idAfirmacion = $Opcion["idAfirmacion"];
            $Nota = $Opcion["Nota"];
            $SumNota += $Nota;
            $Return = $idAfirmacion."|".$Nota."|".$Valor;
            array_push($ArrayRespuestas,$Return);
        }
        $Competencias[$key]["Nota"] = number_format($SumNota, 2, '.', '');
        $ArrayCompetencias[$idCompetencia] = $ArrayRespuestas;
    }
    $ToReturn = array();
    $ToReturn["Competencias"] = $Competencias;
    $ToReturn["SelectedOptions"] = $ArrayCompetencias;
    echo json_encode($ToReturn);
?>