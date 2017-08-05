<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("calidad");
    QueryPHP_IncludeClasses("db");
    $CalidadClass = new Calidad();
    $db = new DB();
    
    $Array_Afirmacion = array();
    $Array_Afirmacion[0] = 1;
    $Array_Afirmacion[1] = 8;
    $Array_Afirmacion[2] = 12;

    $SqlEvaluaciones = "select
                            evaluaciones.id,
                            mandante_cedente.Id_Mandante
                        from
                            evaluaciones
                                inner join mandante_cedente on mandante_cedente.Id_Cedente = evaluaciones.Id_Cedente";
    $Evaluciones = $db->select($SqlEvaluaciones);
    $Values = array();
    foreach($Evaluciones as $Evaluacion){
        $SqlDetails = "select Nota, resumen from detalle_evaluaciones where Id_Evaluacion='".$Evaluacion["id"]."' and (resumen like 'Atenci%' or resumen like'Capacidad%' or resumen like 'Conocimiento%') order by resumen";
        $Details = $db->select($SqlDetails);
        foreach($Details as $Detail){
            $Afirmacion = "";
            switch($Detail["resumen"]){
                case 'Capacidad de Negociación':
                    $Afirmacion = $Array_Afirmacion[0];
                break;
                case 'Atención al Cliente':
                    $Afirmacion = $Array_Afirmacion[1];
                break;
                case 'Conocimiento del Producto':
                    $Afirmacion = $Array_Afirmacion[2];
                break;
            }
            $Nota_Nueva = number_format(($Detail["Nota"] * 5) / 7,2);
            //echo $Detail["resumen"]." - ".$Detail["Nota"]." - ".$Nota_Nueva;
            $ValuesText = "('".$Evaluacion["id"]."','".$Afirmacion."','".$Evaluacion["Id_Mandante"]."','".$Nota_Nueva."',0)";
            array_push($Values,$ValuesText);
        }
    }
    $ValuesImplode = implode(",",$Values);
    $Insert = "insert into respuesta_opciones_afirmaciones_calidad (Id_Evaluacion,Id_Afirmacion,Id_Mandante,Valor,Nota) values " . $ValuesImplode;
    $db->query($Insert);
?>