<?php
  include ("phpagi-asmanager.php");

  print_r ($_SERVER['argv']);

  $canal = $_SERVER['argv'][1];

  $asm = new AGI_AsteriskManager();

  $asm->connect("127.0.0.1","lponce","lponce");

  $resultado = $asm->StopMonitor($canal);

  $asm->disconnect();
  sleep(3);


?>

