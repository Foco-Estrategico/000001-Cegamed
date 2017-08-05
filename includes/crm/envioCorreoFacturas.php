<?php
    ob_start();
    include("../../includes/functions/Functions.php");
    require("../../includes/email/PHPMailer-master/class.phpmailer.php");
	require("../../includes/email/PHPMailer-master/class.smtp.php"); 
    QueryPHP_IncludeClasses("db");
    QueryPHP_IncludeClasses("email");
    include("../../class/crm/crm.php");
    $crm = new crm();
    $db = new DB();
    $envio = new Email();
    $opcionesEnvio = new opciones();
    $Correos = $_POST["Correos"];
    //$Correos = array("jonathanurbina92@gmail.com");
    $Facturas = isset($_POST["Facturas"]) ? $_POST["Facturas"] : array();
    $Rut = $_POST['Rut'];
    $Template = $opcionesEnvio->getTemplateFactura();
    $ToReturn = "";
    if($Template["result"]){
        $html = $Template["Template"];
        $nomTemplate = $Template["nombre"];

        $query_ve = "SELECT variable FROM Variables where id_cedente='".$_SESSION["cedente"]."'";
        $variables_existentes = $db->select($query_ve);
        $Variables = array();
        if(count($variables_existentes) > 0){
            foreach($variables_existentes as $var_e){
                $var = $var_e['variable'];
                $uso = strpos($html, '['.$var.']');
                if($uso !== false){
                    array_push($Variables,$var);
                }
            }
        }

        $info = array();
        $adjuntos = array();

        
        foreach($Correos as $Correo){
            $info[$Correo] = array();
            $adjuntos[$Correo] = array();
            foreach ($Variables as $var){
                $info[$Correo][$var] = $envio->get_var_value($Rut,$var,$_SESSION["cedente"]);
            }
            $info[$Correo]["Rut"] = $Rut;
            $adjuntos[$Correo] = $Facturas;
        }
        $info['adjuntos'] = array();
        switch($_SESSION['tipoSistema']){
            case "1":
                $info['adjuntos'] = $adjuntos;
            break;
        }
        $info['variables'] = $Variables;

        $envio_result = $envio->SendMail($html,"Factura",$Correos, $info,$_SESSION["cedente"]);
        if($envio_result){
            $crm->guardarGestionCorreo($Correos,$Facturas,$Rut,$nomTemplate);            
            $ToReturn = "1";            
        }else{
            $ToReturn = "0";
        }

        /* print_r($Variables);
        print_r($Correos);
        print_r($Facturas);
        print_r($info); */
    }else{
        $ToReturn = "2";
    }
    ob_end_clean();
    echo $ToReturn;
    //Salida:
        //2 => No existe template tipo factura
        //1 => Correo Enviado
        //0 => Fallo al enviar correo
?>