<?php 
//ASTERISK VICIDIAL
$ConViciDial=mysql_connect('192.168.1.80','root','m9a7r5s3'); 
mysql_select_db('asterisk',$ConViciDial); 
//CONEXION A FOCO
$ConFoco=mysql_connect('192.168.1.8','root','s9q7l5.,777'); 
mysql_select_db('foco',$ConFoco); 


$QueryFonos = mysql_query("SELECT fono from Proveedores_Pago",$ConFoco);
while($row = mysql_fetch_array($QueryFonos)){
   $i=1;
   $fono = $row[0];
   $QueryFonosDial = mysql_query("SELECT number_dialed from call_log  where number_dialed = $fono LIMIT 1",$ConViciDial);
   if(mysql_num_rows($QueryFonosDial)>1){
       while($row = mysql_fetch_array($QueryFonosDial)){

            echo $fonoDial = $row[0];
            echo "<br>";
        }

   }
   else{
       echo "Fono no esta";
       echo $i+1;
   }
   
                
}


?>
