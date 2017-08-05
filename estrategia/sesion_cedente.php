<?php
include('../class/session/session.php');
include('../class/db/DB.php');
$db = new DB();
$sql = "SELECT tipo, planDiscado FROM Cedente WHERE Id_Cedente = '".$_POST['cedente']."'";
$resultado = $db->select($sql);
$tipo = $resultado[0]['tipo'];
$plan = $resultado[0]['planDiscado'];
$objetoSession = new Session('1,2,3,4,5,6',false);
// ** Logout the current user. **
$objetoSession->creaLogoutAction();
if ((isset($_GET['doLogout'])) &&($_GET['doLogout']=="true"))
{
  //to fully log out a visitor we need to clear the session varialbles
    $objetoSession->borrarVariablesSession();
    $objetoSession->logoutGoTo("../index.php");
}
$objetoSession->creaMM_restrictGoTo();
$objetoSession->crearVariableSession($array = array("cedente" => $_POST['cedente'], "planDiscado" => $plan, "tipoFactura" => $tipo));
header('Location: ../bienvenida/bienvenida.php');
?>
