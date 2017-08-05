<?php
include_once("../../includes/functions/Functions.php");
include_once("../../class/email/email.php");
QueryPHP_IncludeClasses("db");
$email = new Email(); 
echo $email->verificacionCron();    
?>