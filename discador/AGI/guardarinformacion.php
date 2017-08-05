#!/usr/bin/php -q
<?php

    set_time_limit(30);
    require('phpagi.php');
    error_reporting(E_ALL);

    $agi = new AGI();

    $myfile = fopen("/var/www/html/AGI/newfile.txt", "w") or die("Unable to open file!");

    $txt = $agi->request['agi_type']."\n";
    fwrite($myfile, $txt);

    $txt = $agi->request['agi_channel']."\n";
    fwrite($myfile, $txt);

    $txt = $agi->request['agi_callerid']."\n";
    fwrite($myfile, $txt);

    $txt = $agi->request['agi_extension']."\n";
    fwrite($myfile, $txt);

    $txt = $argv[1] . "\n";
    fwrite($myfile, $txt);

    $fechaFin = date("Y-m-d H:i:s") . "\n";
    fwrite($myfile, $fechaFin);

    $txt = 'ID ' . $argv[2] . "\n";
    fwrite($myfile, $txt);


    $txt = 'pin ' . $argv[3] . "\n";
    fwrite($myfile, $txt);


    fclose($myfile);

    $agi->hangup();

?>

