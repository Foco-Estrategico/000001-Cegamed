<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("calidad");
    QueryPHP_IncludeClasses("db");
    $CalidadClass = new Calidad();

    $Competencia = $_POST['Competencia'];
    $Dimensiones = $CalidadClass->selectDimensionesByCompetencia($Competencia);
    $ArrayDimensiones = array();
    foreach($Dimensiones as $Dimension){
        $ArrayTmpDimensiones = array();
        $ArrayTmpDimensiones["idDimension"] = $Dimension["idDimension"];
        $ArrayTmpDimensiones["Ponderacion"] = $Dimension["Ponderacion"];
        $ArrayTmpDimensiones["Preguntas"] = array();
        $Afirmaciones = $CalidadClass->selectAfirmacionesByDimension($Dimension["idDimension"]);   
        $Preguntas = array();
        foreach($Afirmaciones as $Afirmacion){
            $ArrayTmp = array();
            $ArrayTmp["Afirmacion"] = utf8_encode($Afirmacion["Afirmacion"]);
            $ArrayTmp["idAfirmacion"] = $Afirmacion["idAfirmacion"];
            $ArrayTmp["Ponderacion"] = $Afirmacion["Ponderacion"];
            $ArrayTmp["Opciones"] = array();
            $Opciones = $CalidadClass->selectOpcionesAfirmacionesByAfirmacion($Afirmacion["idAfirmacion"]);
            foreach($Opciones as $Opcion){
                $ArrayTmpOpciones = array();
                $ArrayTmpOpciones["idOpcion"] = $Opcion["idOpcion"];
                $ArrayTmpOpciones["Opcion"] = utf8_encode($Opcion["Opcion"]);
                $ArrayTmpOpciones["Valor"] = $Opcion["Valor"];
                array_push($ArrayTmp["Opciones"],$ArrayTmpOpciones);
            }
            array_push($Preguntas,$ArrayTmp);
        }
        array_push($ArrayTmpDimensiones["Preguntas"],$Preguntas);
        array_push($ArrayDimensiones,$ArrayTmpDimensiones);
    }
    $ToReturn = array();
    $ToReturn["NotaMaxima"] = $CalidadClass->NotaMaximaEvaluacion;
    $ToReturn["Dimensiones"] = $ArrayDimensiones;
    echo json_encode($ToReturn);
?>