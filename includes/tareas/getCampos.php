<?php
    include("../../includes/functions/Functions.php");
    require '../../class/estrategia/config_tablas.php';

    QueryPHP_IncludeClasses("db");
    $TipoCampo = $_POST['TipoCampo'];
    $ToReturn = "";
    switch($TipoCampo){
        case "1":
            $Tabla = $_POST['Tabla'];
            $configTablasClass = new configTablas();
            $Campos = $configTablasClass->getFiltrar_camposCedente($Tabla,$_SESSION['cedente']);
            $Campos = array_sort($Campos,"columna");
            foreach($Campos as $Campo){
                $ToReturn .= "<option value='".$Campo["id_columna"]."'>".$Campo["columna"]."</option>";
            }
        break;
        case "2":
            $ToReturn .= "<option value='fono_2'>Fono 2</option>";
            $ToReturn .= "<option value='color_fono_2'>Color Fono 2</option>";
            $ToReturn .= "<option value='fono_3'>Fono 3</option>";
            $ToReturn .= "<option value='color_fono_3'>Color Fono 3</option>";
            $ToReturn .= "<option value='fono_especial'>Fono Especial</option>";
            $ToReturn .= "<option value='color_fono_especial'>Color Fono Especial</option>";
            $ToReturn .= "<option value='gestion_fono_2'>Gestión Fono 2</option>";
            $ToReturn .= "<option value='gestion_fono_3'>Gestión Fono 3</option>";
            $ToReturn .= "<option value='gestion_fono_especial'>Gestión Fono Especial</option>";
            $ToReturn .= "<option value='mejor_gestion_fecha'>Fecha Mejor Gestión</option>";
            $ToReturn .= "<option value='mejor_gestion_texto'>Mejor Gestión</option>";
            $ToReturn .= "<option value='ultima_gestion_fecha'>Fecha Ultima Gestión</option>";
            $ToReturn .= "<option value='ultima_gestion_observacion'>Observación Ultima Gestión</option>";
            $ToReturn .= "<option value='ultima_gestion_texto'>Ultima Gestión</option>";
            $ToReturn .= "<option value='ultimo_compromiso_fecha'>Fecha Ultimo Compromiso</option>";
            $ToReturn .= "<option value='ultimo_compromiso_observacion'>Observación Ultimo Compromiso</option>";
            $ToReturn .= "<option value='ultimo_compromiso_texto'>Ultimo Compromiso</option>";
            $ToReturn .= "<option value='ultima_gestion_usuario'>Usuario Ultima Gestion</option>";
            $ToReturn .= "<option value='cantidad_gestiones'>Cantidad de Gestiones</option>";
        break;
    }
    
    echo $ToReturn;
?>