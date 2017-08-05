<?php
    class Grupos{
        
        function getGroup($idGrupo){
            $ToReturn = "";
            $db = new DB();
            $SqlGrupo = "select * from grupos where IdGrupo = '".$idGrupo."'";
            $Grupo = $db->select($SqlGrupo);
            if(count($Grupo) > 0){
                $ToReturn = $Grupo[0];
            }
            return $ToReturn;
        }
    }
?>