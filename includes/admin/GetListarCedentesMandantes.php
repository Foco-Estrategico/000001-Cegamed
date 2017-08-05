<?php
    include_once("../../includes/functions/Functions.php");
    include_once("../../class/global/cedente.php");
    QueryPHP_IncludeClasses("db");
    $Cedente = new Cedente();
    //echo json_encode($Cedente->getCedentesMandante($_POST['idMandante']));
    $cedentesMandantes = array();
    $Cedentes = $Cedente->getCedentesMandante($_POST['idMandante']);
    foreach($Cedentes as $cedente){
      $Array = array();
      $Array['NombreCedente'] = utf8_encode($cedente["NombreCedente"]);
      $Array['idCedente'] = $cedente["idCedente"];
      array_push($cedentesMandantes,$Array);
    }
    
    echo json_encode($cedentesMandantes);    

?>