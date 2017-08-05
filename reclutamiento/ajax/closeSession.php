<?php
	session_start();
	unset( $_SESSION["idUsuario_reclutamiento"]);
	unset($_SESSION["idEmpresa_reclutamiento"]);
	header("Location: ../");
 ?>