<?php
  include ("../phpagi/phpagi-asmanager.php");

  $asm = new AGI_AsteriskManager();

  $asm->connect("127.0.0.1","myasterisk","adminaia123");
  $resultado = $asm->command("sip show peers");
  //print_r($resultado);
  echo $resultado["data"];
  $asm->disconnect();
  sleep(3);


?>

