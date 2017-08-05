<?php include_once("../functions/Functions.php");
    QueryPHP_IncludeClasses("db");
    $db = new Db();

    $var_id = $_POST["id"];

    $query_select = "SELECT id, variable, tabla, campo, operacion FROM Variables WHERE id = ".$var_id;

    $row_select = $db->select($query_select);

    if(count($row_select) > 0){

        $row = $row_select[0];

        $is_tabla = strpos($row['campo'], ',');

        if($row['operacion'] !== ''){
            $tipo = 'operacion';
            $preview = '';
        } else if($is_tabla !== false){
            $tipo = 'tabla';
            $preview = str_replace(',', '</span><i class="fa fa-close deleteCol" style="margin-left: 10px; cursor: pointer;"></i></th><th><span>', $row['campo']);
            $preview = '<th><span>'.$preview.'</span><i class="fa fa-close deleteCol" style="margin-left: 10px; cursor: pointer;"></i></th>';
        } else {
            $tipo = 'valor';
            $preview = '';
        }

        $temp = array($row['id'],$row['variable'],$tipo,$row['tabla'],$row['campo'],$row['operacion'],$preview);

    } else {
        $temp = array('','','','','');
    }

    echo json_encode($temp);

?>