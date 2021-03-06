<?PHP
require_once('../db/db.php');
include("../class/global/global.php");
require_once('../class/session/session.php');
$objetoSession = new Session('1,2',false);
// ** Logout the current user. **
$objetoSession->creaLogoutAction();
if ((isset($_GET['doLogout'])) &&($_GET['doLogout']=="true"))
{
  //to fully log out a visitor we need to clear the session varialbles
    $objetoSession->borrarVariablesSession();
    $objetoSession->logoutGoTo("../index.php");
}
$validar = $_SESSION['MM_UserGroup'];
$objetoSession->creaMM_restrictGoTo();
$usuario = $_SESSION['MM_Username'];
$cedente = $_SESSION['cedente'];
$nombreUsuario = $_SESSION['nombreUsuario'];
$id_estrategia = $_SESSION['IdEstrategia'];
$idUsuarioLogin = $_SESSION['id_usuario'];
if(empty($id_estrategia))
{
    header('Location: estrategias.php'); 
    exit;
}
else
{
    $id_estrategia = $id_estrategia;
}
$sql=mysql_query("SELECT nombre, id_usuario FROM SIS_Estrategias WHERE id=$id_estrategia AND Id_Cedente = $cedente ");
$query_row=mysql_query("SELECT * FROM SIS_Querys WHERE id_estrategia=$id_estrategia AND Id_Cedente = $cedente ");
while($row=mysql_fetch_array($sql))
{
    $nombre_estrategia = $row[0];
    $idUsuarioEstrategia = $row[1];
}
/**
  * Verifico sip el usuario conectado es el mismo que creo la estrategia
  * para asi dejarlo crear y deshacer, de lo contrario deshabilitar los botones
*/
if ($idUsuarioEstrategia == $idUsuarioLogin)
{
  $habilitado = "";
} else {
  $habilitado = "disabled='disabled'";
}

$QueryNombreEstrategia = mysql_query("SELECT Nombre FROM SIS_Estrategias WHERE id=$id_estrategia LIMIT 1");
while($row = mysql_fetch_array($QueryNombreEstrategia))
{
    $NombreEstrategia = $row[0];
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Foco | Software de Estrategia</title>
    <!--STYLESHEET-->
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/multiple.css" rel="stylesheet"/>
    <link href="../css/nifty.min.css" rel="stylesheet">
    <link href="../premium/icon-sets/solid-icons/premium-solid-icons.min.css" rel="stylesheet">
    <link href="../plugins/ionicons/css/ionicons.min.css" rel="stylesheet">
    <link href="../plugins/themify-icons/themify-icons.min.css" rel="stylesheet">
    <link href="../css/demo/nifty-demo-icons.min.css" rel="stylesheet">
    <link href="../plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="../plugins/chosen/chosen.min.css" rel="stylesheet">
    <link href="../plugins/animate-css/animate.min.css" rel="stylesheet">
    <link href="../plugins/switchery/switchery.min.css" rel="stylesheet">
    <link href="../plugins/morris-js/morris.min.css" rel="stylesheet">
    <link href="../css/demo/nifty-demo.min.css" rel="stylesheet">
    <link href="../plugins/bootstrap-select/bootstrap-select.min.css" rel="stylesheet">
    <link href="../plugins/bootstrap-datepicker/bootstrap-datepicker.min.css" rel="stylesheet">
    <link href="../plugins/bootstrap-validator/bootstrapValidator.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.13/css/jquery.dataTables.min.css" rel="stylesheet">
    <style type="text/css">
    
             
    .btn-group > .btn:first-child 
    {
        margin-left: 5px;
    }
    .text-transparent-max
    {
        width: auto;
        height: 20px;
        border: none;
        text-align: center;
        background-color:transparent;
    }

    .text-transparent-left
    {
        width: auto;
        height: 20px;
        border: none;
        text-align: left;
        background-color:transparent;
    }

    .text-transparent-min
    {
        width: auto;
        height: 20px;
        border: none;
        text-align: center;
        background-color:transparent;
    }
  

    .modal 
    {
        display:    none;
        position:   fixed;
        z-index:    1000;
        top:        0;
        left:       0;
        height:     100%;
        width:      100%;
        background: rgba( 255, 255, 255, .8 )
        url('../img/gears.gif')
        50% 50%
        no-repeat;
    }

    body.loading
    {
        overflow: hidden;
    }

    body.loading .modal
    {
        display: block;
    }

    </style>
</head>
<body>

    <div id="container" class="effect mainnav-sm">
         <?php
        include("../layout/header.php");
        ?>
        <div class="boxed">

            <!--CONTENT CONTAINER-->
            <!--===================================================-->
            <div id="content-container">

                <!--Page Title-->
                <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                <div id="page-title">

                    <!--Searchbox-->

                </div>
                <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                <!--End page title-->


                <!--Breadcrumb-->
                <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                
                <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                <!--End breadcrumb-->




                <!--Page content-->
                <!--===================================================-->
                <div id="page-content">
                    <input type="hidden" id="IdCedente" value="<?php echo $_SESSION['cedente']; ?>">
                    <input type="hidden" id="IdEstrategia" value="<?php echo $_SESSION['IdEstrategia']; ?>">
                    <input type="hidden" id="IdSubQuery" value=0>
					<div class="row">
                        <div class="col-sm-12">
                            <div class="panel">
                                
                                <div class="panel-body">
                                    <div class="alert alert-warning fade in">
                                        <div id="DivRegistros">
                                        </div>
                                        
                                    </div>
                                    <div class="col-sm-1">
                                        <label>Tipo</label>
                                        <div id="DivTipo">
                                            
                                            <select class="selectpicker"  id="SeleccioneTipoEstrategia" data-width="100%">
                                                <option value="-1">Tipo </option>
                                                <option value="0">Estática</option>
                                                <option value="1">Dinámica</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-1">
                                        <label>Tabla</label>

                                        <div id="DivTabla">
                                            <select class="selectpicker" disabled="disabled" data-width="100%">
                                                <option>Tabla</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <label>Columna</label>
                                        <div id="DivColumna">
                                            <select class="selectpicker" disabled="disabled" data-width="100%">
                                                <option>Seleccione Columna</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-1">
                                        <label>Logica</label>
                                        <div id="DivLogica">
                                            <select class="selectpicker" disabled="disabled" data-width="100%">
                                                <option>Lógica</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <label>Valor</label>
                                        <div id="DivValor">
                                            <select class="selectpicker" disabled="disabled" data-width="100%">
                                                <option>Seleccione Valor</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <label>Nombre</label>
                                        <div id="DivCola">
                                            <input type="text" class="form-control " disabled="disabled">
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <label>Color</label>
                                        <div id="DivColor">
                                            <select class="selectpicker" disabled="disabled" data-width="100%">
                                                <option>Seleccione Tabla</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-1">
                                        <label>Crear</label>
                                        <div id='DivBoton'>
                                            <button class="btn btn-info col-sm-12 " disabled="disabled" id="CrearEstrategia">Crear </button>
                                        </div>
                                    </div>
                                </div>
                            </div> 
                        </div> 
                        </div>
                        <div class="row">
                        <div class="col-sm-12">
                            <div class="panel">
                                        <h2 class="panel-title"> <i class="fa fa-code-fork"></i> Resultado de Segmentación para la Estrategia : <b><?php echo $NombreEstrategia;?></b> <i class="btn btn-danger btn-icon icon-sm fa fa-trash pull-right " id="Deshacer"></i> <i class="btn btn-success btn-icon icon-sm fa fa-refresh pull-right " id="Actualizar"></i></h2>
                                
                                <div class="panel-body">
                                    <div id="DivMostrarEstrategias">
                                    </div>
                                </div>  
                                  
                            </div>    
                        </div>   
                            
					</div>
                </div>
                <!--===================================================-->
                <!--End page content-->


            </div>
            <!--===================================================-->
            <!--END CONTENT CONTAINER-->



            <!--MAIN NAVIGATION-->
            <!--===================================================-->
             <?php include("../layout/main-menu.php"); ?>
            <!--===================================================-->
            <!--END MAIN NAVIGATION-->

        </div>
        <!-- FOOTER -->
        <!--===================================================-->
        <footer id="footer">
            <!-- Visible when footer positions are fixed -->
            <!-- ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ -->
            <div class="show-fixed pull-right">
                <ul class="footer-list list-inline">
                </li>
                </ul>
            </div>

        </footer>
        <!--===================================================-->
        <!-- END FOOTER -->
        <!-- SCROLL TOP BUTTON -->
        <!--===================================================-->
        <button id="scroll-top" class="btn"><i class="fa fa-chevron-up"></i></button>
        <div class="modal"><!-- Place at bottom of page --></div>
        <!--===================================================-->
    </div>
    <!--===================================================-->
    <!-- END OF CONTAINER -->
    <!--JAVASCRIPT-->
    <script src="../js/jquery-2.2.1.min.js"></script>
    <script src="../js/estrategia/Estrategias.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../plugins/fast-click/fastclick.min.js"></script>
    <script src="../js/nifty.min.js"></script>
	<script src="../plugins/morris-js/morris.min.js"></script>
    <script src="../plugins/morris-js/raphael-js/raphael.min.js"></script>
    <script src="../plugins/sparkline/jquery.sparkline.min.js"></script>
    <script src="../plugins/skycons/skycons.min.js"></script>
    <script src="../plugins/switchery/switchery.min.js"></script>
    <script src="../plugins/bootstrap-select/bootstrap-select.min.js"></script>
    <script src="../plugins/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
    <script src="../plugins/bootstrap-validator/bootstrapValidator.min.js"></script>
    <script src="../js/demo/nifty-demo.min.js"></script>
    <script src="../plugins/bootbox/bootbox.min.js"></script>
    <script src="../js/demo/ui-alerts.js"></script>
    <script src="../js/global/funciones-global.js"></script>
    <script src="../js/demo/nifty-demo.min.js"></script>
    <script src="../js/demo/ui-modals.js"></script>




</body>
</html>