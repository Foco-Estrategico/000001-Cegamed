<?php
    if (!isset($_SESSION)){
        session_start();
    }
    function include_all_php($folder){
        foreach ((array)glob("{$folder}/*.php") as $filename)
        {
            if($filename != ""){
                include $filename;   
            }
        }
    }
    function Main_IncludeClasses($folder){
        include_all_php("../class/".$folder);
    }
    function QueryPHP_IncludeClasses($folder){
        include_all_php("../../class/".$folder);
    }
    function Prints_IncludeClasses($folder){
        include_all_php("../../../class/".$folder);
    }
    function array_sort($array, $on, $order=SORT_ASC){
        $new_array = array();
        $sortable_array = array();

        if (count($array) > 0) {
            foreach ($array as $k => $v) {
                if (is_array($v)) {
                    foreach ($v as $k2 => $v2) {
                        if ($k2 == $on) {
                            $sortable_array[$k] = $v2;
                        }
                    }
                } else {
                    $sortable_array[$k] = $v;
                }
            }

            switch ($order) {
                case SORT_ASC:
                    asort($sortable_array);
                break;
                case SORT_DESC:
                    arsort($sortable_array);
                break;
            }

            foreach ($sortable_array as $k => $v) {
                $new_array[$k] = $array[$k];
            }
        }

        return $new_array;
    }
    function unlinkRecursive($dir, $deleteRootToo){
        if(!$dh = @opendir($dir)){
            return;
        }
        while (false !== ($obj = readdir($dh))){
            if($obj == '.' || $obj == '..'){
                continue;
            }
            if (!@unlink($dir . '/' . $obj)){
                unlinkRecursive($dir.'/'.$obj, true);
            }
        }
        closedir($dh);
        if ($deleteRootToo){
            @rmdir($dir);
        }
        return;
    }
    function Depurador($Codigo,$Fono,$Rut,$ImportFonos = true){
		$ContarCodigo = strlen($Codigo);
		$Fono = ereg_replace("[^0-9]", "", $Fono);
		$Largo = strlen($Fono);
		$Fail = 0;
		$array = array();

		switch ($Largo) {
			case 11:
				$Fono = substr($Fono,2,strlen($Fono));
				$Fail = 1;
			break;
			case 9:
				$Fail = 1;
			break;
			case 8:
				$Consulta = substr($Fono,0,2);
				$CodigoArea = 0;
				$ConsultaCodigoArea = mysql_query("SELECT Codigo FROM Codigo_Area WHERE Codigo=$Consulta LIMIT 1");
				while($row = mysql_fetch_array($ConsultaCodigoArea)){
					$CodigoArea = $row[0];
				}
				if($Consulta==$CodigoArea && $Codigo==''){
					if($CodigoArea == $Codigo){
						$part2 = substr($Fono, 2, 10);
						$Fono = $Consulta."2".$part2;
						$Fail = 1;
					}else{
						$part2 = substr($Fono, 2, 10);
						$Fono1  = $Consulta."2".$part2;
						$Fono2  = "9".$Fono;
						$FonoArray = array();
						array_push($FonoArray,$Fono1);
						array_push($FonoArray,$Fono2);
						$Fail = 1;
						$contar = count($FonoArray);
						$i = 0;
						while($i<$contar){
							$Fono = $FonoArray[$i];
							$i++;
						}
					}
				} else{
					$PrimerDigito = substr($Fono, 0, 1);
					if($PrimerDigito>=4){
						$Fono = "9".$Fono;
						$Fail = 1;
					}
				else{
						$Fono = "2".$Fono;
						$Fail = 1;
					}  
				}
			break;
			case 7:
				if($Codigo){
					$Fono = $Codigo.$Fono;
					$Fail = 1;
				}
			break;
			case 6:
				if($Codigo){
					if($ContarCodigo==2){
						$Fono  = $Codigo."2".$Fono;
						$Fail = 1;
					}elseif($ContarCodigo==1){
						$Fono = "222".$Fono;
						$Fail = 1;
					}else{
						$Fail = 0;
					}
				}
				else
				{
					$Fail = 0;
				}
			break;
			default:
				$Fail = 0;
			break;
		}
		$ToReturn = $Fail;
		if($ImportFonos){
			if($Fail==0){
				if(is_array($Fono)){
					$array[0] ="";
					for ($i=0; $i < count($Fono); $i++) {
						$array[0].= "('".$Rut."','".$Fono[$i]."','".date("Y-m-d")."','".date("Y-m-d")."'),";
					}
				}else{
					$array[0] = "('".$Rut."','".$Fono."','".date("Y-m-d")."','".date("Y-m-d")."'),";
				}
			}else{
				if(is_array($Fono)){
					for ($i=0; $i < count($Fono); $i++) {
						$array[0] ="";
						if (stripos($Fono[$i], 'NULL') === 0) {
							$array[0].= "('".$Rut."','".$Fono[$i]."','".date("Y-m-d")."','".date("Y-m-d")."'),";
						}else{
							$array[1] = "('".$Rut."','".$Fono[$i].",'".date("Y-m-d")."','".date("Y-m-d")."'','".date("Y-m-d")."','".date("Y-m-d")."'),";
						}
					}
				}else{
					if (stripos($Fono, 'NULL') === 0) {
						$array[0] = "('".$Rut."','".$Fono."','".date("Y-m-d")."','".date("Y-m-d")."'),";
					}else{
						$array[1] = "('".$Rut."','".$Fono."','".date("Y-m-d")."','".date("Y-m-d")."'),";
					}
				}
			}
			$ToReturn = $array;
		}
		return $ToReturn;
	}
	function utf8_ArrayConverter($array){
		array_walk_recursive($array, function(&$item, $key){
			if(!mb_detect_encoding($item, 'utf-8', true)){
				$item = utf8_encode($item);
			}
		});
		return $array;
	}
?>