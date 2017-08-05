<?php
require_once ('phpagi-asmanager.php');
$manager_ip = "127.0.0.1";
$username = "lponce";
$secret = "lponce";


$manager = new AGI_AsteriskManager();
$manager->connect($manager_ip,$username,$secret);
if($manager){
   $result = $manager->command('sip show users');
   print_r($result);

}else{
   echo "no ok";
}
?>
