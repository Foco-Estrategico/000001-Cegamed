<?php
  include ("phpagi-asmanager.php");

  print_r ($_SERVER['argv']);

  $cola = $_SERVER['argv'][1];
  $exten = $_SERVER['argv'][2];

  $asm = new AGI_AsteriskManager();

  $asm->connect("127.0.0.1","lponce","lponce");


  $resultado = $asm->QueueAdd($cola,$exten,"1");

  $asm->disconnect();
  sleep(3);


?>

