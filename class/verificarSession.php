<?php
	session_start();
	if (count($_SESSION) == 0) {
		echo 'true';
	}else{
		echo 'false';
	}

 ?>