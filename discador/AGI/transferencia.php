<?php
  include ("phpagi-asmanager.php");

  print_r ($_SERVER['argv']);

  $miextension = $_SERVER['argv'][1];
  $laExtensionConQueHablo = $_SERVER['argv'][2];
  $dondeTransfiero = $_SERVER['argv'][3];

  $asm = new AGI_AsteriskManager();

  $asm->connect("127.0.0.1","lponce","lponce");

  $resultado = $asm->redirect($miextension,$laExtensionConQueHablo,$dondeTransfiero,'rob',"1");

  $asm->disconnect();


?>

