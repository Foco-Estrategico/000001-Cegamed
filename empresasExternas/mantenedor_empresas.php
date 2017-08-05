<?php
require_once('../db/db.php');
include("../class/global/global.php");
require_once('../class/session/session.php');
$objetoSession = new Session('1,2',false); // 1,4
//Para Id de Menu Actual (Menu Padre, Menu hijo)
$objetoSession->crearVariableSession($array = array("idMenu" => "adm,sis,emp_ext"));
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
if (isset($_SESSION['cedente'])){
    $cedente = $_SESSION['cedente'];
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
    <link href="../css/nifty.min.css" rel="stylesheet">
    <link href="../premium/icon-sets/solid-icons/premium-solid-icons.min.css" rel="stylesheet">
    <link href="../plugins/ionicons/css/ionicons.min.css" rel="stylesheet">
    <link href="../plugins/themify-icons/themify-icons.min.css" rel="stylesheet">
    <link href="../css/demo/nifty-demo-icons.min.css" rel="stylesheet">
    <link href="../plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="../plugins/animate-css/animate.min.css" rel="stylesheet">
    <link href="../plugins/switchery/switchery.min.css" rel="stylesheet">
    <link href="../plugins/morris-js/morris.min.css" rel="stylesheet">
    <link href="../css/demo/nifty-demo.min.css" rel="stylesheet">
    <link href="../plugins/pace/pace.min.css" rel="stylesheet">
    <script src="../plugins/pace/pace.min.js"></script>
    <link href="../plugins/bootstrap-select/bootstrap-select.min.css" rel="stylesheet">
    <link href="../plugins/bootstrap-dataTables/jquery.dataTables.css" rel="stylesheet"  media="screen">
    <link href="../plugins/bootstrap-datepicker/bootstrap-datepicker.min.css" rel="stylesheet">
    <style type="text/css">
    .select1
             {
        width: 100%;
        height: 30px;
        border: solid;
        border-color: #ccc;
        background-color: #CEECF5;

             }
    .select2
            {
        width: 100%;
        height: 30px;
        border: solid;
        border-color: #ccc;
        background-color: #CCC;

            }
    .text1
            {
        width: 100%;
        height: 30px;
        border: solid;
        border-color: #ccc;
        background-color: #CEECF5;

            }
    .text2
            {
        width: 100%;
        height: 30px;
        border: solid;
        border-color: #ccc;
        background-color: #CCC;

            }
    .mostrar_condiciones
           {
           }
    #midiv100
           {
            display: none;
           }

    #oculto
           {
            display: none;
           }
    #guardar
           {
            display: none;
           }
    #folder
           {
            display: none;
           }
    .modal {
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

 #divtablapeq {
    width: 500px;
    }
 #divtablamed {
    width: 600px;
    }
    .dropdown-menu.open {
    max-height: none !important;
}

    </style>
</head>
<body>
  <input type="hidden" name="cedente" id="cedente" value="<?php echo $cedente;?>">
  <div id="container" class="effect mainnav-sm">
    <!--NAVBAR-->
    <!--===================================================-->
    <?php
    include("../layout/header.php");
    ?>
    <!--===================================================-->
    <!--END NAVBAR-->
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
          <ol class="breadcrumb">
            <li><a href="#">Administrador</a></li>
            <li class="active">Mantenedor Empresa Externa</li>
          </ol>
          <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
          <!--End breadcrumb-->
          <!--Page content-->
          <!--===================================================-->
          <div id="page-content">
            <div class="row">
						  <div class="eq-height">
  						  <div class="col-sm-12 eq-box-sm">
                  <div id="contenedor"></div>
                    <div class="panel" id='sql'>
                      <div class="panel-heading">
                        <h2 class="panel-title"> <i class="fa fa-pencil-square-o"></i> Mantenedor </h2>
                      </div>
                      <!-- Panel model -->
                      <!--===================================================-->
                      <div class="panel-body">
                        <div class="col-sm-12">
                        <!-- INICIO CONTENIDO PRINCIPAL -->
                           <!-- Inicio listar empresas -->
                            <div class="table-responsive">
                               <div class="row">
                                   <div class="col-sm-3">
                                      <button style="margin: 10px 0;" id="AddEmpresa" class="btn btn-primary btn-block">Agregar Empresa Externa</button>
                                   </div>
                               </div>

                                <table id="listaEmpresas" class="display" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th style="width:90%">Empresa</th>
                                            <!-- <th>Campos</th> -->
                                            <th style="width:10%">Acción</th>
                                        </tr>
                                    </thead>
                                 </table>
                            </div>
                           <!-- Fin listar empresas -->
                           <!-- Inicio registrar empresa -->
                            <script id="RegistrarEmpresa" type="text/template">
                              <div class="row">
                              <div class="col-sm-12">
                      <div >
                          <div class="panel-body">
                            <div class="row">
                            <div class="col-sm-4">
                              <div class="form-group">
                                <div class="form-group">
                                  <label class="control-label">Nombre</label>
                                  <input type="text" name="nombreEmpresa" id="nombreEmpresa" class="form-control" value="">
                                </div>
                              </div>
                            </div>
                            <div class="col-sm-4">
                              <div class="form-group">
                                <label for="sel1">Teléfono</label>
                                <input type="text" name="telefonoEmpresa" id="telefonoEmpresa" class="form-control" value="">
                              </div>
                            </div>
                            <div class="col-sm-4">
                              <div class="form-group">
                                <label class="control-label">Correo</label>
                                <input type="text" name="correoEmpresa" id="correoEmpresa" class="form-control" value="">
                              </div>
                            </div>
                          </div>
                            <div class="row">
                            <div class="col-sm-12">
                              <div class="form-group">
                                <label class="control-label">Dirección</label>
                                <input type="text" name="direccionEmpresa" id="direccionEmpresa" class="form-control" value="">
                              </div>
                            </div>
                            </div>
                          </div>
                      </div>
                  </div>
                </div>
                </script>
                <!--  Fin Asignar registrar empresa -->
                      <!-- Inicio modificar empresas -->
                      <script id="ModificarEmpresa" type="text/template">
                              <div class="row">
                              <div class="col-sm-12">
                      <div >
                          <div class="panel-body">
                            <div class="row">
                            <div class="col-sm-4">
                              <div class="form-group">
                                <div class="form-group">
                                  <label class="control-label">Nombre</label>
                                  <input type="text" name="nombreEmpresa" id="nombreEmpresa" class="form-control" value="">
                                </div>
                              </div>
                            </div>
                            <div class="col-sm-4">
                              <div class="form-group">
                                <label for="sel1">Teléfono</label>
                                <input type="text" name="telefonoEmpresa" id="telefonoEmpresa" class="form-control" value="">
                              </div>
                            </div>
                            <div class="col-sm-4">
                              <div class="form-group">
                                <label class="control-label">Correo</label>
                                <input type="text" name="correoEmpresa" id="correoEmpresa" class="form-control" value="">
                              </div>
                            </div>
                          </div>
                            <div class="row">
                            <div class="col-sm-12">
                              <div class="form-group">
                                <label class="control-label">Dirección</label>
                                <input type="text" name="direccionEmpresa" id="direccionEmpresa" class="form-control" value="">
                              </div>
                            </div>
                            </div>
                          </div>
                      </div>
                  </div>
                </div>
                           </script>
                          <!--  Fin modificiar empresas -->
                        <!-- FIN CONTENIDO PRINCIPAL -->
                        </div>
                      </div>
                      <!--===================================================-->
      								<!--End Panel model-->
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
        <?php include("../layout/footer.php"); ?>
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
    <script src="../js/bootstrap.min.js"></script>
    <script src="../plugins/fast-click/fastclick.min.js"></script>
    <script src="../js/nifty.min.js"></script>
	  <script src="../plugins/morris-js/morris.min.js"></script>
    <script src="../plugins/morris-js/raphael-js/raphael.min.js"></script>
    <script src="../plugins/sparkline/jquery.sparkline.min.js"></script>
    <script src="../plugins/skycons/skycons.min.js"></script>
    <script src="../plugins/switchery/switchery.min.js"></script>
    <script src="../plugins/bootstrap-select/bootstrap-select.min.js"></script>
    <script src="../js/demo/nifty-demo.min.js"></script>
    <script src="../plugins/bootbox/bootbox.min.js"></script>
    <script src="../js/demo/ui-alerts.js"></script>
    <script src="../plugins/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
    <script src="../plugins/bootstrap-dataTables/jquery.dataTables.js"></script>
    <script src="../js/global/funciones-global.js"></script>
    <script src="../js/empresasExternas/mantenedor_empresas.js"></script>

</body>
</html>
