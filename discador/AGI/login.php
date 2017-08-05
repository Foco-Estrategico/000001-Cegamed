<?php
  include ("../phpagi/phpagi-asmanager.php");

  $asm = new AGI_AsteriskManager();

  $asm->connect("127.0.0.1","myasterisk","adminaia123");
//  $resultado = $asm->command("reload");
  $resultado = $asm->command("manager show users");
  print_r($resultado);
  $asm->disconnect();
  sleep(3);


?>
