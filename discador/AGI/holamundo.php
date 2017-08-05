#!/usr/bin/php -q
<?php

    set_time_limit(30);
    require('phpagi.php');
    error_reporting(E_ALL);

    $agi = new AGI();


    $agi->answer();
    // toca un archivo de audio
    $agi->stream_file('/var/lib/asterisk/sounds/demo-thanks');

    $agi->hangup();

?>

