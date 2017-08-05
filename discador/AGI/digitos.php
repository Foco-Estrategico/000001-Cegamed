#!/usr/bin/php -q
<?php

    set_time_limit(30);
    require('phpagi.php');
    error_reporting(E_ALL);

    $agi = new AGI();


    $agi->answer();

    $fechaActual = date("Y-m-d H:i:s");
    $agi->set_variable('fechaInicio',$fechaActual);

    // toca un archivo de audio y recibe los ditigos marcados.
    $pin_array = $agi->get_data('conf-getpin', 30000, 20);

    $pin = $pin_array['result'];

    $agi->say_digits($pin);

//    $fechaActual = date("Y-m-d H:i:s");
//    $agi->set_variable('fechaInicio',$fechaActual);

    $agi->set_variable('id_unico','2017');

    $agi->set_variable('pin',$pin);
/*
    $agi->exec_dial('SIP','6326');
*/
    $agi->hangup();

?>

